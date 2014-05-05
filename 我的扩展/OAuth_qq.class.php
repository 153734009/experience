<?php
/*
  +----------------------------------------------------------------+
  * OAuth_qq 
  * 只有登录，不支持WAP登录
  * 	
  +----------------------------------------------------------------+
  使用方法：
	import("@.ORG.OAuth.OAuth_qq");
	$callback = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$qc = new OAuth_qq($appid,$appkey,$callback);
	if(!$_GET['code'])	$qc->qq_login();
	$access_token = $qc->qq_get_accessToken();
	$openid = $qc->qq_get_openid();
	$qq_info = $qc->get_user_info();
	print_r ($qq_info);
	...	
	...	接下去是你处理用户信息的代码，比如写入数据库等。
  +----------------------------------------------------------------+
 * 请配置 
 * 1.$appid	你申请的第三方登录appid
 * 2.$appkey	和appid一起的appkey
 * $qc = new OAuth_qq($appid,$appkey,$calback);（考虑修改为在实例化的时候）
  +----------------------------------------------------------------+
 */
class OAuth_qq{
	const VERSION = "OAuth2.0";
	const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
	const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
	const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";
	private static $data;
	private $APIMap = array(
			/*					   qzone					*/
			"add_blog" => array(
				"https://graph.qq.com/blog/add_one_blog",
				array("title", "format" => "json", "content" => null),
				"POST"
			),
			"add_topic" => array(
				"https://graph.qq.com/shuoshuo/add_topic",
				array("richtype","richval","con","#lbs_nm","#lbs_x","#lbs_y","format" => "json", "#third_source"),
				"POST"
			),
			"get_user_info" => array(
				"https://graph.qq.com/user/get_user_info",
				array("format" => "json"),
				"GET"
			),
			"add_one_blog" => array(
				"https://graph.qq.com/blog/add_one_blog",
				array("title", "content", "format" => "json"),
				"GET"
			),
			"add_album" => array(
				"https://graph.qq.com/photo/add_album",
				array("albumname", "#albumdesc", "#priv", "format" => "json"),
				"POST"
			),
			"upload_pic" => array(
				"https://graph.qq.com/photo/upload_pic",
				array("picture", "#photodesc", "#title", "#albumid", "#mobile", "#x", "#y", "#needfeed", "#successnum", "#picnum", "format" => "json"),
				"POST"
			),
			"list_album" => array(
				"https://graph.qq.com/photo/list_album",
				array("format" => "json")
			),
			"add_share" => array(
				"https://graph.qq.com/share/add_share",
				array("title", "url", "#comment","#summary","#images","format" => "json","#type","#playurl","#nswb","site","fromurl"),
				"POST"
			),
			"check_page_fans" => array(
				"https://graph.qq.com/user/check_page_fans",
				array("page_id" => "314416946","format" => "json")
			),

			/*					wblog							 */
			"add_t" => array(
				"https://graph.qq.com/t/add_t",
				array("format" => "json", "content","#clientip","#longitude","#compatibleflag"),
				"POST"
			),
			"add_pic_t" => array(
				"https://graph.qq.com/t/add_pic_t",
				array("content", "pic", "format" => "json", "#clientip", "#longitude", "#latitude", "#syncflag", "#compatiblefalg"),
				"POST"
			),
			"del_t" => array(
				"https://graph.qq.com/t/del_t",
				array("id", "format" => "json"),
				"POST"
			),
			"get_repost_list" => array(
				"https://graph.qq.com/t/get_repost_list",
				array("flag", "rootid", "pageflag", "pagetime", "reqnum", "twitterid", "format" => "json")
			),
			"get_info" => array(
				"https://graph.qq.com/user/get_info",
				array("format" => "json")
			),
			"get_other_info" => array(
				"https://graph.qq.com/user/get_other_info",
				array("format" => "json", "#name", "fopenid")
			),
			"get_fanslist" => array(
				"https://graph.qq.com/relation/get_fanslist",
				array("format" => "json", "reqnum", "startindex", "#mode", "#install", "#sex")
			),
			"get_idollist" => array(
				"https://graph.qq.com/relation/get_idollist",
				array("format" => "json", "reqnum", "startindex", "#mode", "#install")
			),
			"add_idol" => array(
				"https://graph.qq.com/relation/add_idol",
				array("format" => "json", "#name-1", "#fopenids-1"),
				"POST"
			),
			"del_idol" => array(
				"https://graph.qq.com/relation/del_idol",
				array("format" => "json", "#name-1", "#fopenid-1"),
				"POST"
			),
			/*						   pay						  */
			"get_tenpay_addr" => array(
				"https://graph.qq.com/cft_info/get_tenpay_addr",
				array("ver" => 1,"limit" => 5,"offset" => 0,"format" => "json")
			)
		);

