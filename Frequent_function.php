<?php
// +-----------------------------------------
// | Common
// +-----------------------------------------
// | Frequent functions in my CODE career
// +-----------------------------------------
// | Author: Everyone  you & me(Dale)
// +-----------------------------------------


/**
  +---------------------------------------------------------------+
 * 1. 过滤输入
 * 
 * htmlspecialchars(trim($input));
 * 该函数是php系统函数：
 * trim():		     去除"\0" - NULL;"\t" - tab;"\n" - new line;"\x0B" - 纵向列表符;"\r" - 回车;" " - 普通空白字符
 * htmlspecialchars(): 字符转实体 和号&-->&amp;双引号"-->&quot;单引号'-->&#039;小于<-->&lt;大于>-->&gt;
 * strip_tags():       去除HTML、XML 以及 PHP 的标签.
  +---------------------------------------------------------------+	
 * @param string   $input   用户输入
  +---------------------------------------------------------------+	
  | @return string
  +---------------------------------------------------------------+
 */
	strip_tags($input);
	htmlspecialchars();
	trim();
 
/**
  +---------------------------------------------------------------+
  * 普及常识
  * & 函数引用（一般用于递归）
  * @ 错误控制运算符（尽量不用）
  * file_get_contents(img_path);读得二进制数据
  * :%s/\(\w\+\), \(\w\+\)/\2 \1/   将 Doe, John 修改为 John Doe
  * 替换部分匹配换行的是 \r 不是 \n，和查找不一样
  +---------------------------------------------------------------+
 */
/**
  +---------------------------------------------------------------+
 * 2. 判断是否工作日
 * is_workday(1384666888,3,array("2012-11-20"))
  +---------------------------------------------------------------+	
 *  parameters 
 * @param integer $theTime 时间戳
 * @param integer $mode  模式0 =不考虑周末,假日;模式1 =排除星期天;模式2 =排除星期六,星期天;模式3 =排除星期六,星期天,假日 
 * @param array   $holiday   自定义的节日数组
  +---------------------------------------------------------------+	
 * @return boolen 
  +---------------------------------------------------------------+
*/
function frequent_is_workday ($theTime,$mode=0,$holiday){
	$return = true;
	switch ($mode){
	case 1:
		if (0==idate(w,$theTime))$return=false;
		break;
	case 2:
		if (0==idate(w,$theTime) || 6==idate(w,$theTime))$return=false;
		break;
	case 3:
		$date = date("Y-m-d",$theTime); //获得日期,日期的格式需结合$holiday数组的要求
		if(0==idate(w,$theTime) || 6==idate(w,$theTime) || in_array($date,$holiday))$return=false;
		break;
	default:
	}
	return $return;
}

/**
  +---------------------------------------------------------------+
 * 3. 计算结束时间
 * frequent_endTime(1384666888,3,0)
  +---------------------------------------------------------------+
 *  parameters 
 * @param integer $startTime 时间戳
 * @param integer $during持续天数
 * @param integer $mode  模式0 =不考虑周末,假日;模式1 =排除星期天;模式2 =排除星期六,星期天;模式3 =排除星期六,星期天,假日 
 * @param array   $holiday   自定义的节日数组
  +---------------------------------------------------------------+	
 * @return inerger 时间戳
  +---------------------------------------------------------------+
  |设置时区
  |ini_set('date.timezone','Asia/Shanghai');
  |date_default_timezone_set('Asia/Shanghai'); 
  |date_default_timezone_set("Etc/GMT+8");//这里比林威治标准时间慢8小时 
  |date_default_timezone_set('PRC'); //设置中国时区
  +---------------------------------------------------------------+
*/
function frequent_endTime($startTime,$during,$mode=0,$holidy){
	function new_startTime($t,$a,$b){
		if(!frequent_is_workday($t,$a,$b)&&frequent_is_workday($t+86400,$a,$b)){//今天是假日+明天是工作日
			$arr = getdate($t+86400);
			//mktime(hour,minute,second,month,day,year)
			$t = mktime(9,0,0,$arr['mon'],$arr['mday'],$arr['year']);//设定从工作日的几点开始计算
		}elseif(!frequent_is_workday($t,$a,$b)&&!frequent_is_workday($t+86400,$a,$b)){//今天是假日+明天是假日
			$t += 86400;
			$t = new_startTime($t,$a,$b);
		}else{
		}
		return $t;
	}
	$startTime = new_startTime($startTime,$mode,$holiday); //通过new_startTime传递$mode,$holiday 给 frequent_is_workday
	for($i=1;$i<=$during;$i++){
		$t = $startTime+86400*$i;
		if(!frequent_is_workday($t,$mode,$holiday)){
			$during++;
		}
	}
	$endTime = $startTime+$during*86400;
	return $endTime;
}

