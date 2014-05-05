<?php
/*
  +----------------------------------------------------------------+
  * 微信公众平台接口 
  * MP($appID,$appsecret)
  +----------------------------------------------------------------+
 *  parameters string $appID		公众号appID
 *  parameters string $appsecret	公众号appsecret
  +----------------------------------------------------------------+
 */
class MP{
	private $appID;
	private $appsecret;
	public  $access_token;
	public  $expires_time;
	public  $groups;
	public  $isGroupsNew;
	const GET_ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&';
	const GET_USER_URL = 'https://api.weixin.qq.com/cgi-bin/user/get?';
	const GET_USER_INFO = 'https://api.weixin.qq.com/cgi-bin/user/info?lang=zh_CN&';
	const GET_GROUP = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=';
	const UPDATE_GROUP = 'https://api.weixin.qq.com/cgi-bin/groups/update?access_token=';
	const CREATE_GROUP = 'https://api.weixin.qq.com/cgi-bin/groups/create?access_token=';
	const GET_USER_GROUP = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=';
	const DELETE_MENU = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=';
	
	
	function __construct($appID,$appsecret){
		//echo __CLASS__;die();
		if(!isset($appID) || !isset($appsecret))
			$this->error('请先配置appID，appsecret');	
		$this->appID = $appID;
		$this->appsecret = $appsecret;
		$this->access_token = $_SESSION['MP_access_token'];
		$this->expires_time = $_SESSION['MP_expires_time'];
		$this->groups = $_SESSION['MP_groups'];
		$this->isGroupsNew = $_SESSION['MP_isGroupsNew'];
		$this->getAccessToken();
	}
	/**
	  * 具有缓存功能的accessToken
	  * @param	boolen	是否强制更新
	  */
	public function getAccessToken($flag=false){
		if($flag){//强制更新 access_token
			$url = self::GET_ACCESS_TOKEN_URL.'appid='.$this->appID.'&secret='.$this->appsecret;
			$return = json_decode($this->cURLGet($url),true);
			if(is_array($return) && $return['errcode']){
				$this->showError($return);
			}else{
				$this->access_token = $return['access_token'];
				$this->expires_time = time()+$return['expires_in'];
				return $return['access_token'];
			}
		}else{
			if(!isset($this->access_token) || $this->expires_time < time()){
				$this->getAccessToken(true);
			}	
		}
	}
	
	/**
	  * 循环获取全部用户openid
	  * @param	string ''	即使超过10000个，也循环获取出来
	  */
	public function getUser($next=''){
		$this->getAccessToken();
		static $arr = array();
		$url = self::GET_USER_URL.'access_token='.$this->access_token.'&next_openid='.$next;
		$return = json_decode($this->cURLGet($url),true);
		if(is_array($return) && $return['errcode']){
			$this->showError($return);
		}else{
			$arr = array_merge($arr,$return['data']['openid']);
			if(10000 == $return['count']){
				$this->getUser($return['next_openid']);
			}else{
				return $arr;
			}
		}
	}
	
	/**
	  * 获取单个用户信息
	  * @param	string url=>openid
	  */
	public function getUserInfo($openid){
		$this->getAccessToken();
		$url = self::GET_USER_INFO.'access_token='.$this->access_token.'&openid='.$openid;
		$return = json_decode($this->cURLGet($url),true);
		if(is_array($return) && $return['errcode']){
			$this->showError($return);
		}else{
			return $return;
		}
	}

	/**
	  * 循环获取用户分组
	  * 考虑使用session
	  */
	public function getUserGroup(){
		$this->getAccessToken();
		$url = self::GET_USER_GROUP.$this->access_token;
		$user = $this->getUser();
		static $arr = array();
		foreach($user as $v){
			$json_param = '{"openid":"'.$v.'"}';
			$return = json_decode($this->cURLPost($url,$json_param),true);
			if(is_array($return) && $return['errcode']){
				$this->showError($return);
			}else{
				$arr[$v] = $return['groupid'];
			}
		}
		return $arr;
	}

