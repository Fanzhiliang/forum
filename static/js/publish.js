$(document).ready(function(){
	var E = window.wangEditor,
		tip = {}

	if(window.Utils){
		if(window.Utils.Tip){
			tip = new window.Utils.Tip()
		}
	}

	if(typeof E != 'undefined'){
		(function(){//发帖、回复帖子editor
			var	editor = new E('#toolbar','#editor'),
				editorFrame = $("#editor"),
				title = $("#title")

			if(editorFrame.length>0){
				editor.customConfig.uploadImgServer = '../controller/upload.php?session_id='+$("#session_id").val()
				editor.customConfig.showLinkImg = false
				editor.customConfig.menus = [
				    'link',  // 插入链接
				    'image',  // 插入图片
				    'emoticon' //表情
				]
				editor.customConfig.uploadImgHooks = {//上图片事件
					fail: function(xhr, editor, result){//失败
						console.log(result)
						tip.showTip('上传失败!')
					},
					success: function(xhr, editor, result){//成功
						var rem = parseInt($("html").css('font-size'))
						editorFrame.find("p img").load(function(){//修改图片大小
							if(!$(this).attr('resized')){
								editorFrame.find("img").each(function(){
									if(parseInt($(this).css('width')) >= parseInt(editorFrame.find('.w-e-text').css('width'))*(9/10)){
										var extra = parseInt($(this).css('height')) / (10*rem)
										if(extra>1){
											var rate = ((1/extra)*100)
											if(rate<10){
												rate = 10
											}else{
												rate += 10
											}
											$(this).css({'width':rate+'%'})
										}
									}
								})
								//阻止默认事件和冒泡传递
								$(this).click(silence)
								$(this)[0].ontouchstart = silence
							}
						})
					}
				}
				editor.create()
				title.val('')

				//点击发布按钮
				$("#publish").click(function(event){
					event.preventDefault()
					event.stopPropagation()
					if(editor.txt.html() != '<p><br></p>'){
						var title = $("#title"),
							result = {
								value: editor.txt.getJSON()
							}
						if(title.length>0){
							if(!title.val()){
								if(tip){
									tip.showTip('请输入标题!')
								}
								return false;
							}else{
								if(title.val().length>30){
									if(tip){
										tip.showTip('标题长度不得大于30个字符!')
									}
									return false;
								}else{
									result.title= title.val()
								}
							}
						}
						if(tip){
							tip.showTip('确认发布?',{
								sure: function(){
									var sessionId = $("#session_id").val(),
										postingsId = $("#postings_id").val(),
										floorId = $("#floor_id").val();
									if(!!sessionId){
										result['session_id'] = sessionId;
									}
									if(!!postingsId){
										result['postings_id'] = postingsId;
									}
									if(!!floorId){
										result['floor_id'] = floorId;
									}
									console.log(result)
									$.ajax({
										url: '../controller/publish.php',
										type: 'POST',
										data: {body:result},
										//dataType: 'json',
										success: function(data){
											if(typeof data == 'string'){
												data = eval('('+data+')');
											}
											console.log(data)
											if(data.code == 200){
												data = data.data;
												if(data.href){
													window.location.href = data.href;
												}
												if(data.action){
													var tempForm = $('<form></form>');
													tempForm.attr('action',data.action);
													tempForm.attr('method','post');
													Object.keys(data.params).forEach(function(key){
														var input = $('<input/>');
														input.attr('type','hidden');
														input.attr('name',key);
														input.attr('value',data.params[key]);
														tempForm.append(input);
													})
													$("body").append(tempForm)
													tempForm.submit();
												}
											}else{
												tip.showTip(data.data.message);
											}
										}
									})
								}
							})
						}
					}else{
						if(tip){
							tip.showTip('请输入内容!')
						}
					}
				})

				if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){
					$("#back").click(function(event){
						event.preventDefault()
						window.history.go(-1)
					})

					$("#editor").css({
					    top: title.length>0?'2rem':'0',
					    'border-top': title.length>0?'1px solid #ccc':'0'
					})
					}else{//如果是pc端  主要!
						// tip.showTip('sb')
					}
				}
		})();

		(function(){//回复楼层editor
			var replys = $(".reply-bottom .emoticon,.footer .emoticon")
			replys.each(function(){
				var bar = $(this),
					editorFrame = bar.siblings('.reply-editor'),
					editor = new E('#'+bar.attr('id'),'#'+editorFrame.attr('id'))

				editor.customConfig.menus = [
				    'emoticon' //表情
				]

				editor.create()

				bar.siblings("button").click(function(){
					if(editor.txt.html() != '<p><br></p>'){
						tip.showTip('确认回复?',{
							sure: function(){
								var result = {value: editor.txt.getJSON()},
									sessionId = $("#session_id").val(),
									floorId = bar.siblings("[name='floor_id']").val();
								if(!!sessionId){
									result['session_id'] = sessionId;
								}
								if(!!floorId){
									result['floor_id'] = floorId;
								}
								$.ajax({
									url: '../controller/publish.php',
									type: 'POST',
									data: {body:result},
									//dataType: 'json',
									success: function(data){
										if(typeof data == 'string'){
											data = eval('('+data+')');
										}
										console.log(data)
										if(data.code == 200){
											data = data.data;
											if(data.href){
												window.location.href = data.href;
											}
											if(data.action){
												var tempForm = $('<form></form>');
												tempForm.attr('action',data.action);
												tempForm.attr('method','post');
												Object.keys(data.params).forEach(function(key){
													var input = $('<input/>');
													input.attr('type','hidden');
													input.attr('name',key);
													input.attr('value',data.params[key]);
													tempForm.append(input);
												})
												$("body").append(tempForm)
												tempForm.submit();
											}
										}else{
											tip.showTip(data.data.message);
										}
									}
								})
							}
						})
					}else{
						tip.showTip('请输入内容!')
					}
				})
			})
		})();
	}

	$("html,body").scrollTop(0)	
})