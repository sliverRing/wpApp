<?php
/**
 * 2020 02 19修改
 */
//引入WP加载文件，引入之后就可以使用WP的所有函数 
require( '../../../../wp-load.php' );
//允许跨域
header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); 
header('Access-Control-Allow-Credentials: true'); 
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin'); 
/**
 * 接收数据
 * @var [type]
 */
$user_name = $_POST['user_name'];
$user_pwd = $_POST['user_pwd'];
$user_email = $_POST['user_email'];
//定义返回数组，默认先为空
$data=[];
if($user_name=''&&$user_pwd==''&&$user_email=''){
	$data['code'] = 404;
	$data['msg'] = '请完善注册表单！';
	print_r(json_decode($data));
	exit();
}
// 判断用户是否存在
if(username_exists($user_name)){
	$data['code'] = 404;
	$data['msg'] = '当前用户名已经存在！';
	print_r(json_decode($data));
	exit();
}
/**
 * 检测邮箱是否正确
 */

if(!is_email($user_email)){
	$data['code']  = 404;
	$data['msg']   ='邮箱格式不正确！';
	print_r(json_encode($data));
	exit();
}

// 判断邮箱是否存在
if(email_exists($user_email)){
	$data['code'] = 404;
	$data['msg'] = '当前邮箱已经存在！';
	print_r(json_encode($data));
	exit();
}
//定义用户数据
$userdata = array(
	'user_pass'=>$user_pwd,
	'user_login'=>$user_name,
	'user_nicename'=>$user_name,
	'user_email'=>$user_email,
	'display_name'=>$user_name,
);
//使用wp函数插入用户:插入成功返回用户id，失败 返回WP_Error 
$is_Err = wp_insert_user($userdata);
if(is_wp_error($is_Err)){
	$data['code'] = 404;
	$data['msg']  = '注册失败，请稍后再试';
	print_r(json_encode($data));
	exit();
}
$data['code'] = 200;
$data['msg']  = '欢迎你，注册成功';
print_r(json_encode($data));
exit();