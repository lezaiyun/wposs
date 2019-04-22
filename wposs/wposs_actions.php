<?php
require_once 'wposs_api.php';

use WPOSS\Api;

define( 'WPOSS_VERSION', '0.2' );
define( 'WPOSS_MINIMUM_WP_VERSION', '4.0' );  // 最早WP版本
define( 'WPOSS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );  // 插件路径
define('WPOSS_BASENAME', plugin_basename(__FILE__));
define('WPOSS_BASEFOLDER', plugin_basename(dirname(__FILE__)));

// 初始化选项
function wposs_set_options() {
	$options = array(
		'bucket' => "",
		'endpoint' => "",
		'accessKeyId' => "",
		'accessKeySecret' => "",
		'no_local_file' => "false",  # 不在本地保留备份
		'cname' => False,  // true为开启CNAME。CNAME是指将自定义域名绑定到存储空间上。可以用来代替ENDPOINT
		'upload_information' => array(
			'original' => array(
				'upload_path' => '',
				'upload_url_path' => '',
			),
			'active' => array(
				'upload_path' => '',
				'upload_url_path' => '',
			),
		),
	);
	$wposs_options = get_option('wposs_options', False);
	if(!$wposs_options){
		$options['upload_information']['original']['upload_path'] = get_option('upload_path');
		$options['upload_information']['original']['upload_url_path'] = get_option('upload_url_path');
		add_option('wposs_options', $options, '', 'yes');
	}else{
		update_option('upload_path', $wposs_options['upload_information']['active']['upload_path']);
		update_option('upload_url_path', $wposs_options['upload_information']['active']['upload_url_path']);
	};
}

function wposs_restore_options() {
	$wposs_options = get_option('wposs_options');
	update_option('upload_path', $wposs_options['upload_information']['original']['upload_path']);
	update_option('upload_url_path', $wposs_options['upload_information']['original']['upload_url_path']);
}


/**
 * 删除本地文件
 * @param $file_path : 文件路径
 * @return bool
 */
function wposs_delete_local_file($file_path) {
	try {
		# 文件不存在
		if (!@file_exists($file_path)) {
			return TRUE;
		}
		# 删除文件
		if (!@unlink($file_path)) {
			return FALSE;
		}
		return TRUE;
	} catch (Exception $ex) {
		return FALSE;
	}
}


/**
 * 删除附件（包括图片的原图）
 * @param $post_id
 */
function wposs_delete_remote_attachment($post_id) {
	$meta = wp_get_attachment_metadata( $post_id );

	if (isset($meta['file'])) {
		// meta['file']的格式为 "2011/12/press_image.jpg"
		$wp_uploads = wp_upload_dir();
		// 示例: [basedir] => C:\path\to\wordpress\wp-content\uploads
		$file_path = $wp_uploads['basedir'] . '/' . $meta['file'];
		$oss = new Api(get_option('wposs_options'));
		// 得到远程路径, get_home_path 示例： "Path: /var/www/htdocs/" or "Path: /var/www/htdocs/wordpress/"
		$oss->delete_file(str_replace(get_home_path(), '', str_replace("\\", '/', $file_path)));

		if (isset($meta['sizes']) && count($meta['sizes']) > 0) {
			foreach ($meta['sizes'] as $val) {
				$size_file = dirname($file_path) . '/' . $val['file'];
				$oss->delete_file(str_replace(get_home_path(), '', str_replace("\\", '/', $size_file)));
			}
		}
	}
}


/**
 * 上传附件（包括图片的原图）
 * @param $metadata
 * @return array()
 */
function wposs_upload_attachments($metadata) {
	# 生成object在OSS中的存储路径
	if (get_option('upload_path') == '.') {
		//如果含有“./”则去除之
		$metadata['file'] = str_replace("./", '', $metadata['file']);
	}
	# 必须先替换\\, 因为get_home_path的输出格式为 "Path: /var/www/htdocs/" or "Path: /var/www/htdocs/wordpress/"
	$key = str_replace(get_home_path(), '', str_replace("\\", '/', $metadata['file']));;

	# 在本地的存储路径
	$file = get_home_path() . $key;  //早期版本 $metadata['file'] 为相对路径

	# 调用上传函数
	$oss = new Api(get_option('wposs_options'));
	$oss->upload_file($key, $file);

	return $metadata;
}


/**
 * 上传图片的缩略图
 * @param $metadata
 * @return array
 */
function wposs_upload_thumbs($metadata) {
	# 上传所有缩略图
	if (isset($metadata['sizes']) && count($metadata['sizes']) > 0) {

		$wposs_options = get_option('wposs_options', True);
		$oss = new Api($wposs_options);

		# 若不上传缩略图则直接返回
		if (esc_attr($wposs_options['no_remote_thumb']) == 'true') {
			return $metadata;
		}

		# 获取上传路径
		$wp_uploads = wp_upload_dir();
		//得到本地文件夹和远端文件夹
		$file_path = $wp_uploads['basedir'] . '/' . dirname($metadata['file']) . '/';
		if (get_option('upload_path') == '.') {
			$file_path = str_replace(get_home_path() . "./", '', str_replace("\\", '/', $file_path));
		} else {
			$file_path = str_replace("\\", '/', $file_path);
		}

		// 文件名可能相同，上传操作时会判断是否存在，如果存在则不会执行上传。
		foreach ($metadata['sizes'] as $val) {
			//生成object在COS中的存储路径
			$key = str_replace(get_home_path(), '', $file_path) . $val['file'];
			//生成本地存储路径
			$file = $file_path . $val['file'];

			//执行上传操作
			$oss->upload_file($key, $file);

			# 不保存本地文件则删除
			if (esc_attr($wposs_options['no_local_file']) == 'true') {
				wposs_delete_local_file($file_path . $val['file']);
			}
		}
		// 删除主文件
		if (esc_attr($wposs_options['no_local_file']) == 'true') {
			wposs_delete_local_file($wp_uploads['basedir'] . '/' . $metadata['file']);
	    }
	}
	
	return $metadata;
}


// 在导航栏“设置”中添加条目
function wposs_add_setting_page() {
	if (!function_exists('wposs_setting_page')) {
		require_once 'wposs_setting_page.php';
	}
	add_menu_page('WPOSS设置', 'WPOSS设置', 'manage_options', __FILE__, 'wposs_setting_page');
}
