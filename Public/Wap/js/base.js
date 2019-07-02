;(function(t,ok,no=function(e){}){	
	var startX
	var mx = 0;
	var mv = 0;
	var isgo;
	$(document).on('touchstart',t,function(e){	
		startX = e.originalEvent.changedTouches[0].pageX;	
	});

	$(document).on('touchmove',t,function(e){
		mx = e.originalEvent.changedTouches[0].pageX;
		mv = mx - startX;
		if(startX < 20){
			$(t).css({'left':mv});
			if(mv > 80){
				isgo = true;
			}else{				
				isgo = false;
			}
		}
	})
	$(document).on('touchend',t,function(e){ 
		 if(isgo){
			 ok(t);
		 }else{
			no(t);
			$(t).css({'left':0});
		 }
	})
})('body',function(e){window.history.go(-1)});

;(function(t,ok,no=function(e){}){	
	var startY
	var mx = 0;
	var mv = 0;
	var isgo;
	$(document).on('touchstart',t,function(e){	
		startY = e.originalEvent.changedTouches[0].pageY;
		
	});

	$(document).on('touchmove',t,function(e){
		mx = e.originalEvent.changedTouches[0].pageY;
		mv = mx - startY;
		console.log($(document).scrollTop());
		if($(document).scrollTop() == 0){
			$(t).css({'top':mv});
			if(mv > 80){
				isgo = true;
			}else{				
				isgo = false;
			}
		}
	})
	$(document).on('touchend',t,function(e){ 
		 if(isgo){
			 ok(t);
		 }else{
			no(t);
			$(t).css({'top':0});
		 }
	})
})('body',function(e){location.reload()});

var del = function(t,ok,no=function(e){}){	
	var startX
	var isgo = false;
	var mx = 0;
	var mv = 0;
	$(document).on('touchstart',t,function(e){	
		startX = e.originalEvent.changedTouches[0].pageX;
	});
	$(document).on('touchmove',t,function(e){
		mx = e.originalEvent.changedTouches[0].pageX;
		mv = mx - startX;
		console.log(mv);		
		if(mv < 5){
			console.log(mv);
			$(this).css({'left':mv,'position':'relative'});
			if(mv < -80){
				isgo = true;
			}else{
				isgo = false;
			}
		}
	})
	$(document).on('touchend',t,function(e){ 
		 if(isgo){			 
			 $(this).css({'left':0});
			 ok(this);			 
			isgo = false;
		 }else{			
			$(this).css({'left':0});
			no(this);
		 }
	})
};
