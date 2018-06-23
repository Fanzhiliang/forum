(function(window,document){
	var formUtils = {
        isChinese : function(char){//检测是否为中文，true表示是中文，false表示非中文
        	if(typeof char !== 'string'){return false;}
        	char = char.trim();
            if(/^[\u3220-\uFA29]+$/.test(char)){
                return true;
            }else{
                return false;
            }
        },
        getStrLength: function(val){
        	if(typeof val !== 'string'){return false;}
        	val = val.trim();
        	var len = 0;
        	for (var i = 0;i<val.length;i++) {
        		var char = val.charAt(i);
        		if(this.isChinese(char)){
        			len += 2;
        		}else{
        			++len;
        		}
        	}
        	return len;
        },
		checkFields : function(form){
			var result = {
					message : 'success',
					field : {}
				},
				that = this;

			form.find("input,select").each(function(){
				var field = $(this),
					val = field.val(),
					message = (function(id,val,that){
						switch (id) {
							case 'account':
								for(var i=0;i<val.length;i++){
									if(that.isChinese(val.charAt(i))){
										return '账号不能包含中文!';
									}
								}
								var len = that.getStrLength(val);
								if(len<=0){
									return '账号不能为空!'
								}else if(len<3 || len>20){
									return '账号长度需为3-20位字符!'
								}
								break;
							case 'password':
							case 'newPassword':
								for(var i=0;i<val.length;i++){
									if(that.isChinese(val.charAt(i))){
										return '密码不能包含中文!';
									}
								}
								var len = that.getStrLength(val);
								if(len<=0){
									return '密码不能为空!'
								}else if(len<8 || len>20){
									return '账号长度需为8-30位数字或字母!'
								}
								break;
							case 'name':
								var len = that.getStrLength(val);
								if(len<=0){
									return '昵称不能为空!'
								}else if(len<3){
									return '昵称长度过短!'
								}else if(len>20){
									return '昵称长度过长!'
								}
								break;
							case 'email':
								var reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$")
								if(val.length<=0){
									return '邮箱不能为空!'
								}else if(!reg.test(val)){//这里取反
									return '邮箱格式不正确!'
								}
								break;
							case 'test':
								if(val!='true'){
									return '请滑动滑块验证!'
								}
								break;
						}
					})(field.attr('id'),field.val(),that)
				if(message !== undefined){
					result.message = message
					result.field = field
					return false
				}
			})
			return result
		}
	}

	if(typeof window.Utils == 'undefined'){
		window['Utils'] = {}
	}
	window.Utils['formUtils'] = formUtils

})(window,window.document)

	