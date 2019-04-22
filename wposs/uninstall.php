<?php
/**
 * Created by PhpStorm.
 * User: zdl25
 * Date: 2019/1/21
 * Time: 10:50
 */
if(!defined('WP_UNINSTALL_PLUGIN')){
	// 如果 uninstall 不是从 WordPress 调用，则退出
	exit();
}

// 恢复初始值
$wposs_options = get_option('wposs_options');
update_option('upload_path', $wposs_options['upload_information']['original']['upload_path']);
update_option('upload_url_path', $wposs_options['upload_information']['original']['upload_url_path']);

// 从 options 表删除选项
delete_option( 'wposs_options' );

// 删除其他额外的选项和自定义表