/**
  +---------------------------------------------------------------+
 * 4. 无限分类
 * 应用场景：
 * 数据库里以id pid形式存储的类目信息，转树状无限分类
 * frequent_infinite_category($arr,$pid=0)
  +---------------------------------------------------------------+
 *  parameters 
 * @param  array   $arr  一维数组
 * @param  integer $pid  父级id
  +---------------------------------------------------------------+
  | @return array  $tree
  +---------------------------------------------------------------+
*/
function frequent_infinite_category($arr, $pid = 0) {
	$tree = array();
	foreach ($arr as $k => $v) {
		if ($v['pid'] == $pid) {
	$tree[] = $v;
		}
	}
	if (empty($tree))return null;
	foreach ($tree as $k => $v) {
		if(frequent_infinite_category($arr, $v['id'])){
		//存在子类才添加
			$tree[$k]['son'] = frequent_infinite_category($arr, $v['id']);
		}
	}
	return $tree;
}

/**
  +---------------------------------------------------------------+
 * 5. 多维数组转1维
 * 应用场景：一般在数组里面的值想转成 字符串时使用
 * frequent_multi_2_uni($arr)
G  +---------------------------------------------------------------+
 * @param  array  $arr  多维数组
  +---------------------------------------------------------------+
  | @return array  $arr
  | header("Content-type: text/html; charset=utf-8");
 +---------------------------------------------------------------+
*/ 
function multidimensional_2_unidimensional($array) {
	static $return = array();
	foreach ($array as $key=>$value){
		if (is_array($value)){
			multidimensional_2_unidimensional($value); 	
		}else{
		$return[] = $value; // 不保留数组的key ，所有值都不会丢失
		// $return[$key] = $value;保留Key，但是相同key的会被覆盖	
		}
	}
	return $return;
}

/**
  +---------------------------------------------------------------+
 * 6. php unicode编码
 * frequent_unicode_encode($text)
  +---------------------------------------------------------------+
 * @param  string  $text
  +---------------------------------------------------------------+
  | @return string
  +---------------------------------------------------------------+
*/
function frequent_unicode_encode($text){
	$text = iconv('UTF-8', 'UCS-2', $text);
	//UCS2是Unicode的一种,UCS2码中每个字符都占两个字节
	//iconv("UTF-8","GB2312//IGNORE",$data);顺便说一下，utf-8转gb2312时,常常需要忽略错误
	//待深入 
	$len = strlen($text);
	$str = '';
	for ($i=0;$i<$len-1;$i=$i+2){
		$c = $text[$i];
		$c2 = $text[$i + 1];
		if (ord($c) > 0){
			//两个字节的文字  ord()函数返回字符串第一个字符的ASCII值。以此判断是否2个字节的文字
			//再用base_convert转成16进制
			//str_pad(string,length,pad_string,pad_type)不够2数的，字符填充0
			$str .= '\u'.base_convert(ord($c), 10, 16).str_pad(base_convert(ord($c2), 10, 16), 2, 0, STR_PAD_LEFT);
		}else{
			$str .= $c2;
		}
	}
	return $str;
}
/**
  +---------------------------------------------------------------+
 * 7. php unicode解码
 * frequent_unicode_decode($code)
  +---------------------------------------------------------------+
 * @param  string  $code
  +---------------------------------------------------------------+
  | @return string
  +---------------------------------------------------------------+
*/
//将UNICODE编码后的内容进行解码
function frequent_unicode_decode($code){
	//转换编码，将Unicode编码转换成可以浏览的utf-8编码
	$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
	preg_match_all($pattern, $code, $matches);
	if (!empty($matches)){
		$text = '';
		for ($j = 0; $j < count($matches[0]); $j++){
			$str = $matches[0][$j];
			if (strpos($str, '\\u') === 0){
				$code = base_convert(substr($str, 2, 2), 16, 10);
				$code2 = base_convert(substr($str, 4), 16, 10);
				$c = chr($code).chr($code2);
				$c = iconv('UCS-2', 'UTF-8', $c);
				$text .= $c;
			}else{
				$text .= $str;
			}
		}
	}
	return $text;
}

/**
  +---------------------------------------------------------------+
 * 8. 自动生成略缩图
 * frequent_thumb($url,$width,$height,$autoCrop=0,$nopic,$folder)
  +---------------------------------------------------------------+
 * @param string  $url
 * @param integer $width
 * @param integer $height
 * @param boolen  $autoCrop 
 * @param string  $url
 * @param string  $folder
  +---------------------------------------------------------------+
  | @return string $thumb
  +---------------------------------------------------------------+
 */
