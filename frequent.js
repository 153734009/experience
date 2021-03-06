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
	return (/^[\u4e00-\u9fa5]+$/.test(str));
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
function frequent_submit(url,method,data){
	    var	f= document.createElement('form')
		f.action = url;
		f.method = method;
		document.body.appendChild(f);
	eval ("var data = {"+data+"}");//如果data 直接是对象，直接去掉次句
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
 * 9. 获取DOM(ID/tagName/className)	不好用，待优化。对象化 
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
  +---------------------------------------------------------------+
 * 10. 时钟
 * frequent_clock(id)
  +---------------------------------------------------------------+	
 *  parameters string		id 时钟容器的ID
  +---------------------------------------------------------------+	
  | @return string(dynamic)	
  +---------------------------------------------------------------+
 */
function frequent_clock(id){
	var clock = new Date();
	var Y = clock.getFullYear();
	var m = (clock.getMonth()+1)<10 ? "0"+(clock.getMonth()+1):clock.getMonth()+1;
	var d = clock.getDate()<10 ? "0"+clock.getDate() : clock.getDate();
	var h = clock.getHours()<10 ? "0"+clock.getHours() : clock.getHours();
	var i = clock.getMinutes()<10 ? "0"+clock.getMinutes() : clock.getMinutes();
	var s = clock.getSeconds()<10 ? "0"+clock.getSeconds() : clock.getSeconds();
	document.getElementById(id).innerHTML = Y+'-'+m+'-'+d+' '+h+':'+i+':'+s;
	t = setTimeout('frequent_clock("'+id+'")',1000);	
}

/**
  +---------------------------------------------------------------+
 * 11. VB 汉子拼音首字母
 * getPY_VBscript(str)
  +---------------------------------------------------------------+	
 *  parameters string		str 汉字
  +---------------------------------------------------------------+	
  | @return string		str 拼音
  +---------------------------------------------------------------+
 */
function getPY_VBscript(s) {
    if (s != "") {
        execScript("tmp=asc(\"" + s + "\")", "vbscript");
        tmp = 65536 + tmp;
        var py = "";
        if (tmp >= 45217 && tmp <= 45252) {
            py = "A"
        } else if (tmp >= 45253 && tmp <= 45760) {
            py = "B"
        } else if (tmp >= 45761 && tmp <= 46317) {
            py = "C"
        } else if (tmp >= 46318 && tmp <= 46825) {
            py = "D"
        } else if (tmp >= 46826 && tmp <= 47009) {
            py = "E"
        } else if (tmp >= 47010 && tmp <= 47296) {
            py = "F"
        } else if ((tmp >= 47297 && tmp <= 47613) || (tmp == 63193)) {
            py = "G"
        } else if (tmp >= 47614 && tmp <= 48118) {
            py = "H"
        } else if (tmp >= 48119 && tmp <= 49061) {
            py = "J"
        } else if (tmp >= 49062 && tmp <= 49323) {
            py = "K"
        } else if (tmp >= 49324 && tmp <= 49895) {
            py = "L"
        } else if (tmp >= 49896 && tmp <= 50370) {
            py = "M"
        } else if (tmp >= 50371 && tmp <= 50613) {
            py = "N"
        } else if (tmp >= 50614 && tmp <= 50621) {
            py = "O"
        } else if (tmp >= 50622 && tmp <= 50905) {
            py = "P"
        } else if (tmp >= 50906 && tmp <= 51386) {
            py = "Q"
        } else if (tmp >= 51387 && tmp <= 51445) {
            py = "R"
        } else if (tmp >= 51446 && tmp <= 52217) {
            py = "S"
        } else if (tmp >= 52218 && tmp <= 52697) {
            py = "T"
        } else if (tmp >= 52698 && tmp <= 52979) {
            py = "W"
        } else if (tmp >= 52980 && tmp <= 53688) {
            py = "X"
        } else if (tmp >= 53689 && tmp <= 54480) {
            py = "Y"
        } else if (tmp >= 54481 && tmp <= 62289) {
            py = "Z"
        } else {
            py = s.charAt(0)
        }
        return py
    }
}

/**
  +---------------------------------------------------------------+
 * 12. 设置打印区域
 * frequent_doPrint(start,end)
  +---------------------------------------------------------------+	
 *  parameters string		start 开始标记
 *  parameters string		end 结束标记
  +---------------------------------------------------------------+	
  | 例：frequent_doPrint("<!--startprint-->","<!--endprint-->")
  +---------------------------------------------------------------+	
 */
function frequent_doPrint(start,end) { 
	bdhtml=window.document.body.innerHTML; 
	startStr = start; 
	endStr = end; 
	prnhtml=bdhtml.substr(bdhtml.indexOf(startStr)+17); 
	prnhtml=prnhtml.substring(0,prnhtml.indexOf(endStr)); 
	window.document.body.innerHTML=prnhtml; 
	window.print(); 
}
/**
  +---------------------------------------------------------------+
 * 13. 表格导出excel
 * frequent_2excel(id)
  +---------------------------------------------------------------+	
 *  parameters string		id 要转化的table节点id
  +---------------------------------------------------------------+	
 */
function frequent_2excel(id) {//整个表格拷贝到EXCEL中
    var curTbl = document.getElementById(id);
    var oXL = new ActiveXObject("Excel.Application");
    //创建AX对象excel
    var oWB = oXL.Workbooks.Add();
    //获取workbook对象
    var oSheet = oWB.ActiveSheet;
    //激活当前sheet
    var sel = document.body.createTextRange();
    sel.moveToElementText(curTbl);
    //把表格中的内容移到TextRange中
    sel.select();
    //全选TextRange中内容
    sel.execCommand("Copy");
    //复制TextRange中内容 
    oSheet.Paste();
    //粘贴到活动的EXCEL中      
    oXL.Visible = true;
    //设置excel可见属性
}

/**
  +---------------------------------------------------------------+
 * 14. 闭包函数（待整理）
  +---------------------------------------------------------------+	
  | 不使用闭包的话，test2无法延迟
  +---------------------------------------------------------------+	
 */
function callLater(paramA, paramB, paramC){
/* 返回一个由函数表达式创建的匿名内部函数的引用:- */
var style = document.getElementById("test").style;
	return (function(){
	/* 这个内部函数将通过 - setTimeout - 执行，
	而且当它执行时它会读取并按照传递给
	外部函数的参数行事：
	*/
	//style.paramB = paramC;
	style[paramB] = paramC;
	});
}
function c2(paramA, paramB, paramC){
	alert('?');
}
//var functRef = callLater("test", "display", "none");
test_1 = setTimeout("c2(1,2,3)", 2000);
test_2 = setTimeout(callLater(paramA, paramB, paramC),2000);

/**
  +---------------------------------------------------------------+
 * 15. 子窗口操作父窗口
 * 通常在使用window.opener的时候要去判断父窗口的状态，如果父窗口被关闭或者更新，
 * 就会出错，解决办法是加上如下的验证 if(window.opener && !window.opener.closed)
 *		iframe 内容的获取
  +---------------------------------------------------------------+	
 */

	var childWindow = window.open("/index.php");
	childWindow.close();
	window.location.reload();
//	window.opener 实际上就是通过window.open打开的窗体的父窗体。
//	window.opener.test(); ---调用父窗体中的test()方法
	$(window.frames["if_library"].document).find("#tiles");

	function callParent() { 
		parent.func(); 
	} 

/**
  +---------------------------------------------------------------+
 * 16. js重定向
  +---------------------------------------------------------------+	
 */
	window.location.href="login.jsp?backurl="+window.location.href; 
	window.history.back(-1); 
        window.navigate("top.jsp"); 
        self.location='top.htm'; 
        top.location='xx.jsp'; 

/**
  +---------------------------------------------------------------+
 * 17. 手机版的菜单，左侧pull出
 * 对象化写法
  +---------------------------------------------------------------+	
 */	
function MobileMenu(hook,target){
	this.hook = $("#"+hook);
	this.target = $("#"+target);
	this.isshow = false;
	this.menulist = $("#"+target+" li");
	var self = this;
	//在此绑定事件
	this.hook.click(function(){
		self.show();
	});
	this.menulist.click(function(){
		//这里的this指向li元素，当然是这样好了
		self.hook.html($(this).html());
		self.show();
	});
	
	if (typeof MobileMenu._initialized == "undefined") {
		MobileMenu.prototype.show = function() {
		       if(this.isshow){
				this.target.animate({left:'-40%'});
				this.isshow = false;
			}else{
				this.target.animate({left:'0%'});
				this.isshow = true;
			}
		};
		MobileMenu._initialized = true;
	}
}
//var MobileMenu_school = new MobileMenu('school','schoolResult');
//var MobileMenu_build = new MobileMenu('build','buildResult');

/**
  +---------------------------------------------------------------+
 * 18. 动态绑定
  +---------------------------------------------------------------+	
 */
	// 事件绑定
	this.bindHandler = (function() {
		if (window.addEventListener) {// 标准浏览器
			return function(elem, type, handler) {// elem:节点    type:事件类型   handler:事件处理程序
				// 最后一个参数为true:在捕获阶段调用事件处理程序    为false:在冒泡阶段调用事件处理程序
				elem.addEventListener(type, handler, false);
			}
		} else if (window.attachEvent) {// IE浏览器
			return function(elem, type, handler) {
				elem.attachEvent("on" + type, handler);
			}
		}
	})();

	// 事件解除
	this.removeHandler = (function() {
		if (window.removeEventListerner) {// 标准浏览器
			return function(elem, type, handler) {
				elem.removeEventListerner(type, handler, false);

			}
		} else if (window.detachEvent) {// IE浏览器
			return function(elem, type, handler) {
				elem.detachEvent("on" + type, handler);
			}
		}
	})();

/**
  +---------------------------------------------------------------+
 * 19. 通用型的数据 修改表单 读取 写入
  +---------------------------------------------------------------+	
 */
	function frequent_modify(arr,obj){
		this.hook = $("[data-jshook='"+arr[0]+"']");
		this.target = $("[data-jstarget='"+arr[1]+"']");
		var self = this;//用一个变量存自己
		//在此绑定事件
		this.hook.click(function(){
			var form_elements = self.target.find("form").find("[name]");
			form_elements = $.makeArray(form_elements);
			for(key in obj){
				var element = self.target.find("[data-jsreceive='"+obj[key]+"']")[0];
				//element = document.createElement('input'); 验证不在数组中 会返回-1
				if(-1 == form_elements.indexOf(element)){
					self.target.find("[data-jsreceive='"+obj[key]+"']").html($(this).data(key));
				}else{
					self.target.find("[data-jsreceive='"+obj[key]+"']").val($(this).data(key));
				};
			}
		});
	}
var jsmodify4 = new frequent_modify(Array("4","4"),{"title":"title","_id":"_id","pid":"pid"});	

/**
  +---------------------------------------------------------------+
 * 20. clone对象(复制)
  +---------------------------------------------------------------+	
 */
	Object.prototype.Clone = function(){
		var objClone;
		if (this.constructor == Object){
			objClone = new this.constructor(); 
		}else{
			objClone = new this.constructor(this.valueOf()); 
		}
		for(var key in this){
			if ( objClone[key] != this[key] ){ 
				if ( typeof(this[key]) == 'object' ){ 
					objClone[key] = this[key].Clone();
				}else{
					objClone[key] = this[key];
				}
			}
		}
		objClone.toString = this.toString;
		objClone.valueOf = this.valueOf;
		return objClone; 
	} 

/**
  +---------------------------------------------------------------+
 * 21. host,域名
 * 请注意 match[1]
  +---------------------------------------------------------------+	
 */
	function getHost(url){
		url = url ? url : window.location.href;
		var match = url.match(/(.*\:\/\/)([^\/|:]*).*/);
		return match[2];
	}

/**
 * 22 为 未来 绑定
 * ============================================================================	
 * 请注意：
 *		放在$(function(){})里才有效 
 */
	$(function(){
		$(document).on("click", "[data-eweiwei='delete']", function(){ 
			var url = $(this).data("action"),
				pa = $(this).closest($(this).data("parent"));
				$.get(url);
				pa.remove();
		}); 
	})

/** 23. confirm为 未来 绑定
 * ============================================================================	
 */
	<a class="pBtn" onclick="return confirm('确认删除？')" >删除</a>
	$(".pBtn").click(function(){
		if ( !confirm('确认发布？') ){
			return false;
		}
		var url = $(this).data("action");
		alert(url);
	})

/** 24. 获取计算后的样式
 * ============================================================================	
 */
	function frequent_finalStyle(obj, name) {
		if(obj.currentStyle){//IE 未详细测试
			return obj.currentStyle[name];
		}else{//标准浏览器
			return getComputedStyle(obj, false)[name]; 
		}
	}

/** 25. 载入html5本地数据（未完善）
 * ==============================
 */
/*
  +---------------------------------------------------------------+	
	读入本地存储的数据
  +---------------------------------------------------------------+	
  |	//在文档的最后，把本地存储的数据返回到相应的表单位置
  |	//数据写回页面是个噩梦，如果只是 对应表单的 name value  当然方便，
  |	//但是需要修改文档结构 
  +---------------------------------------------------------------+	
 */
	function loadHTML5Data() {
		for (var str in localStorage) {
			eval("var data=" + localStorage[str]);
			var form = $("form[name='" + str + "']");
			//str = "product"
			for (var key in data) {
				form.find("[name='" + key + "']").each(function(){
					switch(this.type){
					//	case  'checkbox':
					//		//判读它的值 是否在html5 data 中，含逗号
					//		if(data[key].indexOf(this.value+',') != -1){
					//			this.checked = true;
					//		}else{
					//			this.checked = false;
					//		};
					//		break;
						default:
							this.value = data[key];
					}
				});
				//考虑数组形式的值
				form.find("[name='" + key + "[]']").each(function(){
					switch(this.type){
						case  'checkbox':
							//判读它的值 是否在html5 data 中，含逗号
							if(data[key].indexOf(this.value+',') != -1){
								this.checked = true;
							}else{
								this.checked = false;
							};
							break;
						default:
							this.value = data[key];
					}
				});
				// product 是使用在product_index页面的数据
				if (key == "img") {
					var arr = data[key];
					var str = "";
					for (i in arr) {
						var url = arr[i].url;
						str += '<li class="span2 hoverable cur_move" title="可拖动为产品图片排序"><div class="hover-ctrl"><em></em><a class="btn" href="' + url + '" target="_blank" title="预览产品图"><i class="icofont-eye-open"></i></a><a class="btn btn-warning  jslocal-delete-li" title="删除产品图"><i class="icofont-remove"></i></a></div><div class="thumbnail"><img class="img-responsive" src="' + url + '"></div><input type="text" name="image[]" value="' + url + '" style="display:none;"></li>';
					}
					$("#js-html5data-product-img").append(str);
				}
			}
		}
		//动态写入文档结构之后，需要bindJSLocalDeleteLi再绑定
		//动态写入文档结构之后，需要select2再绑定
		bindJSLocalDeleteLi();
		$('[data-form=tags]').select2({tags:[]});
	};
	function loadHTML5Data() {
		for (var str in localStorage) {
			eval("var data=" + localStorage[str]);
			var form = $("form[name='" + str + "']");
			//str = "product"
			for (var key in data) {
				form.find("[name='" + key + "']").each(function(){
					switch(this.type){
					//	case  'checkbox':
					//		//判读它的值 是否在html5 data 中，含逗号
					//		if(data[key].indexOf(this.value+',') != -1){
					//			this.checked = true;
					//		}else{
					//			this.checked = false;
					//		};
					//		break;
						default:
							this.value = data[key];
					}
				});
				//考虑数组形式的值
				form.find("[name='" + key + "[]']").each(function(){
					switch(this.type){
						case  'checkbox':
							//判读它的值 是否在html5 data 中，含逗号
							if(data[key].indexOf(this.value+',') != -1){
								this.checked = true;
							}else{
								this.checked = false;
							};
							break;
						default:
							this.value = data[key];
					}
				});
				// product 是使用在product_index页面的数据
				if (key == "img") {
					var arr = data[key];
					var str = "";
					for (i in arr) {
						var url = arr[i].url;
						str += '<li class="span2 hoverable cur_move" title="可拖动为产品图片排序"><div class="hover-ctrl"><em></em><a class="btn" href="' + url + '" target="_blank" title="预览产品图"><i class="icofont-eye-open"></i></a><a class="btn btn-warning  jslocal-delete-li" title="删除产品图"><i class="icofont-remove"></i></a></div><div class="thumbnail"><img class="img-responsive" src="' + url + '"></div><input type="text" name="image[]" value="' + url + '" style="display:none;"></li>';
					}
					$("#js-html5data-product-img").append(str);
				}
			}
		}
		//动态写入文档结构之后，需要bindJSLocalDeleteLi再绑定
		//动态写入文档结构之后，需要select2再绑定
		bindJSLocalDeleteLi();
		$('[data-form=tags]').select2({tags:[]});
	};
//	loadHTML5Data();

/**
 * 26. 统一的html5本地数据库存储方法
 * ====================================
 | 	eval ("jsdata = "+jsdata+";");
 | 	{type:'localStorage',variable:'product'}
 |		localStorage.clear();
 |		localStorage.removeItem("product");
 |		localStorage.setItem(jsdata.variable,{});
 +---------------------------------------------------------------+	
 */
function addHTML5Data(key, obj) {
	if (key && obj) {
		var data_str = localStorage[key];

		if (typeof(data_str) == 'undefined') {
			data = {}
		} else {
			eval("var data=" + data_str);
		}
		for (k in obj) {
			data[k] = obj[k];
		}
		var data_str = JSON.stringify(data);
		localStorage[key] = data_str;
	}
}
function openHTML5Data(){
		$(".js-storage").change(function() {
			//判断是否checkedbox
			if(this.type == 'checkbox'){
				//var name = $(this).closest("ul.js-rule-list").attr("data");
				var name = $(this).attr("name");
				var checked = $(this).closest("ul.js-rule-list").find(":checked");
				var str= '';
				checked.each(function(index,element){
					str += element.value+',';//这里的this 是具体的checked 的 input 	
				});
				//str = str.substr(0,str.length-1);
				//存入html5数据库不要去掉结尾的 "," ，留着后面判断有用
				var obj = {};
				obj[name] = str;
				var key = $(this).closest(".js-html5data-key").data("key");
				addHTML5Data(key, obj);
			}else{
				var key = $(this).closest(".js-html5data-key").data("key");
				var obj = {};
				obj[$(this).attr("name")] = $(this).val();
				addHTML5Data(key, obj);
			}
		});
	}
/**
 * 27. 全选按钮
 * data-eweiwei="checkAll"
 * ====================================
 */
$("[data-eweiwei='checkAll']").click(function(){
	var pa = $(this).closest($(this).data("wrap")),
		meStatus = $(this).attr("checked");
		if (meStatus == 'checked'){
			$(this).attr("checked",false);
			pa.find("tbody :checkbox:checked").click();
		}else{
			$(this).attr("checked",true);
			pa.find("tbody :checkbox:not(:checked)").click();
		}	
})

/**
 * 28. 同源 iframe 内结构
 * contents()
 * ====================================
 */
var form = $("iframe").contents().find("form");

/*
 * src 引用的不同播放器。会影响表现
<embed src="http://www.ledidea.cn/statics/images/Flvplayer.swf" allowfullscreen="true" flashvars="vcastr_file=http://localhost/1.flv&amp;autostart=false" wmode="transparent" quality="high" style="float:left;width: 1420px;height:680px;">
*/


/**
 * 29. 检查是否EAN13标准的条形码
 * @param	string		str 要检查的字符串
 * @return	boolean
 */
	function frequent_isEAN13(str){
		if ( str.length!=13 ){
			return false;
		}
		var odd = 0,even = 0;
		for(i=0; i<12; i++){
			i%2==1 ? even+=parseInt(str[i]*3) : odd+=parseInt(str[i]);
		}
		verify = (odd+even)%10==0 ? 10:(odd+even)%10;
		return ( str[12] == 10-verify );
	}

/**
 * 30 依赖boostrap alert，请结合使用
 * @param		string		node 添加到此节点中
 * @param		string		errmsg 提示信息
 * @return		void
 */
	function frequent_alert(node, errmsg){
		$(node).prepend('<div class="alert alert-error fade in">'+errmsg+'</div>');
	}
/**
 * 31 防止网页被嵌入iframe
 */
	if (window!=top) // 判断当前的window对象是否是top对象
	top.location.href =window.location.href; // 如果不是，将top对象的网址自动导向被嵌入网页的网址

/**
 +----------------------------------------------------------------+
 * 正则
 * 	1.匹配全中文		/^[\u4e00-\u9fa5]+$/
 * 	2.匹配文件名		/[^\\\/]*$/	
 *		例： "http://127.0.0.1/Material/voice.js".match(/[^\\\/]*$/)
 *	3.匹配后缀名
 *		例："C:\fakepath\test.eweiwei.com.jpg".match(/[^\\\.]*$/)
 *		index = value.lastIndexOf(".");suffix = value.substring(index+1);
 *	4.匹配域名
 *		window.location.href.match(/(.*\:\/\/)([^\/|:]*).* /);
 *	5.电话号码
 *		new RegExp("^[0-9]{3,4}[ -]{0,1}[0-9]{7,8}$")
 *	  手机号码（支持  131 2939 0972的带空格写法）
 *		/^1[^1267][0-9][ ]?[0-9]{4}[ ]?[0-9]{4}$/ 
 *	  身份证
 *		(^[0-9]{15}$|^[0-9]{17}[0-9Xx]$)
 *	6. 匹配多种 多字符
 *		var patt = new RegExp( "(^wxMenu_|^sys_).*");
 *		var patt = /(^wxMenu_|^sys_).* /;
 *	7. //var patt = /m(?!(a|b|add)\.).+\.cn/;
 * 
 +----------------------------------------------------------------+
 */

/**
  +---------------------------------------------------------------+
  * 普及常识
  *	
  * 1.	JSON.stringify(jsonObject)			query转换json对象为字符串
  * 2.	三元运算
  *		形式1：(a==11) ? x=99:x=60;		输出99;条件为真的时候,执行第一个;为假执行第二个。
  *		形式2：x= (false) ? 99:60;		输出60;条件为真的时候,执行第一个；为假执行第二个。
  *		形式3: x = null || 18;			输出18;不存在执行第二个。
  *		       x = "" || 18;			输出18;不存在执行第二个。
  *		形式4: x = "x" || 18;			输出x;存在,执行第一个。
  * 3.	setTimeout
  *		var t = setTimeout('$("p").html("it is me")',2000);
  *		setTimeout和php sleep不同，它只延迟自身代码的执行，后面的代码会首先执行
  *		可以用clearTimeout(t)清除指定的
  * 4. 函数内部声明变量的时候，一定要使用var命令。如果不用的话，你实际上声明了一个全局变量！
  * 5. .prop('outerHTML') jquery 用这个来获取outerHTML
  * 6. 监听回车onkeypress="if(event.keyCode==13||event.which==13){alert(2)}" 
  * 10.	localhost:27017: insertDocument :: caused by :: 11000 E11000 duplicate key error index: eweiwei.sys_fields.$uni_name dup key: { : "custom_3" } 数据库插入错误，+ this->ajaxReturn 可能ajaxFrom引起页面找不到的问题
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
	10. 时钟---------------------------------------------------------------------------- 333
	11. VB 汉字拼音--------------------------------------------------------------------- 355
	12. 设置打印区域-------------------------------------------------------------------- 423
	13. 表格导出excel------------------------------------------------------------------- 443
	14. 闭包函数（待整理）-------------------------------------------------------------- 471
	15. 子窗口操作父窗口/iframe 内容的获取---------------------------------------------- 498	
	16. js重定向------------------------------------------------------------------------ 514
	17. 手机版的菜单，左侧pull出(对象化写法参考)---------------------------------------- 529
	18. 动态绑定------------------------------------------------------------------------ 563
	19. 通用型的数据 修改表单----------------------------------------------------------- 596
	20. clone对象----------------------------------------------------------------------- 622
	21. host,域名----------------------------------------------------------------------- 648
	22. 为 未来 绑定-------------------------------------------------------------------- 659
	23. 确认提示 confirm---------------------------------------------------------------- 673
	24. 获取计算后的样式---------------------------------------------------------------- 689
	25. 载入html5本地数据（未完善）----------------------------------------------------- 696
	26. 写入html5数据（未完善）--------------------------------------------------------- 760
	27. 全选按钮------------------------------------------------------------------------ 866
	28. 同源深入iframe------------------------------------------------------------------ 866
	31. 防止网页被嵌入iframe------------------------------------------------------------ 930
	
 */
