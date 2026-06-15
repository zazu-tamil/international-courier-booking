<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/fullcalendar/dist/fullcalendar.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>asset/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
  
<!-- fullCalendar -->
<script src="<?php echo base_url() ?>asset/bower_components/moment/moment.js"></script>
<script src="<?php echo base_url() ?>asset/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
 <!-- Page specific script -->
<script>
  $(function () {
    
    
     
    
    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev, today, next',
        center: 'title',
        //right : 'month,agendaWeek,agendaDay'
        right : ''
      },
      buttonText: {
        today: 'Today',
        //month: 'month',
       //  week : 'week',
       //  day  : 'day'
      },
      //Random default events
     eventSources: [
      
      {
        url: '<?php echo site_url('get-calender-data');?>',
        type: 'POST',
        data: {
          custom_param1: 'attendance',
          custom_param2: ''
        },
        failure: function() {
          alert('there was an error while fetching events!');
        },
         
      },
      
       
      /*
      {
        url: '<?php echo site_url('get-calender-data');?>',
        type: 'POST',
        data: {
          custom_param1: 'reminder',
          custom_param2: ''
        },
        failure: function() {
          alert('there was an error while fetching events!');
        },
         
      },
      */
      
      ], 
      
      eventRender: function(eventObj, $el) {
        $el.popover({
          title: eventObj.title,
          content: eventObj.description,
          trigger: 'hover',
          placement: 'top',
          html: true,
          container: 'body'
        });
      },
       
      
      eventClick: function(calEvent, jsEvent, view) {

        //alert('Event: ' + calEvent.id);
        //alert('Event: ' + calEvent.tbl);
       
         
        
          
        
         
        
       // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
      //  alert('View: ' + view.name);
    
        // change the border color just for fun
        $(this).css('border-color', 'red');
    
      },
      
      
       
      //contentHeight: 1000,
      editable  : false,
      droppable : false, // this allows things to be dropped onto the calendar !!!
      
    })
 
    
  })
</script>