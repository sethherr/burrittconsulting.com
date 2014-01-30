$(document).ready(function(){
	

	$("#menu_nav a").live("click", function(){
		var link = $(this).attr('title');
		
		var menuDiv = $("div#" + link);
		menuDiv.show('slow');
		menuDiv.nextAll().show('show');
  		menuDiv.prevAll().hide('slow');
		return false;
	});
});


