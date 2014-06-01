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
			//在此迭代 递归
			if(typeof(obj.key) == "object"){
			}
			txt += key + '=' + obj[key] + ',\n';
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
	//eval ("var data = {"+data+"}");
	//eval ("var data = "+data);
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
