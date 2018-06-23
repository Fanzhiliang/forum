$(document).ready(function(){
	var TitleTip = function(){
		if($("#titleTip").length==0){
			$("head").append('<style>#titleTip{display: none;position: absolute;z-index: 999;background-color: rgb(0,0,0);background-color: rgb(0,0,0,0.5);top: 0;left: 0;padding: 2px 6px 4px 6px;border-radius: 5px;}#titleTip .value{display: inline-block;color: #fff;font-size: 12px;}#titleTip .triangle{width: 0;height: 0;border: 5px solid transparent;position: absolute;border-top-color: rgb(0,0,0);border-top-color: rgb(0,0,0,0.5);left: 50%;bottom: -10px;transform: translateX(-50%);}<style>')
			$("body").append('<div id="titleTip"><div class="value">用户中心</div><div class="triangle"></div></div>')
		}
		setTimeout(function(){}, 0)

		var titleTip = $("#titleTip"),
			value = $("#titleTip .value"),
			hide = function(){
				titleTip.css({display:'none'})
			}

		$("body").delegate("*","mouseenter",function(event){
			var title = $(event.target).attr('titleTip')
			if(typeof title != 'undefined' && title.length>0){
				value.text(title)
				var target = $(event.target),
					offset = target.offset(),
					titleTipWidth = parseInt(titleTip.css('width')),
					titleTipHeight = parseInt(titleTip.css('height')),
					targetWidth = parseInt(target.css('width'))
				
				titleTip.css({
					display:'inline-block',
					top: (parseInt(offset.top)-titleTipHeight-15) + 'px',
					left: (parseInt(offset.left)-titleTipWidth/2+targetWidth/4)+'px'
				})
			}
		})

		$("body").delegate("*","mouseout",function(event){
			var title = $(event.target).attr('titleTip')
			if(typeof title != 'undefined' && title.length>0){
				hide()
			}
		})

		this.hide = hide
	}

	if(typeof window.Utils == 'undefined'){
		window.Utils = {}
	}
	window.Utils.TitleTip = TitleTip
})