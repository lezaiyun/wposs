<?php
/**
Plugin Name: WordPress OSS（WPOSS）
Plugin URI: https://www.laobuluo.com/2250.html
Description: WordPress同步附件内容远程至阿里云OSS对象存储中，实现网站数据与静态资源分离，提高网站加载速度。站长互助QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>
Version: 0.1
Author: 老部落（By:zdl25）
Author URI: https://www.laobuluo.com
*/

require_once 'wposs_actions.php';


register_activation_hook(__FILE__, 'wposs_set_options');

if (substr_count($_SERVER['REQUEST_URI'], '/update.php') <= 0) {
	add_filter('wp_handle_upload', 'wposs_upload_attachments');
	add_filter('wp_generate_attachment_metadata', 'wposs_upload_thumbs');
}

add_action('delete_attachment', 'wposs_delete_remote_attachment');

add_action('admin_menu', 'wposs_add_setting_page');
