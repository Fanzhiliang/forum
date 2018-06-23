$(document).ready(function(){
//点击回复跳转到贴子相应的楼层
var postToPostings = $("#postToPostings")
$(".reply-list").delegate(".replys a","click",function(event){
	event.preventDefault();
	var attr = $(this).attr('tag'),
		params = attr.split('&');
	if(attr.length>0 && params.length>0){
		$.ajax({
			url: '../controller/getPageNoByFloorNo.php',
			type: 'POST',
			data: {body:{
				'postings_id': params[0],
				'floor_no': params[1]
			}},
			//dataType: 'json',
			success: function(data){
				if(typeof data == 'string'){
					data = eval('('+data+')');
				}
				console.log(data)
				if(data.code == 200){
					data = data.data;
					postToPostings.attr('action','/postings/'+params[0]);
					postToPostings.find("[name='floor_no']").val(params[1]);
					postToPostings.find("[name='pageNo']").val(data.pageNo);
					postToPostings.submit();
				}else{
					tip.showTip(data.data.message);
				}
			}
		})
	}
})

if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){
	$("#back").click(function(event){
		event.preventDefault()
		window.history.go(-1)
	})

	if(window.Utils){
		if(window.Utils.Slider){
			var resetHeight = function(){
				var postingsList = $(".postings-list"),
					replyList = $(".reply-list"),
					keepList = $(".keep-list");

				postingsList.css('height','')
				replyList.css('height','')
				keepList.css('height','')

				$(".slider-trig>span").each(function(i){
					if($(this).hasClass('on')){
						switch (i) {
							case 0:
								replyList.css('height',postingsList.css('height'))
								keepList.css('height',postingsList.css('height'))
								break;
							case 1:
								postingsList.css('height',replyList.css('height'))
								keepList.css('height',replyList.css('height'))
								break;
							case 2:
								postingsList.css('height',keepList.css('height'))
								replyList.css('height',keepList.css('height'))
								break;
						}
					}
				})
			}

			new window.Utils.Slider('list-scroll','click',undefined,undefined,resetHeight)

			setTimeout(resetHeight(),666);
		}
		if(window.Utils.Tip){
			var tip = new window.Utils.Tip()
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
			var rate = (molecular/denominator)*100
			if(!isNaN(rate)){
				creditsMove.animate({'width':rate+'%'})
			}
		}
	}
	setCredits()

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

	var footer = $(".footer");
	$(document).scroll(function(){
		if($(this).scrollTop() != 0){
			footer.show();
		}else{
			footer.hide();
		}
	})

	footer.click(function(){
		$("html,body").animate({scrollTop: 0})
		footer.hide();
	})

	$(".getMore").click(function(event){
		var text = $(this).find(".text"),
			loading = $(this).find(".loading"),
			attr = $(this).attr('tag'),
			params = attr.split('&'),
			isLoading = false;
		if(attr.length>0 && params.length>0){
			event.preventDefault();
			if(!isLoading){
				text.css('display','none');
				loading.css('display','inline');
				isLoading = true;
				var that = $(this);
				$.ajax({
					url: '../controller/getMoreList.php',
					type: 'POST',
					data: {body:{
						'session_id': $("#session_id").val(),
						type: params[0],
						pageNo: params[1]
					}},
					success: function(data){
						if(typeof data == 'string'){
							data = eval("("+data+")");
						}
						console.log(data);
						if(data.code == 200){
							data = data.data;
							switch (params[0]) {
								case 'myPostings':
								case 'myKeep':
									for(var i=0,len=data['list'].length;i<len;++i){
										var obj = data['list'][i];
										that.before('<a href="/postings/'+obj['postings_id']+'" class="postings"><div class="title">'+obj['title']+'</div><div class="time">'+obj['time']+'</div><div class="reply-col"><span class="reply-count">'+obj['reply_count']+'</span><img src="'+data['prefix']+'/static/img/reply.svg" alt=""></div></a>');
									}
									break;
								case 'myReply':
									for(var i=0,len=data['list'].length;i<len;++i){
										var obj = data['list'][i],
											str = '<div class="replys">';
										if(obj['type'] == 'floor'){
											var post = obj['post'];
											str+='<a href="/postings/'+obj['postings_id']+'" tag="'+obj['postings_id']+'&1" class="post"><span>原帖: </span><span>'+post['title']+'</span></a><a href="/postings/'+obj['postings_id']+'" tag="'+obj['postings_id']+'&'+obj['floor_no']+'" class="you-reply"><span>你的楼层: </span>'+obj['value']+'</a>';
										}else if(obj['type'] == 'reply'){
											var floor = obj['floor'];
											str+='<a href="/postings/'+obj['postings_id']+'" tag="'+obj['postings_id']+'&'+floor['floor_no']+'" class="post"><span>楼层: </span><span>'+floor['value']+'</span></a><a href="/postings/'+obj['postings_id']+'" tag="'+obj['postings_id']+'&'+floor['floor_no']+'" class="you-reply"><span>你的回复: </span>'+obj['value']+'</a>';
										}
										str+='<div class="reply-you"><span class="time">'+obj['time']+'</span></div></div>'
										that.before(str);
									}
									break;
							}
							that.attr('tag',params[0]+'&'+(parseInt(data['pageNo'])+1));
						}else{
							tip.showTip(data.data.message);
						}
						text.css('display','inline');
						loading.css('display','none');
						isLoading = false;
					}
				})
			}
		}
	})

}else{//如果是pc端  主要!
	var	mainPrimary = $(".main-primary .body"),
		topLimit = parseInt(mainPrimary.offset().top),
		sidebar = $(".sidebar"),
		mainPrimaryLeft = parseInt(mainPrimary.offset().left),
		sidebarLeft = mainPrimaryLeft+parseInt(mainPrimary.css('width'))

	$(window).resize(function(){//修改浏览器大小是重新获值
		mainPrimaryLeft = parseInt(mainPrimary.offset().left)
		sidebarLeft = mainPrimaryLeft+parseInt(mainPrimary.css('width'))
	})

	$(document).scroll(function(){
		if(parseInt($(this).scrollTop()) >= topLimit){
			sidebar.css({
				position: 'fixed',
				top: 0,
				left: sidebarLeft + 'px'
			})
		}else{
			sidebar.css({position:'',top:'',left:''})
		}
	})

	var nav = $(".body .nav"),
		navItem = $(".body .nav li"),
		moveObj = $(".nav .move-obj"),
		navItemOn = $(".body .nav li.on"),
		clearMoveClass = function(){
			for(var j in navItem){
				moveObj.removeClass('move-'+j)
			}
		};
	moveObj.hide();

	navItem.each(function(i){
		$(this).attr('index',i)
		$(this).mouseenter(function(){
			clearMoveClass()
			moveObj.addClass('move-'+i)
		})
	})

	var index = navItemOn.attr('index')

	nav.hover(function(){
		moveObj.show();
		navItemOn.removeClass('on')
	},function(){
		clearMoveClass()
		moveObj.addClass('move-'+index)
		setTimeout(function(){
			navItemOn.addClass('on')
		}, 200)
	})

	//分页跳转
	var totalPage = parseInt($(".pager .rear").attr('href')),
		myForm = $("#myForm"),
		pageNo = $("#pageNo");
	if(myForm && pageNo && !isNaN(totalPage) && totalPage>0){
		$(".pager a").click(function(event){
			event.preventDefault();
			var href = parseInt($(this).attr('href'));
			if(!isNaN(href) && href >=1 && href <= totalPage){
				pageNo.val(href);
				myForm.submit();
			}
		})
	}
}
})