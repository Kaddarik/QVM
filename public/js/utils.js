$(document).ready(function(){
	$("#details-membres-hide .btnHide").click(function(e){
		e.preventDefault();
		if($(this).hasClass("btn-rotat")){
			$('#'+$(this).attr('data-target')).show();
			$(this).removeClass("btn-rotat");
		}
		else{
			$('#'+$(this).attr('data-target')).hide();
			$ (this).addClass("btn-rotat");
		}
	});
});