function frequent_thumb($url,$width,$height,$autoCrop=0,$nopic,$folder){
	$allowType = array(1=>'gif',2=>'jpeg',3=>'png');
		//这里的索引需要和$type的索引一致。
	if(empty($url)) return $nopic;
	list($full_width,$full_height,$type,$attr)=getimagesize($url);
	if (!$allowType[$type]){
	       	return false;
	}else{
		$type = $allowType[$type];//索引转文字
	}
	 //list($width, $height, $type, $attr) = getimagesize("img/flag.jpg");
	 // getimagesize()返回一个具有四个单元的数组。索引 0 包含图像宽度的像素值，索引 1 包含图像高度的像素值。索引 2 是图像类型的标记：1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM。这些标记与 PHP 4.3.0 新加的 IMAGETYPE 常量对应。索引 3 是文本字符串，内容为“height="yyy" width="xxx"”，可直接用于 IMG 标记。
	 // list()函数用数组中的元素为一组变量赋值。
	if ($full_width>$width && $full_height>$height){
		if(!$folder) $folder='Public/thumb/';
	/* 略缩图的位置和原图同一文件夹	
		if(!$folder) {
			$pathinfo = pathinfo($url);
			//pathinfo('/home/ramki.pdf');
			//Array([dirname] => /home/ramki	[basename] => ramki.pdf		[extension] => pdf	[filename] => ramki)
			$folder= $pathinfo['dirname'];
		}
	 */
		$pathinfo = pathinfo($url);
		$thumb = $folder.'thumb_'.$width.'_'.$height.'_'.$pathinfo['basename'];//取得原图的文件类型
		if(is_file($thumb)){
			return $thumb;
		}
	}else{
		return $url;
	}
	if($autoCrop){
		if($full_width/$width > $full_height/$height){
			$full_width =$width * ($full_height/$height);
		}else{
			$full_height=$height * ($full_width/$width);
		}
	}else{
		$scale = min($width/$full_width,$height/$full_height);
		if($scale>=1){
			$width=$full_width;
			$height=$full_height;
		}else{
			$width = (int)($full_width * $scale);
			$height= (int)($full_height * $scale);
		}
	}

	$I_function = 'imagecreatefrom'.$type;
	$O_function = 'image'.$type;
	$full_image = $I_function($url);
	if($type != 'gif' && function_exists('imagecreatetruecolor')){
		//优先选择imagecreatetruecolor
		$thumb_image = imagecreatetruecolor($width, $height);
	}else{
		$thumb_image = imagecreate($width, $height);
	}
	if(function_exists('imagecopyresampled')){
		//优先选择imagecopyresampled
		//bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		imagecopyresampled($thumb_image, $full_image, 0,0, 0,0, $width,$height, $full_width,$full_height);
	}else{
		imagecopyresized($thumb_image, $full_image, 0,0, 0,0,$width,$height, $full_width,$full_height);
	}
	if($type=='gif' || $type=='png') {
		$background_color  =  imagecolorallocate($thumb_image,  255, 255, 255);  //  指派一个白色
		imagecolortransparent($thumb_image, $background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
	}
	$O_function($thumb_image, $thumb);
	imagedestroy($thumb_image);  //图片流一般比较大，建议随手删除
	imagedestroy($full_image);
	
	return $thumb;
}

/**
  +---------------------------------------------------------------+
 * 9. 仿javaScript alert()
 * frequent_alert($msg,$url))
  +---------------------------------------------------------------+	
 *  parameters 
 * @param string $msg 
 * @param string $url 
  +---------------------------------------------------------------+	
 * @return string  
  +---------------------------------------------------------------+
 */
function frequent_alert($msg,$url){
	$str = '<script type="text/javascript">';
	$str.= "alert('{$msg}');";
	if(filter_var($url, FILTER_VALIDATE_URL)){
		$str.="window.location.href='{$url}'";
		//".$url." 也可 ； {$val}表示告诉php阔起来的，要当变量处理	
	}else {
		$str.="window.history.back()";
	}
	$str.= "</script>";
	echo $str;
}

/**
  +---------------------------------------------------------------+
 * 10. 等价PHP5版本的array_combine函数
 * $arr = (PHP_VERSION>='5.0') ? array_combine($arr1,$arr2):frequent_arr_combine($arr1,$arr2);
 * 如果其中一个数组为空，或者两个数组的元素个数不同，则该函数返回 false。
  +---------------------------------------------------------------+
 */
function frequent_arr_combine($arr1, $arr2) {
	if (0==count($arr1) || 0==count($arr2) || count($arr1)==count($arr2))
	return false;//使用return,程序就只到这来返回了
	for($i=0; $i<count($arr1); $i++){
		$return[$arr1[$i]] = $arr2[$i];
	}
	return $return;
}

/**
  +---------------------------------------------------------------+
 * 11. 字符与16进制互转
  +---------------------------------------------------------------+
 */
function strToHex($string){   
	$hex="";   
	for($i=0;$i<strlen($string);$i++){   
		$hex.=dechex(ord($string[$i]));
		//dechex() 函数把十进制转换为十六进制。 所能转换的最大数值为十进制的 4294967295
		//bindec()2->10		octdec()8->10		base_convert()任意进制
		$hex=strtoupper($hex);
	}	
	return   $hex;   
}   
function hexToStr($hex){   
	$string="";   
	for($i=0;$i<strlen($hex)-1;$i+=2){ 
		$string.=chr(hexdec($hex[$i].$hex[$i+1]));
	}	
	return   $string;   
}

/**
  +---------------------------------------------------------------+
 * 12. 获取客户端 ip
 * frequent_getIP()
 * 如果代理服务器供出原ip,可以获取到真实ip.
  +---------------------------------------------------------------+
 * @return string	(8.35.201.50)
  +---------------------------------------------------------------+
 */
function frequent_getIP(){
	static $realip = NULL;
	if ($realip !== NULL)	return $realip;
	if (isset($_SERVER)){
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			foreach ($arr AS $ip){
				$ip = trim($ip);
				if ($ip != 'unknown'){
					$realip = $ip;
					break;
				}
			}
		}elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		}else{
			if (isset($_SERVER['REMOTE_ADDR'])){
				$realip = $_SERVER['REMOTE_ADDR'];
			}else{
				$realip = '0.0.0.0';
			}
		}
	}else{
		if (getenv('HTTP_X_FORWARDED_FOR')){
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		}elseif (getenv('HTTP_CLIENT_IP')){
			$realip = getenv('HTTP_CLIENT_IP');
		}else{
			$realip = getenv('REMOTE_ADDR');
		}
	}
	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
	return $realip;
}

