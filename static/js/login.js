$(document).ready(function(){
	$("input").focus(function(){
		$(this).parent().addClass('selected')
		$(this).parent().removeClass('warn')
	})
	$("input").blur(function(){
		$(this).parent().removeClass('selected')
		$(this).parent().removeClass('warn')
	})
	$("form").submit(function(event){
		var result = window.Utils.formUtils.checkFields($(this))
		if(result.message != 'success'){
			event.preventDefault()
			$(".error-tip").text(result.message)
			result.field.parent().addClass('warn')
		}
	})
	$("#name,#email").bind("focus input propertychange change",function(event){
		var that = $(this),
			value = $.trim(that.val()),
			url = that.attr('id')=='name'?'../controller/isExistName.php':'../controller/isExistEmail.php';
        if(value.length > 0){
        	$.ajax({
        		url: url,
        		type: 'POST',
        		data: {body:{value:value}},
        		success: function(data){
        			if(typeof data == 'string'){
        				data = eval('('+data+')');
        			}
        			if(data.code == 200){
        				$(".error-tip").text('')
						that.parent().removeClass('warn')
        			}else{
        				$(".error-tip").text(data.data.message)
						that.parent().addClass('warn')
        			}
        		}
        	})
        }
	});
	//滑块验证
	var initX = 0,
		isfinal = false,
		dragObj = $(".drag-obj"),
		dragBg = $(".drag-bg"),
		drag = $(".drag"),
		dragText = $(".drag-text"),
		test = $('#test'),
		startHandler = function(event){
			initX = event.touches&&event.touches.length>0? event.touches[0].clientX : event.clientX
		},
		moveHandler = function(event){
			if(!isfinal && initX!=0){
				var currX = event.touches&&event.touches.length>0?event.touches[0].clientX:event.clientX,
					dist = currX - initX,
					dragObjX = parseInt(dragObj.css('left')),
					dragBgWidth = parseInt(dragBg.css('width')),
					dragObjNew = dragObjX+dist,
					dragBgNew = dragBgWidth+dist
				if(dragBgNew+parseInt(dragObj.css('width'))>=parseInt(drag.css('width'))){
					dragObj.css({
						left : '',
						right : '0',
						marginLeft : '0',
						marginRight : '-1px'
					})
					dragObj.css({})
					dragBg.css({width : '100%'})
					isfinal = true
					test.val('true')
					dragObj.find('img').attr('src','static/img/ok.svg')
					dragText.find('span').text('验证通过')
					dragText.find('span').css({color:'#fff'})
					dragText.css({zIndex:9})
				}else if(dragObjNew>=0 && dragBgNew>=0){
					dragObj.css({left : dragObjNew+'px'})
					dragBg.css({width : dragBgNew+'px'})
					initX = currX
				}
			}
		},
		endHandler = function(){
			if(!isfinal){
				dragObj.animate({left : ''})
				dragBg.animate({width : ''})
				initX = 0
			}
		},
		resetHandler = function(){
			$(this).removeClass('selected')
			$(this).removeClass('warn')
		};
	dragBg.css('width','0px');
	dragObj.css('left','0px');

	dragObj.find('*').mousedown(function(e){e.preventDefault()})

	dragObj[0].ontouchstart = startHandler
	dragObj[0].ontouchmove = moveHandler
	dragObj[0].ontouchend = endHandler
	drag[0].ontouchstart = resetHandler

	dragObj.mousedown(startHandler)
	$(".input-frame").mousemove(moveHandler)
	dragObj.mouseup(endHandler)
	drag.mousedown(resetHandler)
})