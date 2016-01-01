$(function(){
	
	// Closes the parent upon click.
	$(".close").click(function () {
		$(this).parent().fadeTo(400, 0, function () {
			$(this).slideUp(400);
		});
	    return false;
	}); 

	$('#item_code').typeahead({
		ajax: {
			url: '/inventory/items/search',
			triggerLength: 1,
		},
		display: 'item_code',
		val: 'id'
	});
	
});