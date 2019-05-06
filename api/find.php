<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
// 接口名：发现页面
// 接口功能：获取六个分类，获取几篇最新文章，获取六个标签
// 教程地址：https://www.inacorner.top/455.html
header('Access-Control-Allow-Headers:*'); 
//引入WP加载文件，引入之后就可以使用WP的所有函数 
require( '../../../../wp-load.php' );

//定义返回数组，默认先为空
$data = [];

// *******************************************获取六个分类*************************************
//获取所有分类id
$catIds = get_all_category_ids();
// 定义分类返回数组，默认为空
$cats = [];
// 根据分类id获取分类名称并且push进$cats,只需要六个所以循环六次
for ($i = 0; $i <6 ; $i++) {
	$catList['ID'] = $catIds[$i];
	$catList['name'] = get_cat_name($catIds[$i]);
	$cats[$i] = $catList;
}

// *****************************************获取六个标签**************************************
// 定义标签返回数组，默认为空
$tags=[];
      //wp获取标签函数
$tagData=get_tags(array('hide_empty'=>false));

// 将标签push进$tags，只需要六个所以循环六次
for ($i = 0; $i <6; $i++) {
	$tagLi['ID']=$tagData[$i]->term_id;
	$tagLi['name']=$tagData[$i]->name;
	$tags[$i] = $tagLi;
}

//***************************************获取最新文章**************************************
// 定义文章返回数组，默认为空
$posts=[];
// 使用wp的查询文章函数查询最新文章列表
// 1、定义查询条件
$args = array( 
	'post_type'=>'post',  //查询文章类型
	'post_status'=>'publish', //查询文章状态
	'posts_per_page'=>6,  //显示6篇文章
	'orderby'=>'date',  //按照时间排序
	'order'=>'DESC'
);
// 2、开始查询文章
query_posts($args);
if (have_posts()){ //如果查询出来了文章
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
}else {
	// 如果没有文章
	$posts=[];
}

//*********************************返回值***********************************************

$data['cats'] = $cats;
$data['tags'] = $tags;
$data['posts'] = $posts;
print_r(json_encode($data));
?>