	/**
	  * 获取全部分组
	  * @param	string url=>token
	  */
	public function getGroup(){
		if($this->isGroupsNew && $this->groups){
			return $this->groups;
		}
		$this->getAccessToken();
		$url = self::GET_GROUP.$this->access_token;
		$return = json_decode($this->cURLGet($url),true);
		if(is_array($return) && $return['errcode']){
			$this->showError($return);
		}else{
			$this->groups = $return['groups'];
			$this->isGroupsNew = true;
			return $return['groups'];
		}
	}

	/**
	  * 创建分组
	  * @param	string {"group":{"name":"test"}}
	  * @return	string {"group": {"id": 107,  "name": "test" }}
	  */
	public function createGroup($json_param){
		$this->getAccessToken();
		$url = self::CREATE_GROUP.$this->access_token;
		$return = json_decode($this->cURLPost($url,$json_param),true);
		if(is_array($return) && $return['errcode']){
			$this->showError($return);
		}else{
			$this->isGroupsNew = false;//增加group后，标记本地存储的groups不是最新
			return $return['group'];
		}
	}

	/**
	  * 修改分组名称。
	  * @param	string {"group":{"id":108,"name":"test2_modify2"}} json格式字符串
	  * @return	string {"errcode": 0, "errmsg": "ok"}
	  */
	public function updateGroup($json_param){
		$this->getAccessToken();
		$url = self::UPDATE_GROUP.$this->access_token;
		$return = json_decode($this->cURLPost($url,$json_param),true);
		if(is_array($return) && $return['errcode']){
			$this->showError($return);
		}else{
			$this->isGroupsNew = false;
			return $return;
		}
	}
	/**
	  * 删除自定义菜单。（需一般服务号 或者 认证的订阅号）
	  */
	public function deleteMenu(){
		$this->getAccessToken();
		$url = self::DELETE_MENU.$this->access_token;
		$return = json_decode($this->cURLGet($url),true);
		if(is_array($return) && $return['errcode']){
			$this->showError($return);
		}else{
			return $return;
		}
	}

	protected function showError($return){
//		//待人性化，当前直接返回 元素的错误信息。
		dump($return);
		die();
	}
	protected function cURLGet($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result =  curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	protected function cURLPost($url,$parameter,$header=array()){
		$curlhandle = curl_init();
		curl_setopt($curlhandle, CURLOPT_URL, $url);
		curl_setopt($curlhandle, CURLOPT_HTTPHEADER, $header); //设置HTTP头字段的数组
		curl_setopt($curlhandle, CURLOPT_SSL_VERIFYPEER, 0); //对认证证书来源的检查
		curl_setopt($curlhandle, CURLOPT_SSL_VERIFYHOST, 1); //从证书中检查SSL加密算法是否存在
		curl_setopt($curlhandle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0'); 
		curl_setopt($curlhandle, CURLOPT_FOLLOWLOCATION, 0); //使用自动跳转
		curl_setopt($curlhandle, CURLOPT_AUTOREFERER, 0); //自动设置Referer
		curl_setopt($curlhandle, CURLOPT_POST, 1); //发送一个常规的Post请求
		curl_setopt($curlhandle, CURLOPT_POSTFIELDS, $parameter);//微信接口要就json数据
		curl_setopt($curlhandle, CURLOPT_COOKIE, ''); //读取储存的Cookie信息
		curl_setopt($curlhandle, CURLOPT_TIMEOUT, 30); //设置超时限制防止死循环
		curl_setopt($curlhandle, CURLOPT_HEADER, 0); //显示返回的Header区域内容
		curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 1); //获取的信息以文件流的形式返回
		$result = curl_exec($curlhandle);
		curl_close($curlhandle);
		return $result;
	}
//	public function checkAccessToken(){
//		if(!isset($this->access_token) || $this->expires_time < time()){
//			$this->getAccessToken();
//		}
//	}
	function __destruct(){
		$_SESSION['MP_access_token'] = $this->access_token;
		$_SESSION['MP_expires_time'] = $this->expires_time;
		if($this->isGroupsNew && $this->groups){
			$_SESSION['MP_groups'] = $this->groups;
			$_SESSION['MP_isGroupsNew'] = $this->isGroupsNew;
		}
	}
}