/**
  +---------------------------------------------------------------+
 * 13. ip转化为地址。
 * frequent_ip_2_addr($ip)
 * 切记配置数据文件
  +---------------------------------------------------------------+
 * @param string $ip	 frequent_getIP()返回的ip
  +---------------------------------------------------------------+
 * @return string	(广东省广州市 电信)
  +---------------------------------------------------------------+
 */
function frequent_ip_2_addr($ip) {
	//IP数据文件路径;数据文件一定要定义路径+文件名
	$dat_path = 'QQWry.Dat';
	//检查IP地址
	if(!preg_match("/^\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}$/", $ip)) {
		return 'IP Address Error';
	}
	//打开IP数据文件
	if(!$fd = @fopen($dat_path, 'rb')){
		return 'IP date file not exists or access denied';
	}
	//分解IP进行运算，得出整形数
	$ip = explode('.', $ip);
	$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
	//读取头文件获取IP数据索引开始和结束位置即第一条和最后一条的绝对偏移位置
	$DataBegin = fread($fd, 4);
	$DataEnd = fread($fd, 4);
	$ipbegin = implode('', unpack('L', $DataBegin));
	if($ipbegin < 0) $ipbegin += pow(2, 32);
	$ipend = implode('', unpack('L', $DataEnd));
	if($ipend < 0) $ipend += pow(2, 32);
	$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
	$BeginNum = 0;
	$EndNum = $ipAllNum;
	//使用二分查找法从索引记录中搜索匹配的IP记录
	while($ip1num>$ipNum || $ip2num<$ipNum) {
		$Middle= intval(($EndNum + $BeginNum) / 2);
		//偏移指针到索引位置读取4个字节
		fseek($fd, $ipbegin + 7 * $Middle);
		$ipData1 = fread($fd, 4);
		if(strlen($ipData1) < 4) {
			fclose($fd);
			return 'System Error';
		}
		//提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
		$ip1num = implode('', unpack('L', $ipData1));
		if($ip1num < 0) $ip1num += pow(2, 32);
		//提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
		if($ip1num > $ipNum) {
			$EndNum = $Middle;
			continue;
		}
		//取完上一个索引后取下一个索引
		$DataSeek = fread($fd, 3);
		if(strlen($DataSeek) < 3) {
			fclose($fd);
			return 'System Error';
		}
		$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
		fseek($fd, $DataSeek);
		$ipData2 = fread($fd, 4);
		if(strlen($ipData2) < 4) {
			fclose($fd);
			return 'System Error';
		}
		$ip2num = implode('', unpack('L', $ipData2));
		if($ip2num < 0) $ip2num += pow(2, 32);
		//没找到提示未知
		if($ip2num < $ipNum) {
			if($Middle == $BeginNum) {
				fclose($fd);
				return 'Unknown';
			}
			$BeginNum = $Middle;
		}
	}
	//下面的代码读晕了，没读明白，有兴趣的慢慢读
	$ipFlag = fread($fd, 1);
	if($ipFlag == chr(1)) {
		$ipSeek = fread($fd, 3);
		if(strlen($ipSeek) < 3) {
			fclose($fd);
			return 'System Error';
		}
		$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
		fseek($fd, $ipSeek);
		$ipFlag = fread($fd, 1);
	}
	if($ipFlag == chr(2)) {
		$AddrSeek = fread($fd, 3);//3个字节表示国家/地区的实际偏移位置
		if(strlen($AddrSeek) < 3) {
			fclose($fd);
			return 'System Error';
		}
		$ipFlag = fread($fd, 1);//一个字节表示重定向模式
		if($ipFlag == chr(2)) {
			$AddrSeek2 = fread($fd, 3);
			if(strlen($AddrSeek2) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
			fseek($fd, $AddrSeek2);
		} else {
			fseek($fd, -1, SEEK_CUR);
		}
		while(($char = fread($fd, 1)) != chr(0))
			$ipAddr2 .= $char;
		$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
		fseek($fd, $AddrSeek);
		while(($char = fread($fd, 1)) != chr(0))
			$ipAddr1 .= $char;
	} else {
		fseek($fd, -1, SEEK_CUR);
		while(($char = fread($fd, 1)) != chr(0))
			$ipAddr1 .= $char;
		$ipFlag = fread($fd, 1);
		if($ipFlag == chr(2)) {
			$AddrSeek2 = fread($fd, 3);
			if(strlen($AddrSeek2) < 3) {
				fclose($fd);
				return 'System Error';
			}
			$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
			fseek($fd, $AddrSeek2);
		} else {
			fseek($fd, -1, SEEK_CUR);
		}
		while(($char = fread($fd, 1)) != chr(0)){
			$ipAddr2 .= $char;
		}
	}
	fclose($fd);
	//最后做相应的替换操作后返回结果
	if(preg_match('/http/i', $ipAddr2)) {
		$ipAddr2 = '';
	}
	$ipaddr = "$ipAddr1 $ipAddr2";
	$ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
	$ipaddr = preg_replace('/^s*/is', '', $ipaddr);
	$ipaddr = preg_replace('/s*$/is', '', $ipaddr);
	if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
		$ipaddr = 'Unknown';
	}
	return $ipaddr;
}


