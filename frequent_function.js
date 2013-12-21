// JavaScript Document
/**
 * +-----------------------------------------
 * | Common
 * +-----------------------------------------
 * | Frequent functions in my CODE career
 * +-----------------------------------------
 * | Author: Everyone  you & me(Dale)
 * +----------------------------------------
 */

/**
  +---------------------------------------------------------------+
 * 1.	自定义数组remove
 * Array.prototype.remove(value);
  +---------------------------------------------------------------+	
 * @param string|array|integer...	value	数组元素
  +---------------------------------------------------------------+	
  | @return array
  +---------------------------------------------------------------+
 */
Array.prototype.remove = function(value){
	var dx=-1;
	for(var i=0;i<this.length;i++){
		if (this[i]==value){
			dx=i;
			break;
		}
	}
	if (dx!=-1){
		for(var i=0,n=0;i<this.length;i++){ 
			if(this[i]!=this[dx])  this[n++]=this[i];
		} 
		this.length-=1 
	}
	return this;
}

/**
  +---------------------------------------------------------------+
 * 2.	自定义String方法
 * HTML编解码
 * String.prototype.HTML_encode
 * String.prototype.HTML_decode
  +---------------------------------------------------------------+	
  | @return string 
  +---------------------------------------------------------------+
 */
String.prototype.HTML_encode = function(){
	str = this.replace(/</g,"&lt;");
	str = str.replace(/>/g,"&gt;");  
	str = str.replace(/ /g,"&nbsp;");  
	str = str.replace(/\'/g,"'");  
	str = str.replace(/\"/g,"&quot;");  
	str = str.replace(/\n/g,"<br />");  
	return str;
}
String.prototype.HTML_decode = function(){
	str = this.replace(/&lt;/g,"<");
	str = str.replace(/&gt;/g,">");  
	str = str.replace(/&nbsp;/g," ");  
	str = str.replace(/'/g,"\'");  
	str = str.replace(/&quot;/g,"\"");  
	str = str.replace(/<br \/>/g,"\n");str = str.replace(/<br>/g,"\n");
	return str;
}

/**
  +---------------------------------------------------------------+
 * 3.	HTML实体化
 * frequent_HTMLencode(str);
  +---------------------------------------------------------------+	
 * @param string	str	数组元素
  +---------------------------------------------------------------+	
  | @return string	str
  +---------------------------------------------------------------+
 */
function frequent_HTMLencode(str){  
	var new_str = "";  
	if(str.length==0) return "";  
	//new_str=str.replace(/ & /g," &amp; ");
	new_str=new_str.replace(/</g,"&lt;");  
	new_str=new_str.replace(/>/g,"&gt;");  
	new_str=new_str.replace(/ /g,"&nbsp;");  
	new_str=new_str.replace(/\'/g,"'");  
	new_str=new_str.replace(/\"/g,"&quot;");  
	new_str=new_str.replace(/\n/g,"<br />");  
	return	new_str;  
}
function frequent_HTMLdecode(str){  
	var new_str = "";  
	if(str.length==0) return "";  
	new_str = str.replace(/&amp;/g,"&");
	new_str = new_str.replace(/&lt;/g,"<");
	new_str = new_str.replace(/&gt;/g,">");  
	new_str = new_str.replace(/&nbsp;/g," ");  
	new_str = new_str.replace(/'/g,"\'");  
	new_str = new_str.replace(/&quot;/g,"\"");  
	new_str = new_str.replace(/<br \/>/g,"\n");new_str = new_str.replace(/<br>/g,"\n");
	return	new_str;  
}

/**
  +---------------------------------------------------------------+
 * 4.	模仿php打印变量
 * var_dump(obj);
 * document.write("<pre>"+var_dump(arr)+"</pre>");（用法示例）
  +---------------------------------------------------------------+
 * @param string	obj	数组元素
  +---------------------------------------------------------------+	
  | @return string
  +---------------------------------------------------------------+
 */
function var_dump(obj){
	var str;
	if(typeof(obj) == "object"){
		var txt = '';
		for(key in obj){
			txt += key + '=' + typeof(obj[key]) + ',\n';
		}
		str = "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "")+"\nValue: " + txt;
	}else{
		str = "Type: "+typeof(obj)+"\nValue: "+obj;
	}
	str = str.HTML_encode();//html部分字符实体化，保证显示
	document.write("<pre>"+str+"<pre>");
	//return false;
}


/**
  +---------------------------------------------------------------+
 * 5.	缩小过大的图片
 * frequent_shrink_image(wrapClass,width,imgClass);
  +---------------------------------------------------------------+	
 * @param string	wrapClass
 * @param integer	width
 * @param integer|array	imgClass
  +---------------------------------------------------------------+	
  | @return
  +---------------------------------------------------------------+
 */
function frequent_shrink_image(wrapClass,newWidth,imgClass){  
	function r(img,w){
		if(img.offsetWidth > w){
			img.style.width = w+"px";
		}	
	}
	var arr_image = new Array();
	if (!wrapClass){
		arr_image = document.getElementsByTagName("img");
	}else{
		arr_wrapClass = wrapClass.split(",");
		var wrapNode = new Array();
		//获取所有的 里面图片 需要缩小的元素
		for(var i=0;i<arr_wrapClass.length;i++){
			wrapElement = document.getElementsByClassName(arr_wrapClass[i]);
			//取出该class的所有元素，再把元素赋给wrapNode
			for(var j=0;j<wrapElement.length;j++){
				wrapNode.push(wrapElement[j]);
			}
		}
		
		//获取所有的图片
		for(var i=0;i<wrapNode.length;i++){
			imageElement = wrapNode[i].getElementsByTagName("img");
			//取出该wrap的所有image，再把image赋给arr_image
			for(var j=0;j<imageElement.length;j++){
				arr_image.push(imageElement[j]);
			}
		}
	}
	//如果有指定缩小的imgClass;排除其他
	if(imgClass){
		imgClass += ",";
		for(var i=0;i<arr_image.length;i++){
			if (arr_image[i]['className']){
				position = imgClass.indexOf(arr_image[i]['className']+",");
				if (-1 == position)	arr_image.remove(arr_image[i]);
			}else{
				arr_image.remove(arr_image[i]);
			}
		}
	}
	var_dump(arr_image);
	for(var i=0;i<arr_image.length;i++){
		//arr_image[i].onload = r(arr_image[i],100);
		if(arr_image[i].offsetWidth > newWidth)  arr_image[i].style.width = newWidth+"px";
	}
}
//document.onreadystatechange = frequent_shrink_image();

/**
  +---------------------------------------------------------------+
 * 6. 正则匹配全中文
  +---------------------------------------------------------------+	
 *  parameters str
  +---------------------------------------------------------------+	
  | @return boole
  +---------------------------------------------------------------+
 */
function frequent_hanzi_test(str){
	if(/^[\u4e00-\u9fa5]+$/.test(str)){
		return true;
	}else{
		return false;
	}
}

/**
  +---------------------------------------------------------------+
 * 普及常识
  +---------------------------------------------------------------+
 * 三元运算
	形式1：(a==11) ? x=99:x=60;		输出99;条件为真的时候,执行第一个;为假执行第二个。
	形式2：x= (false) ? 99:60;		输出60;条件为真的时候,执行第一个；为假执行第二个。
	形式3: x = null || 18;			输出18;不存在执行第二个。
	       x = "" || 18;			输出18;不存在执行第二个。
	形式4: x = "x" || 18;			输出x;存在,执行第一个。
 */

/**
目录
	1.  删除数组元素remove--------------------------------------------------------------  22
	2.  HTML编解码（string属性）--------------------------------------------------------  49
	3.  HTML编解码（函数）--------------------------------------------------------------  78
	4.  var_dump(模仿php)--------------------------------------------------------------- 114
	5.  缩小过大的图片------------------------------------------------------------------ 143
	6.  unicode编码--------------------------------------------------------------------- 178
	7.  unidode解码--------------------------------------------------------------------- 207
	8.  生成略缩图---------------------------------------------------------------------- 244
	9.  仿js alert()-------------------------------------------------------------------- 337
	10. 合并数组------------------------------------------------------------------------ 357
	11. 字符与16进制互转---------------------------------------------------------------- 371
	12. 获取客户端 ip------------------------------------------------------------------- 398
	13. 纯真ip地址---------------------------------------------------------------------- 445
	14. php汉字转拼音------------------------------------------------------------------- 661
	4.  无限分类------------------------------------------------------------------------ 126
	4.  无限分类------------------------------------------------------------------------ 126
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
