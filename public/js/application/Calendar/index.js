$(function(){
	
	function saveEvent(event){
		$.ajax({
			url: "/calendar/update",
			data: event
		});
	}
	
	$('#calendar').fullCalendar({
		// Options
		header: {
			left: 'prev,next today',
		    center: 'title',
		    right: 'month,agendaWeek,agendaDay'
		},
		titleFormat: {
			month: 'MMMM yyyy',                             // September 2009
			week: "MMM dS[ yyyy]{ '&#8211;'[ MMM] dS yyyy}", // Sep 7th - 13th 2009
			day: 'dddd, MMM dS, yyyy'                  // Tuesday, Sep 8th, 2009
		},
		columnFormat: {
			month: 'ddd',    // Mon
		    week: 'ddd dS', // Mon 9th
		    day: 'dddd dS'  // Monday 9th
		},
		events: '/calendar/events',
		editable: true,
		eventDrop: function(event, jsEvent, ui, view) {
			saveEvent(event);
		},
		eventResize: function(event, jsEvent, ui, view) {
			saveEvent(event);
		}
	});

});