function imageUploader(hook,target){
	//重点是tpl模板里的iframe 模拟ajax
	//$("[href='#']")
	this.hook = $("[data='"+hook+"']");
	this.target =  $("[data='"+target+"']");
	var self = this;
	this.hook.click(function(){
		$("#image-uploader-wrap").css("display","block");
	})
	
	if (typeof imageUploader._initialized == "undefined") {
		//载入上传文件的模板
		$("<div class=\"iu-container\"></div>").appendTo($("body"));
		$(".iu-container").load("{$site_url}{$vendor}imageUploader/tpl.html",'',
				function(){
					$(".image-uploader-close").click(function(){
						$("#image-uploader-wrap").css("display","none");
					});
					
				}
				);
		imageUploader.uploadCallback = function(data){
			if(data.status==0){
				alert(data.info);
			}else{
				self.target.val(data.info);
				$(".image-uploader-close").click();
			}
		}
		

		imageUploader._initialized = true;
	}
}
var imgUp = new imageUploader('hook-1','target-1');
