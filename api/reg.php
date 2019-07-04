<?php
//引入WP加载文件，引入之后就可以使用WP的所有函数 
require( '../../../../wp-load.php' );
//允许跨域
header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); 
header('Access-Control-Allow-Credentials: true'); 
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin'); 
$user_name = $_POST['user_name'];
$user_pwd = $_POST['user_pwd'];
$user_email = $_POST['user_email'];
//定义返回数组，默认先为空
$data=[];
if($user_name=''&&$user_pwd==''&&$user_email=''){
	$data['code'] = 404;
	$data['msg'] = '请完善注册表单！';
}else {
	// 判断用户是否存在
	if(username_exists($user_name)){
		$data['code'] = 404;
		$data['msg'] = '当前用户名已经存在！';
	}else {
		// 判断邮箱是否存在
		if(email_exists($user_email)){
			$data['code'] = 404;
			$data['msg'] = '当前邮箱已经存在！';
		}else {
			if(is_email($user_email)){
			    //定义用户数据
				$userdata = array(
					'user_pass'=>$user_pwd,
					'user_login'=>$user_name,
					'user_nicename'=>$user_name,
					'user_email'=>$user_email,
					'display_name'=>$user_name,
				);
			    //使用wp函数插入用户
				$user_id =(int)wp_insert_user($userdata);
				if($user_id >0){
					$data['code'] = 200;
					$data['msg'] = '注册成功！';
				}else{
					$data['code'] = 404;
					$data['msg'] = '注册失败，服务器错误！';
				}
			}else{
				$data['code'] = 404;
				$data['msg'] = '邮箱地址格式错误！';
			}
		}
	}
}
print_r(json_decode($data));