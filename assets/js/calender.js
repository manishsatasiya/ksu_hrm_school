	$(document).ready(function() {
	
	
		/* initialize the external events
		-----------------------------------------------------------------*/
	
		$('#external-events div.external-event').each(function() {
		
			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()) // use the element's text as the event title
			};
			
			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);
			
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});
			
		});
	
	
		/* initialize the calendar
		-----------------------------------------------------------------*/
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false,
			droppable: false,
			selectable: true,
			selectHelper: true,
			events: CI.base_url+"schedule/index_json",
			dayRender: function(startDate, cell) { 
				 var startDateString = $.fullCalendar.formatDate(startDate, 'yyyy-MM-dd');
				 alert(startDateString);
			}
		});
		/* Hide Default header : coz our bottons look awesome */ 
		$('.fc-header').hide();
		
		//Get the current date and display on the tile
		var currentDate = $('#calendar').fullCalendar('getDate');
		
		$('#calender-current-day').html($.fullCalendar.formatDate(currentDate, "dddd"));
		$('#calender-current-date').html($.fullCalendar.formatDate(currentDate, "MMM yyyy"));
		
	
		$('#calender-prev').click(function(){
			$('#calendar').fullCalendar( 'prev' );
			currentDate = $('#calendar').fullCalendar('getDate');		
			$('#calender-current-day').html($.fullCalendar.formatDate(currentDate, "dddd"));
			$('#calender-current-date').html($.fullCalendar.formatDate(currentDate, "MMM yyyy"));
		});
		$('#calender-next').click(function(){
			$('#calendar').fullCalendar( 'next' );
			currentDate = $('#calendar').fullCalendar('getDate');		
			$('#calender-current-day').html($.fullCalendar.formatDate(currentDate, "dddd"));
			$('#calender-current-date').html($.fullCalendar.formatDate(currentDate, "MMM yyyy"));
		});
		
		$('#change-view-month').click(function(){
			$('#calendar').fullCalendar('changeView', 'month');
		});
		$('#change-view-week').click(function(){
			 $('#calendar').fullCalendar( 'changeView', 'agendaWeek');
		});
		$('#change-view-day').click(function(){
			$('#calendar').fullCalendar( 'changeView','agendaDay');
		});
	});