<?php
/**
 * Created by PhpStorm.
 * User: zdl25
 * Date: 2019/1/21
 * Time: 10:50
 */
if(!defined('WP_UNINSTALL_PLUGIN')){
	exit();
}

delete_option( 'wposs_options' );
