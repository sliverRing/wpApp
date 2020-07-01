<?php
namespace SliverApi;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
/**
 * Token方法：https://www.clearnull.com/951.html
 */
class Token{
	public function __construct(){

	}
	/**
	 * 生成Token
	 * @param [type] $uid [description]
	 * @param [type] $pwd [description]
	 */
	public static function setToken($uid,$pwd){
		$time         = time();
		$issuedBy     = get_home_url();//WordPress获取主页URL
		$permittedFor = get_home_url();//WordPress获取主页URL
		$pwd          = md5($pwd);
		$signer       = new Sha256();
		$token = (new Builder())->issuedBy($issuedBy) // 配置Token发行者一般为网站域名
		->permittedFor($permittedFor) // 配置Token在什么地方可用一般为网站域名
		->identifiedBy($pwd, true) //配置唯一签名值，此处使用WordPress加密过的用户密码,为了安全我们再MD5一次
		->issuedAt($time) // 配置Token发行时间，这里为当前时间
		->canOnlyBeUsedAfter($time) // 配置Token发行之后多长时间后可以使用，这里为立刻可以使用
		->expiresAt($time + 36000) // 配置Token失效时间，这里为36000秒之后
		->withClaim('uid', $uid) // 配置uid，这里为WordPress的user_id
		->getToken($signer,new Key(APP_TOKENKEY));//APP_TOKENKEY是什么可以看这里：https://www.clearnull.com/942.html
		return (string)$token;
	}
	/**
	 * 校验Token
	 */
	public static function checkToken($token,$pwd){
		$signer       = new Sha256();
		$data         = new ValidationData();
		$issuedBy     = get_home_url();
		$permittedFor = get_home_url();
		$data->setIssuer($issuedBy);
		$data->setAudience($permittedFor);
		try {
			$token        = (new Parser())->parse((string) $user_token);
		} catch (\Lcobucci\JWT\Exception $e) {
			$_data['code'] = 500;
			$_data['msg']  = 'Token值非法';
			return $_data;
		}
		$uid          = $token->getClaim('uid');
		$pwd          = md5($pwd);//WordPress加密过的密码并且按照我们生成Token的逻辑再MD5一次

		$if_auth      = $token->verify($signer, APP_TOKENKEY);//APP_TOKENKEY是什么可以看这里：https://www.clearnull.com/942.html

		if(!$if_auth){
			$_data['code'] = 500;
			$_data['msg']  = '登录失效，请重新登录';
			return $_data;
		}

		$data->setId($pwd);
		$if_auth      =  $token->validate($data);
		
		if(!$if_auth){
			$_data['code'] = 500;
			$_data['msg']  = '登录失效，请重新登录';
			return $_data;
		}

		//鉴权成功，使用WordPress方法返回用户数据
		$_data['code']      = 200;
		$_data['user_data'] = get_userdata($uid);
		return $_data;

	}
}