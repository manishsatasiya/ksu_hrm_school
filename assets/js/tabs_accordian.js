$(document).ready(function() {	
	//Accordians
	$('.panel-group').collapse({
		toggle: false
	})	

/***** Tabs *****/
	//Normal Tabs - Positions are controlled by CSS classes
    $('#tab-01 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
  
    $('#tab-2 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
		var tab_id = $(this).closest('li').index();
		document.cookie="tab_id="+tab_id;
	});
	
	//alert(getCookie('tab_id'));
	var tab_id = getCookie('tab_id');
	$('#tab-2 li:eq('+tab_id+') a').tab('show'); 
	  
	$('#tab-3 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	$('#tab-3 li:eq(2) a').tab('show'); 
	  
	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	  
	$('#tab-5 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i].trim();
			if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
		}
		return "";
	}
});
