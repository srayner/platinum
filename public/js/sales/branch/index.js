$(function(){
	
	// Closes the parent upon click.
	$(".close").click(function () {
		$(this).parent().fadeTo(400, 0, function () {
			$(this).slideUp(400);
		});
	    return false;
	}); 
	
	// Get the total records
	iTotalRecords = parseInt($('#total-records').html());
	
	oTable = $('.data').dataTable({
		"sPaginationType": "full_numbers",
		"bServerSide": true,
		"sAjaxSource": "/sales/branch/index",
		"aoColumnDefs": [
		    { "bSortable": false, "aTargets": [ 'no-sort' ] },
		    { "sWidth": "100px", "aTargets": [ 'actions' ] },
		],
		"iDeferLoading" : iTotalRecords
	});

});