/**
  +---------------------------------------------------------------+
  | &0x80
  | 位与运算
  | 就知道是不是标准ASCII码了，ASCII码都是小于0x80的，
  +---------------------------------------------------------------+
  * for(i=0;i<strlen(str);i++) { 
  * 	if( str[i] & 0X80 ) i++; 
  * 	else count++; 
  * }
  * 这段程序的count 就是str中标准ASCII的个数。
  * 位与(相同的位都是1的才为1，否则为0): 
  +---------------------------------------------------------------+
 */
/**
  +---------------------------------------------------------------+
 * 14. php汉字转拼音  --待整理
 * is_workday(1384666888,3,array("2012-11-20"))
  +---------------------------------------------------------------+	
 *  parameters 
 * @param integer $theTime 时间戳
 * @param integer $mode  模式0 =不考虑周末,假日;模式1 =排除星期天;模式2 =排除星期六,星期天;模式3 =排除星期六,星期天,假日 
 * @param array   $holiday   自定义的节日数组
  +---------------------------------------------------------------+	
 * @return boolen 
  +---------------------------------------------------------------+
 */
/**	
	$_DataKey ="a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
	        "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
	        "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
	        "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
	        "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
	        "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
	        "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
	        "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
	        "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
	        "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
	        "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
	        "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
	        "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
	        "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
	        "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
	        "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";

	$_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
	        "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
	        "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
	        "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
	        "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
	        "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
	        "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
	        "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
	        "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
	        "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
	        "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
	        "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
	        "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
	        "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
	        "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
	        "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
	        "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
	        "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
	        "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
	        "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
	        "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
	        "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
	        "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
	        "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
	        "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
	        "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
	        "|-10270|-10262|-10260|-10256|-10254";
	$_TDataKey = explode('|', $_DataKey);
	$_TDataValue = explode('|', $_DataValue);
	$_Data = (PHP_VERSION>='5.0') ? array_combine($_TDataKey, $_TDataValue) : _Array_Combine($_TDataKey, $_TDataValue);
	arsort($_Data);	//arsort:对数组进行逆向排序并保持索引关系
	reset($_Data);	//reset:将数组的内部指针指向第一个单元
*/
function Pinyin($str, $code='gb2312') {
	$pinyin_library = array('a'=>'-20319','ai'=>'-20317','an'=>'-20304','ang'=>'-20295','ao'=>'-20292','ba'=>'-20283','bai'=>'-20265','ban'=>'-20257','bang'=>'-20242','bao'=>'-20230','bei'=>'-20051','ben'=>'-20036','beng'=>'-20032','bi'=>'-20026','bian'=>'-20002','biao'=>'-19990','bie'=>'-19986','bin'=>'-19982','bing'=>'-19976','bo'=>'-19805','bu'=>'-19784','ca'=>'-19775','cai'=>'-19774','can'=>'-19763','cang'=>'-19756','cao'=>'-19751','ce'=>'-19746','ceng'=>'-19741','cha'=>'-19739','chai'=>'-19728','chan'=>'-19725','chang'=>'-19715','chao'=>'-19540','che'=>'-19531','chen'=>'-19525','cheng'=>'-19515','chi'=>'-19500','chong'=>'-19484','chou'=>'-19479','chu'=>'-19467','chuai'=>'-19289','chuan'=>'-19288','chuang'=>'-19281','chui'=>'-19275','chun'=>'-19270','chuo'=>'-19263','ci'=>'-19261','cong'=>'-19249','cou'=>'-19243','cu'=>'-19242','cuan'=>'-19238','cui'=>'-19235','cun'=>'-19227','cuo'=>'-19224','da'=>'-19218','dai'=>'-19212','dan'=>'-19038','dang'=>'-19023','dao'=>'-19018','de'=>'-19006','deng'=>'-19003','di'=>'-18996','dian'=>'-18977','diao'=>'-18961','die'=>'-18952','ding'=>'-18783','diu'=>'-18774','dong'=>'-18773','dou'=>'-18763','du'=>'-18756','duan'=>'-18741','dui'=>'-18735','dun'=>'-18731','duo'=>'-18722','e'=>'-18710','en'=>'-18697','er'=>'-18696','fa'=>'-18526','fan'=>'-18518','fang'=>'-18501','fei'=>'-18490','fen'=>'-18478','feng'=>'-18463','fo'=>'-18448','fou'=>'-18447','fu'=>'-18446','ga'=>'-18239','gai'=>'-18237','gan'=>'-18231','gang'=>'-18220','gao'=>'-18211','ge'=>'-18201','gei'=>'-18184','gen'=>'-18183','geng'=>'-18181','gong'=>'-18012','gou'=>'-17997','gu'=>'-17988','gua'=>'-17970','guai'=>'-17964','guan'=>'-17961','guang'=>'-17950','gui'=>'-17947','gun'=>'-17931','guo'=>'-17928','ha'=>'-17922','hai'=>'-17759','han'=>'-17752','hang'=>'-17733','hao'=>'-17730','he'=>'-17721','hei'=>'-17703','hen'=>'-17701','heng'=>'-17697','hong'=>'-17692','hou'=>'-17683','hu'=>'-17676','hua'=>'-17496','huai'=>'-17487','huan'=>'-17482','huang'=>'-17468','hui'=>'-17454','hun'=>'-17433','huo'=>'-17427','ji'=>'-17417','jia'=>'-17202','jian'=>'-17185','jiang'=>'-16983','jiao'=>'-16970','jie'=>'-16942','jin'=>'-16915','jing'=>'-16733','jiong'=>'-16708','jiu'=>'-16706','ju'=>'-16689','juan'=>'-16664','jue'=>'-16657','jun'=>'-16647','ka'=>'-16474','kai'=>'-16470','kan'=>'-16465','kang'=>'-16459','kao'=>'-16452','ke'=>'-16448','ken'=>'-16433','keng'=>'-16429','kong'=>'-16427','kou'=>'-16423','ku'=>'-16419','kua'=>'-16412','kuai'=>'-16407','kuan'=>'-16403','kuang'=>'-16401','kui'=>'-16393','kun'=>'-16220','kuo'=>'-16216','la'=>'-16212','lai'=>'-16205','lan'=>'-16202','lang'=>'-16187','lao'=>'-16180','le'=>'-16171','lei'=>'-16169','leng'=>'-16158','li'=>'-16155','lia'=>'-15959','lian'=>'-15958','liang'=>'-15944','liao'=>'-15933','lie'=>'-15920','lin'=>'-15915','ling'=>'-15903','liu'=>'-15889','long'=>'-15878','lou'=>'-15707','lu'=>'-15701','lv'=>'-15681','luan'=>'-15667','lue'=>'-15661','lun'=>'-15659','luo'=>'-15652',
'ma'=>'-15640','mai'=>'-15631','man'=>'-15625','mang'=>'-15454','mao'=>'-15448','me'=>'-15436','mei'=>'-15435','men'=>'-15419','meng'=>'-15416','mi'=>'-15408','mian'=>'-15394','miao'=>'-15385','mie'=>'-15377','min'=>'-15375','ming'=>'-15369','miu'=>'-15363','mo'=>'-15362','mou'=>'-15183','mu'=>'-15180','na'=>'-15165','nai'=>'-15158','nan'=>'-15153','nang'=>'-15150','nao'=>'-15149','ne'=>'-15144','nei'=>'-15143','nen'=>'-15141','neng'=>'-15140','ni'=>'-15139','nian'=>'-15128','niang'=>'-15121','niao'=>'-15119','nie'=>'-15117','nin'=>'-15110','ning'=>'-15109','niu'=>'-14941','nong'=>'-14937','nu'=>'-14933','nv'=>'-14930','nuan'=>'-14929','nue'=>'-14928','nuo'=>'-14926','o'=>'-14922','ou'=>'-14921','pa'=>'-14914','pai'=>'-14908','pan'=>'-14902','pang'=>'-14894','pao'=>'-14889','pei'=>'-14882','pen'=>'-14873','peng'=>'-14871','pi'=>'-14857','pian'=>'-14678','piao'=>'-14674','pie'=>'-14670','pin'=>'-14668','ping'=>'-14663','po'=>'-14654','pu'=>'-14645','qi'=>'-14630','qia'=>'-14594','qian'=>'-14429','qiang'=>'-14407','qiao'=>'-14399','qie'=>'-14384','qin'=>'-14379','qing'=>'-14368','qiong'=>'-14355','qiu'=>'-14353','qu'=>'-14345','quan'=>'-14170','que'=>'-14159','qun'=>'-14151','ran'=>'-14149','rang'=>'-14145','rao'=>'-14140','re'=>'-14137','ren'=>'-14135','reng'=>'-14125','ri'=>'-14123','rong'=>'-14122','rou'=>'-14112','ru'=>'-14109','ruan'=>'-14099','rui'=>'-14097','run'=>'-14094','ruo'=>'-14092','sa'=>'-14090','sai'=>'-14087','san'=>'-14083','sang'=>'-13917','sao'=>'-13914','se'=>'-13910','sen'=>'-13907','seng'=>'-13906','sha'=>'-13905','shai'=>'-13896','shan'=>'-13894','shang'=>'-13878','shao'=>'-13870','she'=>'-13859','shen'=>'-13847','sheng'=>'-13831','shi'=>'-13658','shou'=>'-13611','shu'=>'-13601','shua'=>'-13406','shuai'=>'-13404','shuan'=>'-13400','shuang'=>'-13398','shui'=>'-13395','shun'=>'-13391','shuo'=>'-13387','si'=>'-13383','song'=>'-13367','sou'=>'-13359','su'=>'-13356','suan'=>'-13343','sui'=>'-13340','sun'=>'-13329','suo'=>'-13326','ta'=>'-13318','tai'=>'-13147','tan'=>'-13138','tang'=>'-13120','tao'=>'-13107','te'=>'-13096','teng'=>'-13095','ti'=>'-13091','tian'=>'-13076','tiao'=>'-13068','tie'=>'-13063','ting'=>'-13060','tong'=>'-12888','tou'=>'-12875','tu'=>'-12871','tuan'=>'-12860','tui'=>'-12858','tun'=>'-12852','tuo'=>'-12849','wa'=>'-12838','wai'=>'-12831','wan'=>'-12829','wang'=>'-12812','wei'=>'-12802','wen'=>'-12607','weng'=>'-12597','wo'=>'-12594','wu'=>'-12585','xi'=>'-12556','xia'=>'-12359','xian'=>'-12346','xiang'=>'-12320','xiao'=>'-12300','xie'=>'-12120','xin'=>'-12099','xing'=>'-12089','xiong'=>'-12074','xiu'=>'-12067','xu'=>'-12058','xuan'=>'-12039','xue'=>'-11867','xun'=>'-11861','ya'=>'-11847','yan'=>'-11831','yang'=>'-11798','yao'=>'-11781','ye'=>'-11604','yi'=>'-11589','yin'=>'-11536','ying'=>'-11358','yo'=>'-11340','yong'=>'-11339','you'=>'-11324','yu'=>'-11303','yuan'=>'-11097','yue'=>'-11077','yun'=>'-11067',
'za'=>'-11055','zai'=>'-11052','zan'=>'-11045','zang'=>'-11041','zao'=>'-11038','ze'=>'-11024','zei'=>'-11020','zen'=>'-11019','zeng'=>'-11018','zha'=>'-11014','zhai'=>'-10838','zhan'=>'-10832','zhang'=>'-10815','zhao'=>'-10800','zhe'=>'-10790','zhen'=>'-10780','zheng'=>'-10764','zhi'=>'-10587','zhong'=>'-10544','zhou'=>'-10533','zhu'=>'-10519','zhua'=>'-10331','zhuai'=>'-10329','zhuan'=>'-10328','zhuang'=>'-10322','zhui'=>'-10315','zhun'=>'-10309','zhuo'=>'-10307','zi'=>'-10296','zong'=>'-10281','zou'=>'-10274','zu'=>'-10270','zuan'=>'-10262','zui'=>'-10260','zun'=>'-10256','zuo'=>'-10254');
	arsort($pinyin_library);
	//内部函数，数字转拼音
	function number_pinyin($number, $pinyin_library) {
		if($number>0 && $number<160 )	return chr($number);
		elseif($number<-20319 || $number>-10247)	return '';
		else {
			foreach($pinyin_library as $k=>$v) {
				if($v<=$number)	break;
			}
			return $k;
		}
	}
	function utf8_Gb($str) {
		$return = '';
		if($str < 0x80) {
			$return .= $str;
		}elseif($str < 0x800) {
			$return .= chr(0xC0 | $str>>6);
			$return .= chr(0x80 | $str & 0x3F);
		}elseif($str < 0x10000) {
			$return .= chr(0xE0 | $str>>12);
			$return .= chr(0x80 | $str>>6 & 0x3F);
			$return .= chr(0x80 | $str & 0x3F);
		}elseif($str < 0x200000) {
			$return .= chr(0xF0 | $str>>18);
			$return .= chr(0x80 | $str>>12 & 0x3F);
			$return .= chr(0x80 | $str>>6 & 0x3F);
			$return .= chr(0x80 | $str & 0x3F);
		}
		return @iconv('UTF-8','GB2312//IGNORE',$return);
	}
		//假如编码不是gb2312,则启用utf-8
	if($code != 'gb2312') $str = utf8_Gb($str);
	$return = '';
	for($i=0; $i<strlen($str); $i++) {
		$_P = ord(substr($str, $i, 1));
	if($_P>160) {
			$_Q = ord(substr($str, ++$i, 1));
			$_P = $_P*256 + $_Q - 65536;
		}
		$return .= number_pinyin($_P, $pinyin_library);
	}
	return preg_replace("/[^a-z0-9]*/", '', $return);
}

