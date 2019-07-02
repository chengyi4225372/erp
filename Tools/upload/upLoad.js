!function ( $ ) {
	options = {
		url:'upload.php',
		btnTitle:'上传图片',
		length:1,
		type:'all',
		data:false,
	}
	html = {
		form:"<form enctype='multipart/form-data' method='post'><input name='file' type='file'><input name='file' type='submit'></form><a class='btns'>&nbsp;&nbsp;限图片格式，大小不超过1028KB<div></div></a>",
		msg:"<div>loding.....</div>"
	}
	css = {
		btn:{background:'#ffffff','border':'1px solid #ccc','display':'block','width':'334px','height':'30px','line-height':'30px','text-decoration':'none',"color":'#aaa'},
		btnbtn:{background:"#eee",'height':"100%",'width':"20%",'text-align':'center','line-height':'30px','float':'right','border-left':'1px solid #ccc','color':'#05c','cursor':'pointer'}
	}
	var checkType = function(file,option){
		if (file.value == "") {    
			alert("请上传图片");    
			return false;    
		} else {			
			if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(file.value)) {    
				layer.msg("图片类型必须是gif,jpeg,jpg,png中的一种");    
				file.value = "";    
				return false;
			}
		}    
		return true;  
	}
	var checkLen = function(_this,option){
		if($(_this).children("p").length >= option.length){			
			layer.msg("最多只能上传"+option.length+ "个图片,请删除原来图片重新上传");
			_this.find(':file').val('');
			return false;
		}
		return true;
	}
	var upLoad = function(_this,option){
		return{
			start:function(){
				var id = _this.attr('id');
				iframe = $('<iframe>').attr('name',id+'_up');
				iframe = $(iframe).appendTo(_this).hide();
				form = $(html.form).appendTo(_this).attr({action:URL+options.url}).hide();
				form.attr('target',id+'_up');
				var btn = _this.find('.btns').css(css.btn);
				btn.find('div').text(options.btnTitle).css(css.btnbtn);
				msg = $(html.msg).appendTo(_this).hide();
				return {f:form,i:iframe,s:msg};
			},
			evnt:function(o){
				_this.on('change',':file',function(){
					if(!checkType(this,option)){return false;}
					if(!checkLen(_this,option)){return false;}
					o.f.submit();
					o.s.show().html('loding.....').show();
				})
				_this.on('click','.btns',function(){
					_this.find(':file').click();
				})
				_this.on('click','._close',function(){
					$(this).parent('p').remove();
				})
				_this.on('click','._open',function(){
					layer.open({
						type:1,
						shade:false,
						title:false,
						content:"<img src='"+$(this).parent().children('img').attr('src')+"'>",
					});
				})
				_this.on('click','.showimg',function(){
					url = $(this).attr('src');
					window.open(url);
				})
				o.i.on('load',function(){
					var cd = this.contentWindow;
					var data = cd.document.body.innerText;
					if(data){
						var re = JSON.parse(data);
					}else{
						return false;
					}
					if(re.status == 0){
						o.s.html(re.msg).hide();
						file = $("<p style='background:#000;border:1px solid #ccc;position:relative;top:10px;height:120px;width:100px;font-size:12px;'><img src='"+re.url+"' style='width:100px;height:100px;'><input value="+re.url+" type='hidden' name='"+_this.attr('name')+"'><a class='_open' href='javascript:' style='color:#fff;width:40px;text-decoration:none;position:absolute;bottom:0px;left:3px'>放大</a><a class='_close' href='javascript:' style='color:#f00;width:50px;text-align:right;text-decoration:none;position:absolute;bottom:0px;right:3px'>删除</a></p>");
						_this.append(file);
						
					}else{
						o.s.html(re.msg).css('color','red').fadeOut(3000);
					}
				})
			},
			upEdit:function(data){
				var tr = "<p style='background:#000;border:1px solid #ccc;position:relative;top:10px;height:120px;width:100px;font-size:12px;'><img src='"+data+"' style='width:100px;height:100px;'><input value="+data+" type='hidden' name='"+_this.attr('name')+"'><a class='_open' href='javascript:' style='color:#fff;width:40px;text-decoration:none;position:absolute;bottom:0px;left:3px'>放大</a><a class='_close' href='javascript:' style='color:#f00;width:50px;text-align:right;text-decoration:none;position:absolute;bottom:0px;right:3px'>删除</a></p>";	
				_this.append(tr);			
			}
		}
	}
	$.fn.upLoad = function(option){
		option = $.extend(options,option);
		up = upLoad(this,option);
		obj = up.start();
		if(option.data){
			up.upEdit(option.data);
		}
		up.evnt(obj);
	}
	var URL = window.UEDITOR_HOME_URL || (function(){

        function PathStack() {

            this.documentURL = self.document.URL || self.location.href;

            this.separator = '/';
            this.separatorPattern = /\\|\//g;
            this.currentDir = './';
            this.currentDirPattern = /^[.]\/]/;

            this.path = this.documentURL;
            this.stack = [];

            this.push( this.documentURL );

        }

        PathStack.isParentPath = function( path ){
            return path === '..';
        };

        PathStack.hasProtocol = function( path ){
            return !!PathStack.getProtocol( path );
        };

        PathStack.getProtocol = function( path ){

            var protocol = /^[^:]*:\/*/.exec( path );

            return protocol ? protocol[0] : null;

        };
        PathStack.prototype = {
            push: function( path ){
                this.path = path;
                update.call( this );
                parse.call( this );
                return this;
            },
            getPath: function(){
                return this + "";
            },
            toString: function(){
                return this.protocol + ( this.stack.concat( [''] ) ).join( this.separator );
            }
        };

        function update() {

            var protocol = PathStack.getProtocol( this.path || '' );

            if( protocol ) {

                this.protocol = protocol;

                this.localSeparator = /\\|\//.exec( this.path.replace( protocol, '' ) )[0];

                this.stack = [];
            } else {
                protocol = /\\|\//.exec( this.path );
                protocol && (this.localSeparator = protocol[0]);
            }

        }

        function parse(){

            var parsedStack = this.path.replace( this.currentDirPattern, '' );

            if( PathStack.hasProtocol( this.path ) ) {
                parsedStack = parsedStack.replace( this.protocol , '');
            }

            parsedStack = parsedStack.split( this.localSeparator );
            parsedStack.length = parsedStack.length - 1;

            for(var i= 0,tempPath,l=parsedStack.length,root = this.stack;i<l;i++){
                tempPath = parsedStack[i];
                if(tempPath){
                    if( PathStack.isParentPath( tempPath ) ) {
                        root.pop();
                    } else {
                        root.push( tempPath );
                    }
                }

            }


        }

        var currentPath = document.getElementsByTagName('script');

        currentPath = currentPath[ currentPath.length -1 ].src;

        return new PathStack().push( currentPath ) + "";
    })();
}(window.jQuery)