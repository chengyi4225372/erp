!function( $ ) {
	var area = function (option,pid){
		var ops = "<option value='0'>--请选择--</option>";
		$(option.data).each(function(i){
			if(this.pid == pid && pid != 0)ops += "<option value='"+this.id+"'>"+this.name+"</option>"; 
		})
		return ops;
		
	}
	var City = function (obj,option){
		obj.append("<select>");
		obj.append(" - ");
		obj.append("<select>");
		$(obj.children('select')).addClass(option.class);
		var ops = "<option value='0'>--请选择--</option>";
		var pid = 0;
		$(option.data).each(function(i){
			if(this.pid == 0)ops += "<option value='"+this.id+"'>"+this.name+"</option>"; 
			if(option.selected == this.id)pid = this.pid;
		})
		
		var ops1 = area(option,pid);
		var f = $(obj.children('select')).eq(0);
		var l = $(obj.children('select')).eq(1);
		if(option.name){
			f.attr('name',option.name[0]);
			l.attr('name',option.name[1]);
		}
		f.append(ops).val(pid);
		l.empty().append(ops1).val(option.selected ? option.selected : 0);
		
		if(option.strcity){
			obj.append("<input type='hidden' name='"+option.name[0]+"name'>");
			obj.append("<input type='hidden' name='"+option.name[1]+"name'>");
			$(obj.children('input')).eq(0).val(f.find("option:selected").text());
			$(obj.children('input')).eq(1).val(l.find("option:selected").text());
		}
		
		f.on("change",function(){
			var ops2 = area(option,$(this).val());
			l.empty().append(ops2);
			if(option.strcity){
				$(obj.children('input')).eq(0).val($(this).find("option:selected").text());
				$(obj.children('input')).eq(1).val("");
			}
		})
		l.on("change",function(){
			if(option.strcity){
				$(obj.children('input')).eq(1).val($(this).find("option:selected").text());
			}
		})
	}
	$.fn.city = function ( option ) {
		new City($(this),option);
	};
}( window.jQuery );
