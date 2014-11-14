(function($){
	$(function(){
		var box = $("#wp_simple_association_manager_groups_box_ID");
		box.find(".categorychecklist > li").each(function(){
			var group = $(this);
			var hiddenCat = group.find("> span");
			group.find(".children input:checkbox").on('change',function(){
				hiddenCat.find("input:checkbox").prop("checked",group.find(".children input:checked").length > 0);
				if(group.find(".children input:checked").length > 0){
					hiddenCat.removeClass("dashicons-minus").addClass("dashicons-yes");
				}else{
					hiddenCat.removeClass("dashicons-yes").addClass("dashicons-minus");
				}
			});
		});
	});
})(jQuery);
