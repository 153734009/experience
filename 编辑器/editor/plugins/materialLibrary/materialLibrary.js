KindEditor.plugin('materialLibrary', function(K) {
        var editor = this, 
			self = this,
			imgPath = self.pluginsPath + 'materialLibrary/',
			//lang = self.lang(name),
			name = 'materialLibrary';
        // 点击图标时执行
		self.plugin.materialLibrary = {
		edit : function() {
			var 
				html = '<div style="padding:20px;">' +
					//url
					'<div class="ke-dialog-row  js-closest">' +
						'<div class="input-prepend input-append">'+
						'<span class="add-on">图片</span>'+
						'<input id="editImageInput" class="ke-input-text input-large" type="text" id="keUrl" name="url" value=""  data-jstarget="93" />'+
						'<a class="btn file js-file-box" data-loading-text="上传中…">选择文件'+
						'<input type="file" data-jshook="93" data-tag="editor" data-jsaction="/Ajax/upLoadImage" onchange="materialImageUpload(this)"></a>'+
					
						'<a class="btn material-library" href="#picsAll" data-toggle="modal" data-js-loadurl="/Material/imageSelect.html?tag=editor" data-jstarget-final="#editImageInput"  onclick="editorShowModal(this)">从素材库选择</a>'+
						'</div>'+
					'</div>' +
					//size
			'<div class="ke-dialog-row">' +
			'<label for="remoteWidth" style="width:60px;">图片大小</label>' +
			'宽 <input type="text" id="remoteWidth" class="ke-input-text ke-input-number" name="width" value="" maxlength="4" /> ' +
			'高 <input type="text" class="ke-input-text ke-input-number" name="height" value="" maxlength="4" /> ' +
			'<img class="ke-refresh-btn" src="' + imgPath + 'refresh.png" width="16" height="16" alt="" style="cursor:pointer;" title="重置大小" />' +
			'</div>' +
			//align
			'<div class="ke-dialog-row">' +
			'<label style="width:60px;">对齐方式</label>' +
			'<input type="radio" name="align" class="ke-inline-block" value="center" checked="checked" /> <img name="defaultImg" src="' + imgPath + 'align_top.gif" width="23" height="25" alt="" />' +
			' <input type="radio" name="align" class="ke-inline-block" value="left" /> <img name="leftImg" src="' + imgPath + 'align_left.gif" width="23" height="25" alt="" />' +
			' <input type="radio" name="align" class="ke-inline-block" value="right" /> <img name="rightImg" src="' + imgPath + 'align_right.gif" width="23" height="25" alt="" />' +
			'</div>' +
			//title
			'<div class="ke-dialog-row">' +
			'<label for="remoteTitle" style="width:60px;">图片说明</label>' +
			'<input type="text" id="remoteTitle" class="ke-input-text" name="title" value="" style="width:200px;" />' +
			
			'</div>' +
				
					'</div>',
				dialog = self.createDialog({
					name : name,
					width : 490,
					title : self.lang(name),
					body : html,
					yesBtn : {
						name : self.lang('yes'),
						click : function(e) {
							var url = K.trim(urlBox.val());
							if (url == 'http://' || K.invalidUrl(url)) {
								alert(self.lang('invalidUrl'));
								urlBox[0].focus();
								return;
							}
							var width =  K.trim(widthBox.val());
							var height =  K.trim(heightBox.val());
							var title =  K.trim(titleBox.val());
							var align =  $("[name='align']:checked").val();
							self.exec('insertimage', url, title, width, height, '1', align).hideDialog().focus();
							//self.insertHtml('<img src="'+url+'">');
						}
					}
				}),
				div = dialog.div,
				urlBox = K('input[name="url"]', div),
				widthBox = K('input[name="width"]', div),
				heightBox = K('input[name="height"]', div),
				titleBox = K('input[name="title"]', div),
				alignBox = K('input[name="align"]', div),
				typeBox = K('select[name="type"]', div);
			urlBox.val('http://');
			self.cmd.selection();
			var a = self.plugin.getSelectedLink();
			if (a) {
				self.cmd.range.selectNode(a[0]);
				self.cmd.select();
				urlBox.val(a.attr('data-ke-src'));
				typeBox.val(a.attr('target'));
			}
			urlBox[0].focus();
			urlBox[0].select();
		}
	};
	self.clickToolbar(name, self.plugin.materialLibrary.edit);
});
