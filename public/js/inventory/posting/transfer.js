$(function(){

	$('.typeahead').typeahead({
		ajax: {
			url: '/inventory/items/search',
			triggerLength: 1,
		},
		display: 'item_code',
		val: 'id'
	});
	
});