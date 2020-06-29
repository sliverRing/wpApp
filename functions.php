<?php
//开启缩略图
add_theme_support( 'post-thumbnails' ); 
// 后台框架
// if (!class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/admin/ReduxCore/framework.php' ) ) {
// 	require_once( dirname( __FILE__ ) . '/admin/ReduxCore/framework.php' );
// }
// if (!isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/admin/sample/sample-config.php' ) ) {
// 	require_once( dirname( __FILE__ ) . '/admin/sample/sample-config.php' );
// }

//允许中文名注册
function allowed_chinese_name ($username, $raw_username, $strict) {
	$username = wp_strip_all_tags( $raw_username );
	$username = remove_accents( $username );
	$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
	$username = preg_replace( '/&.+?;/', '', $username ); 
	if ($strict) {
		$username = preg_replace ('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
	}
	$username = trim( $username );
	$username = preg_replace( '|\s+|', ' ', $username );
	return $username;
}
add_filter ('sanitize_user', 'allowed_chinese_name', 10, 3);
function jinsom_update_user_login($user_id,$user_login){
	global $wpdb;
	if($wpdb->query( "UPDATE $wpdb->users SET user_login = '$user_login' WHERE ID=$user_id;" ))
	return 1;
	return 0;
}
// 引入composer包
require_once 'vendor/autoload.php';