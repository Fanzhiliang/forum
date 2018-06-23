$(document).ready(function(){
var UploadHeadImg = function(){
	var tip = {};
	if(window.Utils){
		if(window.Utils.Tip){
			tip = new window.Utils.Tip()
		}
	}
	var isMobile = false
	if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){
		isMobile = true
	}
	//上传部分
	var wholeFrame = $("#image-frame"),
		uploadImage = $("#upload-image"),
		selecter = $("#selecter"),
		preview = $("#preview"),
		frameBgImg = $('#bg'),
		selecterBg = $("#selecter-bg"),
		previewImg = $("#preview-img"),
		loadImgCount = 3,
		startUpload = $("#start-upload"),
		saveAll = $("#save-all"),
		imageSrc = '',
		startX = 0.0,
		endX = 0.0,
		startY = 0.0,
		endY = 0.0,
		sex = $("#sex"),
		updateForm = $("#updateForm");

	//清空uploadImage
	uploadImage.val('')
	//性别选择
	$(".radio").click(function(){
		sex.val($(this).attr('value'))
		$(".radio.on").removeClass('on')
		$(this).addClass('on')
	})
	//保存所有
	$("#save,#save-all").click(function(event){
		event.preventDefault()
		console.log({
				'imageSrc': imageSrc,
				'startX': startX,
				'startY': startY,
				'endX': endX,
				'endY': endY,
				'session_id': $("#session_id").val(),
				'name': $("#name").val(),
				'sex': $("#sex").val()
			})
		$.ajax({
			url: '../controller/update_user.php',
			type: 'POST',
			data: {body:{
				'imageSrc': imageSrc,
				'startX': startX,
				'startY': startY,
				'endX': endX,
				'endY': endY,
				'session_id': $("#session_id").val(),
				'name': $("#name").val(),
				'sex': $("#sex").val()
			}},
			//dataType: 'json',
			success: function(data){
				if(typeof data == 'string'){
					data = eval('('+data+')');
				}
				console.log(data)
				if(data.code == 200){
					window.location.reload();
				}else{
					data = data.data;
					if(data.href){
						window.location.href = data.href+'?message='+data.message;
					}else{
						tip.showTip(data.message);
					}
				}
			}
		})
	})
	//点击原来头像开始上传
	startUpload.click(function(){
		uploadImage.val('')
		uploadImage.click()
		$("#save-head").css('display','')
	})
	//保存图片，把新头像图片地址，和头像剪切的坐标保存
	$("#save-head").click(function(event){
		event.preventDefault();
		var bgWidth = parseFloat(frameBgImg.css('width')),
			bgHeight = parseFloat(frameBgImg.css('height')),
			pos = selecter.position(),
			selecterSize = parseFloat(selecter.css('width'));
		//改变变量
		startX = pos.left/bgWidth;
		endX = (pos.left+selecterSize)/bgWidth;
		startY = pos.top/bgHeight;
		endY = (pos.top+selecterSize)/bgHeight;
		//改变样式
		$(this).css('display','none')
		wholeFrame.css('display','none')
		if(!isMobile){
			startUpload.css('display','none')
		}
		saveAll.css('display','')
	})
	//不保存图片
	$("#no-save-head").click(function(event){
		event.preventDefault()
		wholeFrame.css('display','none')
		preview.css('display','none')
		if(!isMobile){
			startUpload.css('display','block')
		}
		saveAll.css('display','')
		imageSrc = '';
		startX = '';
		endX = '';
		startY = '';
		endY = '';
	})
	//uploadImage内容发生改变，马上上传
	uploadImage.change(function(){
		var myForm = new FormData(),
			resetCss = function(obj){
				obj.css({//重置选择框
					width: '',
					height: '',
					left: '',
					top: ''
				})
			}
		loadImgCount = 3//每次改变需要剪裁的图片就重置loadImgCount
		resetCss(selecter)//重置选择框
		resetCss(selecterBg)//重置选择区图片
		resetCss(previewImg)//重置预览图片
		myForm.append('file',uploadImage[0].files[0])
		$.ajax({
			url: '../controller/upload.php?session_id='+$("#session_id").val(),
			type: 'POST',
			data: myForm,
			processData: false,
			contentType: false,
			//dataType: 'json',
			success: function(data){
				if(typeof data == 'string'){
					data = eval('('+data+')');
				}
				console.log(data)
				if(typeof data.data[0] == 'string'){
					if(isMobile){
						$(".sidebar").css('display','block')
					}
					imageSrc = data.data[0]
					frameBgImg.attr('src',imageSrc)
					selecterBg.attr('src',imageSrc)
					previewImg.attr('src',imageSrc)
					wholeFrame.css('display','block')
					preview.css('display','block')
					saveAll.css('display','none')

					if(isMobile){
						tip.showTip('移动端无缩放功能!');
					}
				}
			},
			fail: function(data){
				console.log(data)
			}
		})
	})
	//图片加载完成，调整样式，默认selecter在背景图片的中间
	$("img").load(function(){
		if(parseInt($(this).css('width'))>0 && parseInt($(this).css('height'))>0){
			--loadImgCount
		}
		if(loadImgCount==0){//三张图片加载完成
			var bgWidth = parseInt(frameBgImg.css('width')),
				bgHeight = parseInt(frameBgImg.css('height'))
			if(bgWidth>=bgHeight){
				selecter.css('width',bgHeight+'px')
				selecter.css('height',bgHeight+'px')
				previewImg.css('widht','')
				previewImg.css('height','100%')
				selecterBg.css('height',bgHeight+'px')
				if(bgWidth!=bgHeight){
					selecter.css('left',(bgWidth-bgHeight)/2+'px')
					selecterBg.css('left',-(bgWidth-bgHeight)/2+'px')
					previewImg.css('left',-(parseInt(previewImg.css('width'))-parseInt($(".preview-img-frame").css('width')))/2+'px')
				}
			}else{
				selecter.css('width',bgWidth+'px')
				selecter.css('height',bgWidth+'px')
				previewImg.css('width','100%')
				previewImg.css('height','')
				selecterBg.css('width',bgWidth+'px')
			}
			
		}
	})
	//能否移动和缩放的判断值
	var ableMove = false,
		ableScale = false
	//selecter缩放、移动时禁止滚动
	$(window).scroll(function(event){
		if(ableMove || ableScale){
			$(this).scrollTop(0)
		}
	})
	//selecter移动
	var recordX1 = 0,
		recordY1 = 0,
		cancelMove = function(event){
			event.preventDefault()
			ableMove = false
			recordX1 = 0
			recordY1 = 0
		},
		startMove = function(event){
			event.preventDefault()
			event.stopPropagation()
			ableMove = true
			ableScale = false
			recordX1 = isMobile?event.touches[0].clientX:event.clientX
			recordY1 = isMobile?event.touches[0].clientY:event.clientY
		},
		moveHandler = function(gapX,gapY){//移动方法，根据x、y偏移量移动。缩放时也要用
			var selecterSize = parseFloat(selecter.css('width')),
				selecterTop = parseFloat(selecter.css('top')),
				selecterLeft = parseFloat(selecter.css('left')),
				rate = parseFloat(previewImg.css('width'))/parseFloat(selecterBg.css('width')),
				leftLimit = parseFloat(selecterBg.css('width')) - parseFloat(selecter.css('width')),
				topLimit = parseFloat(selecterBg.css('height')) - parseFloat(selecter.css('height')),
				selecterBgTop = parseFloat(selecterBg.css('top')),
				selecterBgLeft = parseFloat(selecterBg.css('left')),
				previewImgTop = parseFloat(previewImg.css('top')),
				previewImgLeft = parseFloat(previewImg.css('left')),
				selecterNewTop = selecterTop+gapY,
				selecterNewLeft = selecterLeft+gapX

			if(selecterNewTop>0 && selecterNewTop<topLimit){
				selecter.css('top',selecterNewTop+'px')
				selecterBg.css('top',parseFloat(selecterBgTop-gapY)+'px')
				previewImg.css('top',parseFloat(previewImgTop-gapY*rate)+'px')
			}else{
				if(selecterNewTop<=0){
					selecter.css('top',0)
					selecterBg.css('top',0)
					previewImg.css('top',0)
				}else if(selecterNewTop>=topLimit){
					selecter.css('top',topLimit)
					selecterBg.css('top',-topLimit)
					previewImg.css('top',-topLimit*rate)
					// previewImg.css('top',-(parseFloat(previewImg.css('height'))-70))
				}
			}

			if(selecterNewLeft>0 && selecterNewLeft<leftLimit){
				selecter.css('left',selecterNewLeft+'px')
				selecterBg.css('left',parseFloat(selecterBgLeft-gapX)+'px')
				previewImg.css('left',parseFloat(previewImgLeft-gapX*rate)+'px')
			}else{
				if(selecterNewLeft<=0){
					selecter.css('left',0)
					selecterBg.css('left',0)
					previewImg.css('left',0)
				}else if(selecterNewLeft>=leftLimit){
					selecter.css('left',leftLimit)
					selecterBg.css('left',-leftLimit)
					previewImg.css('left',-leftLimit*rate)
				}
			}
		}
	if(isMobile){
		selecter[0].addEventListener('touchstart',startMove)
		selecter[0].addEventListener('touchend', cancelMove)
		wholeFrame[0].addEventListener('touchmove', function(event){
			if(ableMove){
				var clientX = isMobile?event.touches[0].clientX:event.clientX,
					clientY = isMobile?event.touches[0].clientY:event.clientY,
					gapX = parseFloat(clientX - recordX1),
					gapY = parseFloat(clientY - recordY1)
				moveHandler(gapX,gapY)
				recordY1 = clientY
				recordX1 = clientX
			}
		})
	}else{
		selecter.mousedown(startMove)
		selecter.mouseup(cancelMove)
		wholeFrame.mouseleave(cancelMove)
		wholeFrame.mousemove(function(event){//为了计算精准使用parseFloat
			if(ableMove){
				var clientX = isMobile?event.touches[0].clientX:event.clientX,
					clientY = isMobile?event.touches[0].clientY:event.clientY,
					gapX = parseFloat(clientX - recordX1),
					gapY = parseFloat(clientY - recordY1)
				moveHandler(gapX,gapY)
				recordY1 = clientY
				recordX1 = clientX
			}
		})
	}
	//selecter缩放
	var points = selecter.find(".point"),
		recordX2 = 0,
		recordY2 = 0,
		cancelScale = function(event){
			event.preventDefault()
			ableScale = false
			recordX2 = 0
			recordY2 = 0
			point = {}
		},
		startScale = function(event){
			event.preventDefault()
			event.stopPropagation()
			ableMove = false
			ableScale = true
			recordX2 = parseFloat(isMobile?event.touches[0].clientX:event.clientX)
			recordY2 = parseFloat(isMobile?event.touches[0].clientY:event.clientY)
			point = $(this)
		},
		scaleHandler = function(event){
			event.stopPropagation()
			if(ableScale){
				var clientX = parseFloat(isMobile?event.touches[0].clientX:event.clientX),
					clientY = parseFloat(isMobile?event.touches[0].clientY:event.clientY),
					selecterPos = selecter.position(),
					bgWidth = parseFloat(selecterBg.css('width')),
					bgHeight = parseFloat(selecterBg.css('height')),
					gapX = parseFloat(clientX-recordX2),
					gapY = parseFloat(clientY-recordY2),
					widthLimit = bgWidth-parseFloat(selecterPos.left),
					heightLimit = bgHeight-parseFloat(selecterPos.top),
					selecterSize = parseFloat(selecter.css('width')),
					selecterNewSize = 0,
					maxGap = 0,
					bgLimit = bgHeight,
					successHandler = {},
					rateWH = bgWidth / bgHeight,
					previewImgWidth = parseFloat(previewImg.css('width')),
					rateWidth = parseFloat(previewImgWidth / bgWidth),
					rateSize = parseFloat(parseFloat(selecter.css('width')) / bgWidth),
					previewImgHeight = parseFloat(previewImg.css('height')),
					previewImgTop = parseFloat(previewImg.css('top')),
					previewImgleft = parseFloat(previewImg.css('left')),
					previewImgNewWidth = parseFloat($(".preview-img-frame").css('width'))/rateSize,
					previewImgNewHeight = previewImgNewWidth/rateWH

				if(point){
					if(point.hasClass('tl')){
						maxGap = -gapY
						if(maxGap>0){
							successHandler = function(){moveHandler(-Math.abs(gapX),-Math.abs(gapY))}
						}else if(maxGap<0){
							successHandler = function(){moveHandler(Math.abs(gapX),Math.abs(gapY))}
						}
					}else if(point.hasClass('tc')){
						maxGap = -gapY
						if(maxGap>0){
							successHandler = function(){moveHandler(0,-Math.abs(gapY))}
						}else{
							successHandler = function(){moveHandler(0,Math.abs(gapY))}
						}
					}else if(point.hasClass('tr')){
						maxGap = -gapY
						if(maxGap>0){
							successHandler = function(){moveHandler(0,-Math.abs(gapY))}
						}else{
							successHandler = function(){moveHandler(0,Math.abs(gapY))}
						}
					}else if(point.hasClass('cl')){
						maxGap = -gapX
						bgLimit = widthLimit<=heightLimit ? widthLimit : heightLimit
						if(maxGap>0){
							successHandler = function(){moveHandler(-Math.abs(gapX),0)}
						}else{
							successHandler = function(){moveHandler(Math.abs(gapX),0)}
						}
					}else if(point.hasClass('cr')){
						maxGap = gapX
						bgLimit = widthLimit<=heightLimit ? widthLimit : heightLimit
					}else if(point.hasClass('dl')){
						maxGap = -gapX
						bgLimit = widthLimit<=heightLimit ? widthLimit : heightLimit
						if(maxGap>0){
							successHandler = function(){moveHandler(-Math.abs(gapX),0)}
						}else{
							successHandler = function(){moveHandler(Math.abs(gapX),0)}
						}
					}else if(point.hasClass('dc')){
						maxGap = gapY
						bgLimit = widthLimit<=heightLimit ? widthLimit : heightLimit
					}else if(point.hasClass('dr')){
						maxGap = gapY
						bgLimit = widthLimit<=heightLimit ? widthLimit : heightLimit
					}
				}
				selecterNewSize = selecterSize + maxGap

				if(selecterNewSize>=0 && selecterNewSize<=bgLimit){
					selecter.css('width',selecterNewSize+'px')
					selecter.css('height',selecterNewSize+'px')
					previewImg.css('width',previewImgNewWidth+'px')
					previewImg.css('height',previewImgNewHeight+'px')
					previewImg.css('top','-'+parseFloat(selecterPos.top)*rateWidth+'px')
					previewImg.css('left','-'+parseFloat(selecterPos.left)*rateWidth+'px')

					if(typeof successHandler == 'function'){
						successHandler()
					}
				}else{
					if(selecterNewSize<0){
						selecter.css('width','1px')
						selecter.css('height','1px')
					}else if(selecterNewSize>bgLimit){
						selecter.css('width',selecterSize+'px')
						selecter.css('height',selecterSize+'px')
					}
				}
				recordX2 = clientX
				recordY2 = clientY
			}
		},
		point = {}

	if(isMobile){
		points.each(function(){
			this.addEventListener('touchstart', startScale)
		})
		wholeFrame[0].addEventListener('touchend', cancelScale)
		wholeFrame[0].addEventListener('touchmove', scaleHandler)
	}else{
		points.mousedown(startScale)
		wholeFrame.mouseup(cancelScale)
		wholeFrame.mouseleave(cancelScale)
		wholeFrame.mousemove(scaleHandler)
	}
}

if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){
	$("#back").click(function(event){
		event.preventDefault()
		window.history.go(-1)
	})

	var sex = $("#sex"),
		sexResult = $("#sex-result"),
		sexList = $("#sex-list"),
		sexListItems = sexList.find('span'),
		isSexShow = false,
		oldResult = sexResult.html(),
		oldValue = sex.val(),
		listShow = function(){
			sexList.css({display:'block'})
			isSexShow = true
		},
		listHidden = function(){
			sexList.css({display:'none'})
			isSexShow = false
		}

	sexResult.click(function(event){
		listShow()
		event.stopPropagation()
	})

	sexListItems.click(function(event){
		sexResult.html($(this).html())
		sex.val($(this).attr('value'))
		listHidden()
		event.stopPropagation()
	})

	$(window).click(function(){
		if(isSexShow){
			listHidden()
		}
	})
	new UploadHeadImg()
}else{//如果是pc端  主要!
	new UploadHeadImg()
}
})