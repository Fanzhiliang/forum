$(document).ready(function(){
if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){//如果是移动端
	var search = $("#search"),
		pageNo = $("#pageNo"),
		searchShow = function(){
			search.css({display:'block'})
			search.animate({width:'10.5rem'})
		},
		searchHidden = function(){
			search.animate({width:'0'},function(){
				search.css({display:''})
			})
		}

	$("#toggle-search").click(function(event){
		event.stopPropagation()
		event.preventDefault()
		if(search.css('display') == 'block'){
			search.submit();
		}else{
			searchShow();
		}
	})

	$("#close-search").click(function(){
		searchHidden();
	})

	search.click(function(event){
		event.stopPropagation();
	})

	var totalPage = parseInt($(".footer .totalPage").text());

	$(".footer .prev a").click(function(event){
		event.preventDefault();
		var newPageNO = parseInt(pageNo.val())-1;
		if(newPageNO>0 && newPageNO<=totalPage){
			pageNo.val(newPageNO);
			search.submit();
		}
	})

	$(".footer .next a").click(function(event){
		event.preventDefault();
		var newPageNO = parseInt(pageNo.val())+1;
		if(newPageNO>0 && newPageNO<=totalPage){
			pageNo.val(newPageNO);
			search.submit();
		}
	})

	$("a").click(function(event){
		if(search.css('display') == 'block'){
			event.preventDefault()
		}
	})

	$(document).click(function(){
		if(search.css('display') == 'block'){
			searchHidden()
		}
	})
}else{//如果是pc端  主要!
	//取消宽度
	$(".postings p").each(function(){
		$(this).find("img").each(function(i){
			if(i<2){
				$(this).css({width: '','max-width': ''});
			}else{
				$(this).hide();
			}
		})	
	});

	var	mainPrimary = $(".main-primary .body"),
		topLimit = parseInt(mainPrimary.offset().top),
		sidebar = $(".sidebar"),
		mainPrimaryLeft = parseInt(mainPrimary.offset().left),
		sidebarLeft = mainPrimaryLeft+parseInt(mainPrimary.css('width')),
		headerSeach = $(".header-seach"),
		freenet = $(".header .freenet")

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
			headerSeach.css({
				position: 'fixed',
				top: 0,
				left: (mainPrimaryLeft+1) + 'px',
				'margin-top': 0,
				width: '718px'
			})
			freenet.css({display:'none'})
		}else{
			sidebar.css({position:'',top:'',left:''})
			headerSeach.css({position:'',top:'',left:'','margin-top':'',width:''})
			freenet.css({display:''})
		}
	})

	var totalPage = parseInt($(".pager .rear").attr('href')),
		myForm = $("#search"),
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

	//搜索
	$("#toggle-search").click(function(event){
		event.stopPropagation()
		event.preventDefault()
		myForm.submit();
	})
}
})