/**
  +---------------------------------------------------------------+
 * 15. 压缩文件
 * frequent_zip($path,$savedir)
  +---------------------------------------------------------------+
 * @param string $path		需要压缩的文件[夹]路径
 * @param string $savedir	压缩文件所保存的目录
  +---------------------------------------------------------------+
 * @return array		zip文件路径
  +---------------------------------------------------------------+
 */
function frequent_zip($path,$savedir) {
    $path=preg_replace('/\/$/', '', $path);
    preg_match('/\/([\d\D][^\/]*)$/', $path, $matches, PREG_OFFSET_CAPTURE);
    $filename=$matches[1][0].".zip";
    set_time_limit(0);
    $zip = new ZipArchive();
    $zip->open($savedir.'/'.$filename,ZIPARCHIVE::OVERWRITE);
    if (is_file($path)) {
        $path=preg_replace('/\/\//', '/', $path);
        $base_dir=preg_replace('/\/[\d\D][^\/]*$/', '/', $path);
        $base_dir=addcslashes($base_dir, '/:');
        $localname=preg_replace('/'.$base_dir.'/', '', $path);
        $zip->addFile($path,$localname);
        $zip->close();
        return $filename;
    }elseif (is_dir($path)) {
        $path=preg_replace('/\/[\d\D][^\/]*$/', '', $path);
        $base_dir=$path.'/';//基目录
        $base_dir=addcslashes($base_dir, '/:');
    }
    $path=preg_replace('/\/\//', '/', $path);
    function addItem($path,&$zip,&$base_dir){
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if (($file!='.')&&($file!='..')){
                $ipath=$path.'/'.$file;
                if (is_file($ipath)){//条目是文件
                    $localname=preg_replace('/'.$base_dir.'/', '', $ipath);
                    var_dump($localname);
                    $zip->addFile($ipath,$localname);
                } else if (is_dir($ipath)){
                    addItem($ipath,$zip,$base_dir);
                    $localname=preg_replace('/'.$base_dir.'/', '', $ipath);
                    var_dump($localname);
                    $zip->addEmptyDir($localname);
                }
            }
        }
    }
    addItem($path,$zip,$base_dir);
    $zip->close();
    return $filename;
}

