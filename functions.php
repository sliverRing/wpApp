<?php
//开启缩略图
add_theme_support( 'post-thumbnails' ); 
// 后台框架
if (!class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/admin/ReduxCore/framework.php' ) ) {
	require_once( dirname( __FILE__ ) . '/admin/ReduxCore/framework.php' );
}
if (!isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/admin/sample/sample-config.php' ) ) {
	require_once( dirname( __FILE__ ) . '/admin/sample/sample-config.php' );
}