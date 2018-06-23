(function(window,document){
	var href = window.location.href,
		httpType = href.substring(0, href.indexOf(':')),
		pcUrl = httpType+'://localhost/',
		mobileUrl = httpType+'://localhost/';

	if(/Android|webOS|iPhone|iPod|BlackBerry|Phone/i.test(navigator.userAgent)){
		// if(window.location.href.indexOf(pcUrl) != -1){
		// 	window.location.href = mobileUrl;
		// }
		//获得宽高
		var pageWidth = window.innerWidth,
			pageHeight = window.innerHeight;
		if(typeof pageWidth != "number"){
			if (document.compatMode == "CSS1Compat") {
				pageWidth = document.documentElement.clientWidth;
				pageHeight = document.documentElement.clientHeight;
			} else {
				pageWidth = document.body.clientWidth;
				pageHeight = document.body.clientHeight;
			}
		}
		//给html设置宽高，方便使用rem
		document.getElementsByTagName("html")[0].style.fontSize = pageWidth*0.0625 + "px";
	}else{//如果是pc端  主要!
		// if(window.location.href.indexOf(mobileUrl) != -1){
		// 	window.location.href = pcUrl;
		// }
	}
})(window,window.document);