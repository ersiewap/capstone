document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  const salonID = document.getElementById('salon_select');
  
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    contentHeight: 400,
    selectable: true,
    themeSystem: 'standard',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
    },

    eventSources: [{
      url: 'ajax/monthSchedule.php',
      method: 'GET',
      extraParams: {
        salon_id: salonID.value
      }
     }
    ],
    eventColor: 'transparent', 
    eventBackgroundColor: 'transparent',
    eventDidMount: function (info) {
      
      info.el.style.backgroundColor = 'green';
      info.el.style.border = '1px solid green';
      info.el.querySelector('.fc-event-title').innerText  = "Available";
      if (info.event.extendedProps.status === 'full') {
        info.el.style.backgroundColor = 'red';
        info.el.style.border = '1px solid red';
        info.el.querySelector('.fc-event-title').innerText  = "Full";
        info.el.style.cursor = 'not-allowed';
      } 

      
    },
    dateClick: function (info) {
      // Triggered when a date is clicked
      handleDateClick(info.dateStr); // Pass the clicked date to your function
    },
    
  });

  calendar.render();

  salonID.addEventListener('change', function () {
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      contentHeight: 400,
      selectable: true,
      themeSystem: 'standard',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
      },
  
      eventSources: [{
        url: 'ajax/monthSchedule.php',
        method: 'GET',
        extraParams: {
          salon_id: salonID.value
        }
       }
      ],
      //post eventcall
      eventColor: 'transparent', 
      eventBackgroundColor: 'transparent',
      eventDidMount: function (info) {
        
        info.el.style.backgroundColor = 'green';
        info.el.style.border = '1px solid green';
        info.el.querySelector('.fc-event-title').innerText  = "Available";
        if (info.event.extendedProps.status === 'full') {
          info.el.style.backgroundColor = 'red';
          info.el.style.border = '1px solid red';
          info.el.querySelector('.fc-event-title').innerText  = "Full";
          info.el.style.cursor = 'not-allowed';
        } 
  
        
      },
      dateClick: function (info) {
        handleDateClick(info.dateStr); 
      },
      
    });
  
    calendar.render();
  });
});

function handleDateClick(date) {
  const selectedDate = date;
  const selectedTime = document.getElementsByClassName('.timeSlot'); // Get selected time
  const salonID = document.getElementById('salon_select');

  //build url string
  const url = new URL('ajax/daySchedule.php', window.location.origin); 
  url.searchParams.append('date', selectedDate);
  url.searchParams.append('salon_id', salonID.value);
  
  // run daySchedule query
  fetch(url)
  .then(response => {
    return response.json();
  })
  .then(data => {
      disableRadioTime(data);
  })
  .catch(error => {
    console.error('Fetch error:', error);
  });


}


function disableRadioTime(data) {
  const radios = document.getElementsByName('timeSlot');
  const arrayData = data[0] != null ? data[0].split(",") : [];

  radios.forEach(radio => {
      // Check if the radio's value is selected date in database
      label = radio.parentElement;
      if (arrayData.includes(radio.value)) {
          radio.disabled = true; // Disable the radio input
          label.classList.add("timeSlot-occupied");
      } else {
          radio.disabled = false;
          label.classList.remove("timeSlot-occupied");
      }
     
  });
}