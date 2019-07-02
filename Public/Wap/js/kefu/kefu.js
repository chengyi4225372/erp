(function(){
	kefu = function(){
		var id;
		return {
			meMsg:function(msg){
				var html = this.meHtml.replace('__msg__',msg);
				html = html.replace('__TIME__', new Date().toLocaleString());
				$('.msg').append(html);	
				$('#inmsg').val('');
				$('.msglog').scrollTop($('.msg').height());
			}
			,toMsg:function (msg){
				var html = this.toHtml.replace('__msg__',msg);
				html = html.replace('__TIME__',new Date().toLocaleString());
				if (navigator.vibrate) {		   // 支持
				   navigator.vibrate([100]);
				}
				$('.msg').append(html);
				$('#inmsg').val(null);					
				$('.msglog').scrollTop($('.msg').height());
			}
			,creat:function(){
				$('head').append('<link href="/Public/Wap/js/kefu/kefu.css" charset="utf-8" type="text/css" rel="stylesheet">');
				this.id = $(this.html).appendTo('body');
				this.id.hide();
				var _this = this;
				$(this.id).on('click','.close',function(){
					_this.id.hide();
				})
			}
			,show:function(){
				this.id.show();
			}
			,html:"<div id='zixun'><div class='msglog'><div class='msg'></div></div><div class='msgin'><div><div class='close'>关闭</div><div id='inmsg' contenteditable='true'> </div><div class='send'>发送</div></div></div></div>"
			,toHtml:"<div class='pc'><p style='text-align:center;margin:10px;'>__TIME__</p><div class='logo'>&nbsp;</div><div class='text'><p>__msg__</p></div></div>"
			,meHtml:"<div class='me'><p style='text-align:center;margin:10px;'>__TIME__</p><div class='me-logo'>&nbsp;</div><div class='text'><p>__msg__</p></div></div>"
		}
	}
	$.kefu = new kefu();
	$.kefu.creat();
	
})();