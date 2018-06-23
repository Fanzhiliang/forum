(function(window,document){
	var Tip = function(){
		var isMobile = false
		if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){
			isMobile = true
		}
		if($("#tip").length==0){
			if(isMobile){
				$("head").append('<style>#tip {width: 100%;height: 100%;position: fixed;top: 0;left: 0;z-index: 999999;text-align: center;display: none;}#tip .tip-body {width: 0;/*width: 60%;*/height: 0;/*height: 20%;*/padding: 0;/*padding: 10%;*/display: inline-block;background-color: #fff;position: relative;top: 25%;border-radius: 5px;box-shadow: 0px 0px 5px #888888;font-size: 0.9rem;}#tip .tip-main{word-wrap: break-word;}#tip .tip-btns {width: 100%;height: 20%;position: absolute;left: 0;bottom: 0;}#tip .tip-btns button {color: #fff;border: 0;border-radius: 5px;padding: 4px 15px;cursor: pointer;}#tip .sure {background-color: rgb(18, 150, 219);margin-right: 10%;}#tip .cancel {background-color: rgb(204, 50, 47);margin-left: 10%;}#tip .tip-body>* {opacity: 0;}</style>')
			}else{
				$("head").append('<style>#tip {display: none;position: fixed;width: 100%;height: 100%;z-index: 999999;}#tip .tip-body {width: 0;/*width: 240px;*/height: 0;/*height: 140px;*/padding: 0;/*padding: 30px;*/position: absolute;top: 50%;left: 50%;transform: translateX(-50%) translateY(-50%);background-color: #fff;border-radius: 5px;box-shadow: 0 0 5px #888888;}#tip .tip-main{padding: 20px 10px;font-size: 16px;text-align: center;height: 100px;word-wrap: break-word;}#tip .tip-btns {position: absolute;width: 100%;height: 30px;left: 0;bottom: 10px;text-align: center;}#tip .tip-btns button {color: #fff;border: 0;border-radius: 5px;padding: 4px 15px;cursor: pointer;}#tip .sure {background-color: rgb(18, 150, 219);margin-right: 10%;}#tip .cancel {background-color: rgb(204, 50, 47);margin-left: 10%;}#tip .tip-body>* {opacity: 0;}</style>')
			}
			$("body").append('<div id="tip"><div class="tip-body"><div class="tip-main">提示内容</div><div class="tip-btns"><button class="sure">确认</button><button class="cancel">取消</button></div></div></div>')
		}
			
		//保证前面执行完
		setTimeout(function(){},0)
		var tip = $("#tip"),
			tipBody = tip.find(".tip-body"),
			tipItem = tip.find(".tip-body>*"),
			tipMain = tip.find(".tip-body .tip-main"),
			sureBtn = tip.find(".sure"),
			cancelBtn = tip.find(".cancel"),
			handler = {},
			showTip = function(message,obj){
				if(typeof message != 'string'){
					return false
				}
				if(obj){
					handler = {
						sure: typeof obj.sure != 'undefined' ? obj.sure : {},
						cancel: typeof obj.cancel != 'undefined' ? obj.cancel : {}
					}
				}
				tipMain.text(message)
				tip.css({display:'block'})
				if(isMobile){
					tipBody.animate({
						width:'60%',
						height:'20%',
						padding:'10%'
					},function(){
						tipItem.css({opacity:1})
					})
				}else{
					tipBody.animate({
						width:'240px',
						height:'140px',
						padding:'30px'
					},function(){
						tipItem.css({opacity:1})
					})
				}
			},
			hideTip = function(){
				tip.css({display:'none'})
				tipBody.css({width:0,height:0,padding:0})
				tipItem.css({opacity:0})
			}
			
		sureBtn.click(function(){
			if(typeof handler.sure == 'function'){
				handler.sure()
			}
			handler = {}
			hideTip()
		})
		cancelBtn.click(function(){
			if(typeof handler.cancel == 'function'){
				handler.cancel()
			}
			handler = {}
			hideTip()
		})
		this.showTip = showTip
		this.hideTip = hideTip
	}

	if(typeof window.Utils == 'undefined'){
		window.Utils = {}
	}
	window.Utils.Tip = Tip
})(window,window.document)