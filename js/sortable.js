$("#sortable").sortable({
	items:"li",
	update: function(event, ui) {
		//同 jquery.prev()
		//item[0].previousElementSibling
		//！！！！！
		//请用经常用自己写的 var_dump();打印对象的内容
		var prev = $(ui.item[0].previousElementSibling).data("sort");
		var next = $(ui.item[0].nextElementSibling).data("sort")
		var active = ui.item[0];
	return false;
	}
});
