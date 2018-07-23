$(document).ready(function(){
//收藏、取消收藏
$("#keep").click(function(event){
	var sessionId = $("#session_id").val(),
		userId = $("#user_id").val(),
		postingsId = $("#postings_id").val(),
		keepId = $("#keep_id").val();
	event.preventDefault();
	result = {};
	if($.trim(sessionId).length>0){
		result['session_id'] = sessionId;
	}
	if($.trim(userId).length>0){
		result['user_id'] = userId;
	}
	if($.trim(postingsId).length>0){
		result['postings_id'] = postingsId;
	}
	if($.trim(keepId).length>0){
		result['keep_id'] = keepId;
	}
	var that = $(this);
	$.ajax({
		url: '../controller/keep.php',
		type: 'POST',
		data: {body:result},
		//dataType: 'json',
		success: function(data){
			if(typeof data == 'string'){
				data = eval('('+data+')');
			}
			console.log(data)
			if(data.code==200){
				data = data.data;
				if(data.toggle){
					var keepImg = that.find('img'),
						keepImgSrc = keepImg.attr('src'),
						toggle = data.toggle.split('=>')
					//更换图片
					keepImg.attr('src',keepImgSrc.replace(toggle[0],toggle[1]));
					//删除input
					that.find('input').remove();
					//重新添加input
					var keys = ['user_id','postings_id','keep_id'];
					for(var i=0;i<keys.length;++i){
						var key = keys[i];
						if(data[key]){
							that.append('<input type="hidden" id="'+key+'" value="'+data[key]+'">');
						}
					}
				}
			}else{
				tip.showTip(data.data.message);
			}
		}
	})
})
//删除楼层,贴子
$(".delete-floor,.delete-postings").click(function(event){
	event.preventDefault();
	var that = $(this);
	tip.showTip('确认删除?',{
		sure: function(){
			var result = {'session_id': $("#session_id").val()}
			if(that.hasClass('delete-floor')){
				result['floor_id'] = that.attr('href');
			}else if(that.hasClass('delete-postings')){
				result['postings_id'] = that.attr('href');
			}
			$.ajax({
				url: '../controller/delete.php',
				type: 'POST',
				data: {body:result},
				//dataType: 'json',
				success: function(data){
					console.log(data)
					if(typeof data == 'string'){
						data = eval('('+data+')');
					}
					console.log(data)
					if(data.code == 200){
						data = data.data;
						if(that.hasClass('delete-floor')){
							window.location.reload();
						}else if(that.hasClass('delete-postings')){
							window.location.href = '/';
						}
					}else{
						tip.showTip(data.data.message);
					}
				}
			})
		}
	})	
})

//楼层分页跳转
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

//删除回复
$('body').delegate(".delete-reply",'click',function(event){
	event.preventDefault();
	var that = $(this);
	tip.showTip('确认删除?',{
		sure: function(){
			$.ajax({
				url: '../controller/delete.php',
				type: 'POST',
				data: {body:{
					'session_id': $("#session_id").val(),
					'reply_id': that.attr('href')
				}},
				//dataType: 'json',
				success: function(data){
					if(typeof data == 'string'){
						data = eval('('+data+')');
					}
					console.log(data)
					if(data.code == 200){
						data = data.data;
						window.location.reload();
					}else{
						tip.showTip(data.data.message);
					}
				}
			})
		}
	})	
})

setTimeout(function(){
	//跳转楼层
	var floorNo = parseInt($("#floor_no").val());
	if(floorNo>0){
		var floor = $("#floor-"+floorNo),
			extraHeight = parseInt($(".body>.title").css('height'))+parseInt($(".body>.title").css('padding-top'))*2;
		if(floor.length>0){
			$("body,html").scrollTop(floor.offset().top-extraHeight);//跳转到回复的楼层
			//展开回复
			// var toggleReply = floor.find(".toggle-reply"),
			// 	replyList = floor.find(".replys .reply");
			// if(replyList.length > 0){
			// 	toggleReply.click();
			// }
			floor.find(".toggle-reply").click();
		}
	}
},666);

//每个回复中如果p只要一个css为inline-block  多于1则为block
$(".reply,.reply-row").each(function(){
	var ps = $(this).find("p");
	if(ps.length==1){
		ps.css('display','inline-block');
	}else{
		ps.css('display','block');
	}
})

if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){
	var rem = parseInt($("html").css('font-size')),
		imgs = $("p img"),
		tip = {}

	if(window.Utils){
		if(window.Utils.Tip){
			tip = new window.Utils.Tip()
		}
	}	

	$("#back").click(function(event){
		event.preventDefault()
		window.history.go(-1)
	})

	$(".toggle-reply").each(function(){
		(function(that){
			var frame = that.parents('.reply-frame'),
				frameHeight = frame.css("height"),
				replyFrame = that.parents('.reply-frame'),
				thatSiblings = that.siblings();

			frame.css({height:'1rem'})

			that.click(function(event){
				event.preventDefault() 
				replyFrame.animate({height:frameHeight})
				thatSiblings.siblings().css({display:'block'})
				$(this).remove();
			})
		})($(this))
	})

	var href = window.location.href,//   http://localhost/bilibili/index.html
		pathName = window.location.pathname,//   /bilibili/index.html
		domain = href.substring(0, href.indexOf(pathName)+1);//   http://localhost/

	setTimeout(function(){
		imgs.each(function(){
			if(parseInt($(this).css('width')) >= parseInt($('.body .down-row').css('width'))*(9/10)){
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

			$(this).click(function(){
				var filename = $(this).attr('src')
					filename = filename.substring(filename.lastIndexOf('/')+1,filename.length)
				window.location.href = domain+'showImage?filename='+filename
			})
		})
		//跳转楼层
		var floorNo = parseInt($("#floor_no").val());
		if(floorNo>0){
			var floor = $("#floor-"+floorNo),
				mainMarginTop = parseInt($(".main").css('margin-top'));
			if(floor.length>0){
				$("body,html").scrollTop(floor.offset().top-mainMarginTop);
			}
		}
	},666);

	var totalPage = parseInt($(".footer .totalPage").text()),
		pageNo = $("#pageNo"),
		pageForm = $("#pageForm");

	$(".footer .prev a").click(function(event){
		event.preventDefault();
		if(!isNaN(totalPage) && totalPage>0){
			var newPageNo = parseInt(pageNo.val())-1;
			if(newPageNo>0 && newPageNo<=totalPage){
				pageNo.val(newPageNo);
				pageForm.submit();
			}
		}
	})

	$(".footer .next a").click(function(event){
		event.preventDefault();
		if(!isNaN(totalPage) && totalPage>0){
			var newPageNo = parseInt(pageNo.val())+1;
			if(newPageNo>0 && newPageNo<=totalPage){
				pageNo.val(newPageNo);
				pageForm.submit();
			}
		}
	})

	$(".ctrl-nav").each(function(){
		var childLen = $(this).find('li').length;
		$(this).css('width',(childLen*4)+'rem');
	})

	$(".more").click(function(event){
		event.stopPropagation();
		var ctrlNav = $(this).siblings('.ctrl-nav'),
			currDisplay = ctrlNav.css('display');
		if(currDisplay == 'none'){
			ctrlNav.css('display','block');
		}else{
			ctrlNav.css('display','none');
		}
	})

	$(document).click(function(){
		$(".ctrl-nav").css('display','none');
	})

	$(".reply-frame").each(function(){
		if($(this).children().length<1){
			$(this).css('height','1rem');
		}
	})
}else{//如果是pc端  主要!
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
	$("html,body").scrollTop(0);

	var	mainPrimary = $(".main-primary .body"),
		topLimit = parseInt(mainPrimary.offset().top),
		sidebar = $(".sidebar"),
		mainPrimaryLeft = parseInt(mainPrimary.offset().left),
		sidebarLeft = mainPrimaryLeft+parseInt(mainPrimary.css('width')),
		title = $(".body .title")

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
			title.css({
				position: 'fixed',
				top: 0,
				left: mainPrimaryLeft + 'px',
				'border-left-color': 'rgb(225,230,235)' 
			})
		}else{
			sidebar.css({position:'',top:'',left:''})
			title.css({position:'',top:'',left:'','border-left-color':''})
		}
	})

	//生成回复内容
	$(document).delegate(".floor .reply-pager a","click",function(event){
		event.preventDefault()
		var page = parseInt($(this).attr('href')),
			replys = $(this).parents(".replys"),
			floor = $(this).parents(".floor"),
			replyFrame = replys.find(".reply-frame"),
			replyPager = replys.find(".reply-pager"),
			floorId = replyPager.siblings("input").val();
		if(page>0){
			$.ajax({
				url: '../controller/reply_page.php',
				type: 'POST',
				data: {body:{
					'session_id': $.trim($("#session_id").val()),
					'pageNo': page,
					'floor_id': floorId
				}},
				//dataType: 'json',
				success: function(data){
					if(typeof data == 'string'){
						data = eval('('+data+')');
					}
					console.log(data)
					if(data && data.code==200){
						data = data.data
						var i,item,
							replyList = '',pageList = '',
							oldHeight = parseInt(replyFrame.css('height')),//旧高度
							newHeight = 0//新高度
						for(i in data.list){
							item = data.list[i]
							replyList += '<div class="reply"><span class="left-col"><img src="'+item.head+'" alt="头像"></span><div class="right-col"><div class="up-row"><a href="" class="name">'+item.author+':</a>'+item.value+'</div><div class="down-row"><span class="time">'+item.time+'</span>';
							if(item.ableDelete > 0){
								replyList += '<a href="'+item.ableDelete+'" class="delete-reply">删除</a>';
							}
							replyList += '</div></div></div>';
						}
						//删除旧生成新
						replyFrame.find("*").remove()
						replyFrame.html(replyList)
						//每个回复中如果p只要一个css为inline-block  多于1则为block
						$(".reply,.reply-row").each(function(){
							var ps = $(this).find("p");
							if(ps.length==1){
								ps.css('display','inline-block');
							}else{
								ps.css('display','block');
							}
						})
						//重设高度
						newHeight = parseInt(replyFrame.css('height'))
						var gap = newHeight - oldHeight,
							a = parseInt(floor.attr('floorHeight')) + gap,
							b = parseInt(replys.css('height')) + gap
						floor.attr('floorHeight',a)
						floor.css({height:a+'px'})
						replys.css({height:b+'px'})

						var pageNo = parseInt(data.pageNo),
							totalPage = parseInt(data.totalPage)
						if(pageNo > 1){
							pageList += '<li><a href="1">首页</a></li>'
							pageList += '<li><a href="'+(pageNo-1)+'">上一页</a></li>'
							if((pageNo-2)>=1){
								pageList += '<li><a href="'+(pageNo-2)+'">'+(pageNo-2)+'</a></li>'
							}
							if((pageNo-1)>=1){
								pageList += '<li><a href="'+(pageNo-1)+'">'+(pageNo-1)+'</a></li>'
							}
						}
						pageList += '<li><a class="selected">'+pageNo+'</a></li>'
						if(pageNo < totalPage){
							if((pageNo+1) <= totalPage){
								pageList += '<li><a href="'+(pageNo+1)+'">'+(pageNo+1)+'</a></li>'
							}
							if((pageNo+2) <= totalPage){
								pageList += '<li><a href="'+(pageNo+2)+'">'+(pageNo+2)+'</a></li>'
							}
							pageList += '<li><a href="'+(pageNo+1)+'">下一页</a></li>'
						}
						pageList += '<li><a href="'+totalPage+'">尾页</a></li>'
						replyPager.find("*").remove()
						replyPager.html(pageList)

					}	
				}
			})
		}
	})

	$(window).load(function(){
		//给楼层设定器本来的高度 让它的子节点height:100% 起作用
		$(".floor").each(function(){
			var height = $(this).css('height')
			$(this).css({height:height})
		})
	})

	//展开收起回复
	$(".toggle-reply").each(function(){
		(function(that){
			var floor = that.parents(".floor"),
				replys = floor.find(".replys"),
				replysHeight = parseInt(replys.css('height')),
				isShow = false,
				floorH = parseInt(floor.css('height'));
			//设为透明
			replys.css('opacity',0);
			//因为有分页功能会导致floor高度会发生改变
			floor.attr('floorHeight',floorH)

			var	hide = function(){
					var floorHeight = parseInt(floor.attr('floorHeight'))
					replys.css({
						overflow: 'hidden',
						border: 0
					})
					replys.animate({height: 0})
					floor.animate({height: (floorHeight - replysHeight) + 'px'})
				},
				show = function(){
					var floorHeight = parseInt(floor.attr('floorHeight'))
					replys.css({
						overflow: '',
						border: ''
					})
					replys.animate({height: replysHeight + 'px'})
					floor.animate({height: floorHeight + 'px'})
				}
			//直接隐藏不动画
			replys.css({height: 0,border: 0})
			floor.css({height: (floorH-replysHeight) + 'px'})

			var oldText = that.text();
			that.click(function(event){
				event.preventDefault() 
				if(isShow){
					$(this).text(oldText)
					hide();isShow = false;
				}else{
					$(this).text('收起回复')
					show();isShow = true;
				}
			})
			//设为不透明
			replys.css('opacity',1);
		})($(this))
	})

	//一楼点击回复
	$(".goto-reply").click(function(){
		if(titleTip){
			titleTip.hide()
		}
		$("html,body").animate({
			scrollTop: $("#editor").offset().top + 'px'
		})
	})
}
})