	protected $appid = '101012291';
	protected $appkey = '6bbb2df11ccd17eaa6c35b28b600dffb';
	protected $scope = '';
	protected $callback = 'www.aiweiwang.cn/index.php?m=Index&a=Oauth';
	function __construct(){
		if(empty($_SESSION['OAuth_qq_data'])){
		    self::$data = array();
		}else{
		    self::$data = $_SESSION['OAuth_qq_data'];
		}
	}
	public function qq_login(){
		//$state = md5(uniqid(rand(), TRUE));
		$state = session_id();
		$this->write('state',$state);
		$url = self::GET_AUTH_CODE_URL.'?response_type=code&client_id='.$this->appid.'&redirect_uri='.urlencode($this->callback).'&state='.$state;
		header("Location:$url");
		exit;
	}
	public function qq_get_accessToken(){
		//--------验证state防止CSRF攻击
		if($_GET['state'] != session_id())		$this->showError("30001");
		$url = self::GET_ACCESS_TOKEN_URL.'?grant_type=authorization_code&client_id='.$this->appid.'&redirect_uri='.urlencode($this->callback).'&client_secret='.$this->appkey.'&code='.$_GET['code'];
		$response = $this->cURL($url);
		if(strpos($response, "callback") === false){
			$access_token = substr($response,13,32);
			$this->write('access_token',$access_token);
			return ($access_token);
		}else{
			echo $response;
			die();
		}
	}
	public function qq_get_openid(){
		$url = self::GET_OPENID_URL.'?access_token='.self::$data['access_token'];
		$response = $this->cURL($url);
		if(strpos($response, "error") === false){
			preg_match('/(?<=openid":").*?(?=")/',$response,$match);
			$openid = $match[0];
			$this->write('openid',$openid);
			return ($openid);
		}else{
			echo $response;
			die();
		}
	}
	public function get_user_info(){
		$url = $this->APIMap['get_user_info'][0].'?openid='.self::$data['openid'].'&access_token='.self::$data['access_token'].'&oauth_consumer_key='.$this->appid.'&format=json';
		$response = $this->cURL($url);
		if(strpos($response, '"ret": 0,') === false){
			echo $response;
			die();
		}else{
			$info = json_decode($response,true);
			return ($info);
		}
		return $response;
	}
	public function write($name,$value){
			self::$data[$name] = $value;
			
	}
	private function cURL($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$return =  curl_exec($ch);
		curl_close($ch);
		return $return;
	}
	private function showError($code){
		switch ($code){
			case '30001':
				$return = 'state验证不通过！谨防CSRF攻击';
				break;
			default:
				$return = '>_<出错了!:（';
		}
		echo $return;die();
	}
	function __destruct(){
		$_SESSION['OAuth_qq_data'] = self::$data;
	}
}
/*
  +----------------------------------------------------------------+
 * APIMap	为扩展保留的信息，最简登录只用到了 $this->APIMap['get_user_info'];
 *	1.登录的url参数：		response_type=code		client_id	redirect_uri	state
 *	2.access的url参数：		grant_type=authorization_code	client_id	client_secret	code	redirect_uri
 *	3.openid的url参数:		access_token
 *	4.user的url参数：		format=json			openid		access_token	oauth_consumer_key	
 *
  +----------------------------------------------------------------+
   返回数据格式
 * code
 *  	
 * access_token
 * 	$response = 'access_token=8F7000D272517A3DD8783481CC317BD7&expires_in=7776000&refresh_token=E4618DF79E84C499C7E3AFFF3AC70F46';
 * 	$response = callback({"error":100021,"error_description":"get access token error"});
 * openid
 * 	$response = callback( {"client_id":"101012291","openid":"E3490D34CAC7706ADB899E3090B33595"} );
 * 	$response = callback( {"error":100016,"error_description":"access token check failed"} );
 * get_user_info
 * 	$response = { "ret": 0, "msg": "", 	"is_lost":0, 
 *		"nickname": "dale", 
 *		"gender": "男", 
 *		"figureurl": "http:\/\/qzapp.qlogo.cn\/qzapp\/101012291\/E3490D34CAC7706ADB899E3090B33595\/30", 
 *		"figureurl_1": "http:\/\/qzapp.qlogo.cn\/qzapp\/101012291\/E3490D34CAC7706ADB899E3090B33595\/50", 
 *		"figureurl_2": "http:\/\/qzapp.qlogo.cn\/qzapp\/101012291\/E3490D34CAC7706ADB899E3090B33595\/100", 
 *		"figureurl_qq_1": "http:\/\/q.qlogo.cn\/qqapp\/101012291\/E3490D34CAC7706ADB899E3090B33595\/40", 
 *		"figureurl_qq_2": "http:\/\/q.qlogo.cn\/qqapp\/101012291\/E3490D34CAC7706ADB899E3090B33595\/100", 
 *		"is_yellow_vip": "0", "vip": "0", "yellow_vip_level": "0", "level": "0", "is_yellow_year_vip": "0" 
 *	}
 * 	$response = {"ret":-22,"msg":"openid is invalid"}
 *
 +----------------------------------------------------------------+
 */

