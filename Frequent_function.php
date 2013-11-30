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
 *
	strip_tags($input);
	htmlspecialchars();
	trim();
 
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
  +---------------------------------------------------------------+
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
function frequent_strToHex($string){   
	$hex="";   
	for($i=0;$i<strlen($string);$i++){   
		$hex.=dechex(ord($string[$i]));
		//dechex() 函数把十进制转换为十六进制。 所能转换的最大数值为十进制的 4294967295
		//bindec()2->10		octdec()8->10		base_convert()任意进制
		//decbin()		decoct()	hexdec()
		$hex=strtoupper($hex);
	}	
	return   $hex;   
}   
function frequent_hexToStr($hex){   
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
 * 14. php汉字转拼音
 * 如果出来的拼音不对，请检查第二个参数$code的设置
  +---------------------------------------------------------------+	
 *  parameters 
 * @param string	$str 时间戳
 * @param string	$code
  +---------------------------------------------------------------+	
 * @return string 
  +---------------------------------------------------------------+
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
function frequent_pinyin($str, $code='utf-8') {// $code='gb2312',如果出来的拼音不对，请检查第二个参数$code的设置
	require('array_pinyin.php'); 
	$pinyin_library = $frequent_pinyin_library;
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
 * 15. 真实文件类型
 * frequent_get_file_info($path)
  +---------------------------------------------------------------+
 * @param string $path		文件路径
  +---------------------------------------------------------------+
 * @return array		文件信息
  +---------------------------------------------------------------+
 */
function frequent_get_file_info($path){
	$binary_file = file_get_contents($path);
	$binary_info = substr($binary_file,0,2);// $binary_info	等价于	"\xFF\xD8";
		//$binary_info = frequent_strToHex($binary_info); 或者 ord($binary_info)可以看看字符信息
		//decbin(ord($binary_info)); 二进制信息
		//$binary_info = "\xFF\xD8";
	$binary_info = @unpack("C2chars",$binary_info);
		//任何一款拥有socket操作能力的语言都有一个专门用于组包的函数，php也不例外当然这组函数的用途不仅仅是组包。
		//解包2个字符，附带说返回的数据类型是：chars。"chars你可以自由声明，例如mychar"
	$binary_info = intval($binary_info['chars1'].$binary_info['chars2']);
		//转为一个整数，待比较
	switch ($binary_info){
		//更多文件的判断，可以引入 array_file_type.php。
		case 7790:
			$file_info['type']= 'exe';
			break;
		case 7784:
			$file_info['type']= 'midi';//音乐文件
			break;
		case 8297:
			$file_info['type']= 'rar';
			break;
		case 255216:
			$file_info['type']= 'jpg';
			break;
		case 7173:
			$file_info['type']= 'gif';
			break;
		case 6677:
			$file_info['type']= 'bmp';
			break;
		case 13780:
			$file_info['type']= 'png';
			break;
		default:
			$file_info['type']= 'unknown';
	}
	$file_info['size']= strlen($binary_file);
	return $file_info;
}

/**
  +---------------------------------------------------------------+
 * 16. eval()
 * eval() 函数把字符串按照 PHP 代码来计算。
  +---------------------------------------------------------------+
 * @param string $str		要转换的字符串
  +---------------------------------------------------------------+
  | $str = "array(1 => array ('linkurl' => '','imageurl' => 'http://127.0.0.1/uploadfile/2013/1129/20131129090415366.jpg','alt' => ''))";
  | eval("\$arr = $str;"); 注意里面有个分号
  | 此时：得到数组 $arr
  | 相当于 <?php 
  |		$arr = array(1 => array ('linkurl' => '','imageurl' => 'http://127.0.0.1/uploadfile/2013/1129/20131129090415366.jpg','alt' => ''));
  |	   ?>
  | 拓展：如果是 
  |		$v[0]['setting']="array(1 => array ('linkurl' =>'href'));
  |		需要先 $str = $v[0]['setting'];
  |		再 eval();
  +---------------------------------------------------------------+
 */

/**
  +---------------------------------------------------------------+
 * 15. 压缩文件  --待整理
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

/
/**
  +---------------------------------------------------------------+
  * 普及常识
  * & 函数引用（一般用于递归）
  * @ 错误控制运算符（尽量不用）
  * file_get_contents(img_path);读得二进制数据
  * :%s/\(\w\+\), \(\w\+\)/\2 \1/   将 Doe, John 修改为 John Doe
  * 替换部分匹配换行的是 \r 不是 \n，和查找不一样
  * header("Content-type: text/html; charset=utf-8");
  * 
  +---------------------------------------------------------------+
 */

/**
目录	Table of Contents ¶
	1.  输入过滤------------------------------------------------------------------------  13
	2.  判断是否工作日------------------------------------------------------------------  32
	3.  计算结束时间--------------------------------------------------------------------  63
	4.  无限分类------------------------------------------------------------------------ 107
	5.  多维数组转1维------------------------------------------------------------------- 138
	6.  unicode编码--------------------------------------------------------------------- 163
	7.  unidode解码--------------------------------------------------------------------- 194
	8.  生成略缩图---------------------------------------------------------------------- 227
	9.  仿js alert()-------------------------------------------------------------------- 317
	10. 合并数组------------------------------------------------------------------------ 342
	11. 字符与16进制互转---------------------------------------------------------------- 358
	12. 获取客户端 ip------------------------------------------------------------------- 382
	13. 纯真ip地址---------------------------------------------------------------------- 427
	14. php汉字转拼音------------------------------------------------------------------- 576
	15. eval 字符转任意----------------------------------------------------------------- 648
	16. 解压缩-------------------------------------------------------------------------- 699
	17. 压缩文件（打包下载）------------------------------------------------------------ 795
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
 */
