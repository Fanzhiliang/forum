$(document).ready(function(){
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
	},666);

	$(".delete-reply").click(function(event){
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

	//删除贴子
	$(".delete-floor").click(function(event){
		event.preventDefault();
		var that = $(this);
		tip.showTip('确认删除?',{
			sure: function(){
				var result = {
						'session_id': $("#session_id").val(),
						'floor_id' : $("#floor_id").val()
					}
				$.ajax({
					url: '../controller/delete.php',
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
							if(that.hasClass('delete-floor')){
								window.location.href = '/home';
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
}else{//如果是pc端  主要!
}
})