/**
  +---------------------------------------------------------------+
 * 16. 压缩文件
 * frequent_ezip($zip,$hedef)
  +---------------------------------------------------------------+
 * @param string $zip		压缩包路径
 * @param string $hedef		解压到的路径
  +---------------------------------------------------------------+
 */
function ezip($zip, $hedef = ''){
    $dirname=preg_replace('/.zip/', '', $zip);
    $root = $_SERVER['DOCUMENT_ROOT'].'/zip/';
    $zip = zip_open($root . $zip);
    @mkdir($root . $hedef . $dirname.'/'.$zip_dosya);
    while($zip_icerik = zip_read($zip)){
        $zip_dosya = zip_entry_name($zip_icerik);
        if(strpos($zip_dosya, '.')){
            $hedef_yol = $root . $hedef . $dirname.'/'.$zip_dosya;
            @touch($hedef_yol);
            $yeni_dosya = @fopen($hedef_yol, 'w+');
            @fwrite($yeni_dosya, zip_entry_read($zip_icerik));
            @fclose($yeni_dosya); 
        }else{
            @mkdir($root . $hedef . $dirname.'/'.$zip_dosya);
        };
    };
}




/**
目录
	1.  输入过滤------------------------------------------------------------------------  26
	2.  判断是否工作日------------------------------------------------------------------  50
	3.  计算结束时间--------------------------------------------------------------------  88
	4.  无限分类------------------------------------------------------------------------ 126
	5.  多维数组转1维------------------------------------------------------------------- 155
	6.  unicode编码--------------------------------------------------------------------- 178
	7.  unidode解码--------------------------------------------------------------------- 207
	8.  生成略缩图---------------------------------------------------------------------- 244
	9.  仿js alert()-------------------------------------------------------------------- 337
	10. 合并数组------------------------------------------------------------------------ 357
	11. 字符与16进制互转---------------------------------------------------------------- 371
	12. 获取客户端 ip------------------------------------------------------------------- 398
	13. 纯真ip地址---------------------------------------------------------------------- 445
	14. php汉字转拼音------------------------------------------------------------------- 661
	15. 压缩文件（打包下载）------------------------------------------------------------ 721
	16. 解压缩-------------------------------------------------------------------------- 774
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
 */
