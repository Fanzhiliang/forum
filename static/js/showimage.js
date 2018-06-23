(function(window,document){
if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){
$(document).ready(function(){
	var screenHeight = parseInt($("html").css('height')),
		main = $(".main"),
		mainHeight = parseInt(main.css('height'))
	if(mainHeight<screenHeight){
		main.css({top:'50%'})
		main.css({transform:'translateY(-50%)'})
	}
	$(".back").click(function(){
		window.history.go(-1)
	})
	$(".save").click(function(){
			
	})
})
}else{//如果是pc端  主要!
	
}
})(window,window.document)