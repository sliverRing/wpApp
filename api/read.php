<?php
//引入WP加载文件，引入之后就可以使用WP的所有函数 
require( '../../../../wp-load.php' );
//允许跨域
header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); 
header('Access-Control-Allow-Credentials: true'); 
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

//定义返回数组，默认先为空
$data=[];

$post_id = (int)$_GET['post'];
if(!$post_id){
	$data['code'] = 404;
	$data['msg'] = '请选择一篇文章';
	echo json_encode($data);
	die();
}
//调用WordPress函数获取文章信息
$post_data = get_postdata($post_id);
if(!$post_data){
	$data['code'] = 404;
	$data['msg'] = '该文章不存在';
	echo json_encode($data);
	die();
}
$data['code'] = 200;
$data['msg'] = '文章数据获取成功';
$data['data'] = $post_data;
echo json_encode($post_data);