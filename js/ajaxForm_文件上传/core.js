/*
 * 修改器 
 *	Array(hook,target),
 *	{source1:target1,source2:target2,...} 
 * 结合 阿泉 的 data-title ....
 * 暂时无法 无缝修改 文件
 */
function materialModify(arr,obj){
	this.hook = $("[data-hook='"+arr[0]+"']");
	this.target = $("[data-target='"+arr[1]+"']");
	var self = this;//用一个变量存自己
	//在此绑定事件
	this.hook.click(function(){
		for(key in obj){
			self.target.find("[data-target='"+obj[key]+"']").val($(this).data(key));
			self.target.find("[data-target='"+obj[key]+"']").html($(this).data(key));
		}
	});
}
var m1 = new materialModify(Array("voice-hook-1","voice-target-1"),{"title":"title","_id":"_id"});
var m2 = new materialModify(Array("hook-2","target-2"),{"title":"title","_id":"_id"});

/*
 * ajaxForm 表单提交修改，通用型
 * 需要 _id 
 */
$("#ajaxForm_1").ajaxForm(function(data) {
	var form = $("#ajaxForm_1");
	var send_data = new Object();//提交的数据
	form.find("input[name]").each(function(){
		send_data[$(this).attr("name")] = $(this).val();
	})
	if( true == data.updatedExisting){
		var wrap = $("#"+send_data._id);
		delete send_data._id;
		for(key in send_data){
			wrap.find("[data='"+key+"']").html(send_data[key]);
			//按钮修改 和 按钮删除的data-xx数据也需要修改
			wrap.find("[data-hook]").data(key,send_data[key]);
		}
		//找出modal 模态框
		form.closest(".modal").modal('hide');
	}else{
		form.closest(".modal").find("[data='error-container']").html("更新失败");
	}
}); 
/*
 * ajaxForm 表单提交删除，通用型
 */
$("#ajaxForm_2").ajaxForm(function(data) {
	var form = $("#ajaxForm_2");
	var _id = form.find("[name='_id']").val();
	if(true == data.err){
		form.closest(".modal").find("[data='error-container']").html("删除失败");
	}else{
		$("#"+_id).remove();
		form.closest(".modal").modal('hide');
	}
});
