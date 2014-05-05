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
var MobileMenu_school = new MobileMenu('school','schoolResult');
var MobileMenu_build = new MobileMenu('build','buildResult');
//var mobileMenu = {//静态方法
//	"isShow":false,
//	"hook":"",
//	"target":"",
//	"init":function(hook,target){
//		this.hook = $("#"+hook);
//		this.target = $("#"+target);
//		this.hook.click(function(){
//			mobileMenu.show();
//		})
//	},
//	"show":function(){
//		if(this.isShow){
//			this.target.animate({left:'-40%'});
//			this.isShow = false;
//		}else{
//			this.target.animate({left:'0%'});
//			this.isShow = true;
//		}
//	},
//}
