$(document).ready(function(){
	var titleTip = {},
		tip = {}

	if(window.Utils){
		if(window.Utils.TitleTip){
			titleTip = new window.Utils.TitleTip()
		}
		if(window.Utils.Tip){
			tip = new window.Utils.Tip()
		}
	}

	var setCredits = function(isLevelUp){
			var creditsMove = $(".credits-rate .credits-move"),
				molecular = parseInt($(".credits-rate .molecular").text()),
				denominator = parseInt($(".credits-rate .denominator").text())
			if(isLevelUp){
				creditsMove.css({width:0})
			}
			if(!isNaN(molecular) && !isNaN(denominator)){
				if(molecular<=denominator){
					var rate = (molecular/denominator)*100
					if(!isNaN(rate)){
						creditsMove.animate({width:rate+'%'})
					}
				}
			}
		}
	setCredits()

	var	backTop = $("#back-top"),
		backTopHandler = function(){
			if(titleTip){
				titleTip.hide()
			}
			$("html,body").animate({scrollTop: 0})
		},
		backBottom = $("#back-bottom"),
		backBottomHandler = function(){
			if(titleTip){
				titleTip.hide()
			}
			$("html,body").animate({
				scrollTop: $("#editor").offset().top + 'px'
			})
		}


	$("#sign-in").click(function(event){
		event.preventDefault();
		var that = $(this);
		$.ajax({
			url: '../controller/sign_in.php',
			type: 'POST',
			data: {body:{
				'session_id': $("#session_id").val()
			}},
			//dataType: 'json',
			success: function(data){
				if(typeof data == 'string'){
					data = eval('('+data+')');
				}
				console.log(data);
				if(data.code==200){
					data = data.data;
					var levelImg = $(".name .level img"),
						oldLevel = parseInt(levelImg.attr('level')),
						newLevel = data.level;
					$(".credits-rate .molecular").text(data.credits);
					$(".credits-rate .denominator").text(data['max_credits']);
					that.after('<div class="sign-in final">已签到</div>');
					that.remove();
					if(oldLevel < newLevel){
						setCredits(true);
						levelImg.attr('src',levelImg.attr('src').replace(oldLevel+'',newLevel+''));
						levelImg.attr('level',newLevel);
					}else{
						setCredits();
					}
				}else{
					tip.showTip(data.data.message);
				}
			},
			fail: function(){
				tip.showTip('连接超时');
			}
		})
	})

	$("#logout").click(function(event){
		event.preventDefault();
		tip.showTip('确定注销吗?',{
			sure: function(){
				$.ajax({
					url: '../controller/logout.php',
					type: 'POST',
					data: {body:{
						'session_id': $("#session_id").val()
					}},
					//dataType: 'json',
					success: function(data){
						if(typeof data == 'string'){
							data = eval('('+data+')');
						}
						console.log(data);
						if(data && data.code==200){
							data = data.data;
							window.location.href = data.href;
						}else{
							tip.showTip(data.data.message);
						}
					},
					fail: function(){
						tip.showTip('连接超时');
					}
				})
			}
		})
	})


	backTopHandler()
	backTop.click(backTopHandler)
	backBottom.click(backBottomHandler)
})