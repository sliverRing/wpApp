<?php
// 接口名：首页文章列表
// 教程地址：https://www.inacorner.top/395.html
// 可传参数：page(页数)
header('Access-Control-Allow-Headers:*'); 
//引入WP加载文件，引入之后就可以使用WP的所有函数 
require( '../../../../wp-load.php' );


// 这里为了方便后期的下拉加载更多，我们可以定义一个页码参数
$page=@$_GET['$page'];

//定义返回数组，默认先为空
$data=[];

// 使用wp的查询文章函数查询最新文章列表
// 1、定义查询条件
$args = array( 
	'post_type'=>'post',  //查询文章类型
	'post_status'=>'publish', //查询文章状态
	'posts_per_page'=>10,  //一页显示十篇文章
	'paged'=>$page,  //第几页，默认为1
	'orderby'=>'date',  //按照时间排序
	'order'=>'DESC'
);
// 2、开始查询文章
query_posts($args);
if (have_posts()){ //如果查询出来了文章
	// 定义接收文章数据的数组
	$posts=[];
	// 循环文章数据
	while ( have_posts() ) : the_post();
		// 获取文章id
		$post_id=get_the_ID();
		// 定义单条文章所需要的数据
		$list=[
			"id"=>$post_id,  //文章id
			"title"=>get_the_title(), //文章标题
			"img"=>get_the_post_thumbnail_url() //文章缩略图
		];
		// 将每一条数据分别添加进$posts
		array_push($posts,$list);
	endwhile;
	// 定义返回值
	$data['code']=200;
	$data['msg']="查询数据成功！";
	$data['post']=$posts;
}else {
	// 如果没有文章
	$data['code']=404;
	$data['msg']="没有相关文章";
	$data['post']=[];
}
// 输入json数据格式
print_r(json_encode($data));
?>

