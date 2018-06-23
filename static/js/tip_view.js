$(document).ready(function(){
	$("#back").click(function(event){
		event.preventDefault()
		window.history.go(-1)
	})
})