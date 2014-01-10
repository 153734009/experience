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
 * 7. 浮点运算
 * frequent_floatAdd[/Sub/Mul/Div](arg1,arg2);
  +---------------------------------------------------------------+	
 *  parameters float 	arg1
 *  parameters float 	arg2
  +---------------------------------------------------------------+	
  | @return float
  +---------------------------------------------------------------+
 */
//浮点数加法运算
function floatAdd(arg1,arg2){
	var r1,r2,m;
	try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
	try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
	m=Math.pow(10,Math.max(r1,r2));//pow(X,y)就是计算X的Y次方
	return (arg1*m+arg2*m)/m
}
Number.prototype.floatAdd = function (arg){return floatAdd(arg, this);} 
//浮点数减法运算
function floatSub(arg1,arg2){
	var r1,r2,m,n;
	try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
	try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
	m=Math.pow(10,Math.max(r1,r2));
	n=(r1>=r2)?r1:r2;//动态控制精度长度
	return ((arg1*m-arg2*m)/m).toFixed(n);
}
Number.prototype.floatSub = function (arg){return floatSub(this, arg);}
//浮点数乘法运算
function floatMul(arg1,arg2){
	var m=0,s1=arg1.toString(),s2=arg2.toString();
	try{m+=s1.split(".")[1].length}catch(e){}
	try{m+=s2.split(".")[1].length}catch(e){}
	return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)
}
Number.prototype.floatMul = function (arg){return floatMul(this, arg);}
//浮点数除法运算
function floatDiv(arg1,arg2){
	var t1=0,t2=0,r1,r2;
	try{t1=arg1.toString().split(".")[1].length}catch(e){}
	try{t2=arg2.toString().split(".")[1].length}catch(e){}
	with(Math){
		r1=Number(arg1.toString().replace(".",""))
		r2=Number(arg2.toString().replace(".",""))
		return (r1/r2)*pow(10,t2-t1);
	}
}   
Number.prototype.floatDiv = function (arg){return floatDiv(this, arg);}

/**
  +---------------------------------------------------------------+
 * 8. js创建表单提交 
 * frequent_submit(url,method,data)
  +---------------------------------------------------------------+	
 *  parameters string		url	
 *  parameters string		method = post/get
 *  parameters string(json)	data(一维)
  +---------------------------------------------------------------+	
  | @return float
  +---------------------------------------------------------------+
 */
function frequent_submit(url,method="post",data){
	    var	f= document.createElement('form')
		f.action = url;
		f.method = method;
		document.body.appendChild(f);
	eval ("var data = {"+data+"}");
	for(var key in data){  
	    if(typeof data[key]  === 'string'||'number'){
		    var temp=document.createElement('input');
			temp.type= 'hidden';
			temp.name= key ;
			temp.value= data[key];	
			f.appendChild(temp);
	    }
	}
	f.submit();
}

/**
  +---------------------------------------------------------------+
 * 9. 获取DOM(ID/tagName/className)	对象化 
 * frequent_dom()
 * var d = new frequent_dom();		用法：d.id()/d.tagName()/d.className()
  +---------------------------------------------------------------+	
 *  function id
 *  	parameters string	id	
 *  function tagName
 *  	parameters object	parentNode
 *  	parameters string	element
 *  function className
 *  	parameters object	parentNode
 *  	parameters string	className
  +---------------------------------------------------------------+	
  | @return Node/NodeList
  +---------------------------------------------------------------+
 */
function frequent_dom(){
	if (typeof frequent_dom._initialized == "undefined") {
		frequent_dom.prototype.id = function (id){
			return typeof id === "string" ? document.getElementById(id) : id;
		}
		frequent_dom.prototype.tagName = function (objParent,element){
			alert('o_tag');
			return (objParent || document).getElementsByTagName(element);
		}
		frequent_dom.prototype.className = function (objParent, strClass){
			var noteListElement = frequent_tagName(objParent, '*');
			var noteListClass = [];
			var i = 0;
			for(i=0;i<noteListElement.length;i++){
				if(noteListElement[i].className == strClass)	noteListClass.push(noteListElement[i]);
			}
			return noteListClass;
		}
		frequent_dom._initialized = true;
	}
}

/**
 +----------------------------------------------------------------+
 * 正则
 * 	1.匹配全中文		/^[\u4e00-\u9fa5]+$/
 * 
 +----------------------------------------------------------------+
 */

/**
  +---------------------------------------------------------------+
  * 普及常识
  *	
  *	JSON.stringify(jsonObject)			query转换json对象为字符串
  *	三元运算
  *		形式1：(a==11) ? x=99:x=60;		输出99;条件为真的时候,执行第一个;为假执行第二个。
  *		形式2：x= (false) ? 99:60;		输出60;条件为真的时候,执行第一个；为假执行第二个。
  *		形式3: x = null || 18;			输出18;不存在执行第二个。
  *		       x = "" || 18;			输出18;不存在执行第二个。
  *		形式4: x = "x" || 18;			输出x;存在,执行第一个。
  +---------------------------------------------------------------+
 */


/**
目录
	1.  删除数组元素remove--------------------------------------------------------------  22
	2.  HTML编解码（string属性）--------------------------------------------------------  49
	3.  HTML编解码（函数）--------------------------------------------------------------  78
	4.  var_dump(模仿php)--------------------------------------------------------------- 114
	5.  缩小过大的图片------------------------------------------------------------------ 143
	6.  正则匹配全中文------------------------------------------------------------------ 193
	7.  浮点数运算---------------------------------------------------------------------- 210
	8.  js创建表单提交------------------------------------------------------------------ 263
	9.  获取DOM------------------------------------------------------------------------- 293
	8.  -------------------------------------------------------------------------------- 244
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
