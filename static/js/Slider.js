(function(window,document){
	var Slider =  function(id,type,interval,initDis,additional){//initDis 是初始位置默认是0 如果是第二个则是-2
		var scrollx = $('#'+id),//type为手动移动的方法，interval是循环的间隔，小于不等于0则不循环
			moveObj = scrollx.find(".slider-item[belong='"+id+"']"),//additional是type事件触发时附加执行的函数
			items = scrollx.find(".slider-item[belong='"+id+"']>*"),
			trigs = scrollx.find(".slider-trig[belong='"+id+"']>*"),
			titles = scrollx.find(".slider-title[belong='"+id+"']>*"),
			cycleThread = {},
			currentDis = initDis===undefined||typeof initDis !=='number' ? 0 : initDis,
			newTrig = {},oldTrig = {};//触发事件的trig和前一个trig
		var moveEach = function(i){
				if(i == -currentDis){
					$(this).addClass('on')
					newTrig = this
				}else if($(this).hasClass('on')){
					$(this).removeClass('on')
					oldTrig = this
				}
			},
			move = function(dir){// -向左 +向右		
				var newDis = currentDis+dir
				if(items.length>1){// 判断是否大于1 只有一个组件不能滑动
					if(newDis<=0 && newDis>=-(items.length-1)){
						currentDis = newDis
					}else if(newDis>0){
						currentDis = -(items.length-1)
					}else if(newDis<-(items.length-1)){
						currentDis = 0
					}
					moveObj.animate({'margin-left':currentDis*100+'%'})
				}else{
					currentDis = newDis
				}
				if(trigs.length>1){trigs.each(moveEach)}
				if(titles.length>1){titles.each(moveEach)}
				if(typeof additional === 'function'){additional(newTrig,oldTrig)}
			},
			startCycle = function(){
				if(typeof interval ==='number' && interval>0){
					// cycleThread=setInterval(()=>{move(-1)},interval)
					cycleThread=setInterval(function(){move(-1)},interval)
				}
			},
			stopCycle = function(){
				clearInterval(cycleThread)
				cycleThread = {}
			};

		this.move = move
		this.startCycle = startCycle
		this.stopCycle = stopCycle

		trigs.each(function(i){
			$(this).attr('index',i)
			$(this)[type].call($(this),function(){
				var newIndex = i,
					oldIndex = scrollx.find(".slider-trig[belong='"+id+"']>*.on").attr('index')
				move(oldIndex - newIndex)
			})
		})

		moveObj.hover(stopCycle,startCycle)
	}

	if(typeof window.Utils == 'undefined'){
		window.Utils = {}
	}
	window.Utils.Slider = Slider

})(window,window.document)
