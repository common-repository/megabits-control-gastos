document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
          right: 'prev next today',
          center: 'title',
          left: 'dayGridMonth timeGridWeek timeGridDay listMonth'
        },
        buttonText: {
          today:    'hoy',
          month:    'mes',
          week:     'semana',
          day:      'día',
          list:     'lista'
        },
        firstDay: 1,
        buttonIcons: true,
        navLinks: true,
        editable: true,
        dayMaxEvents: true,
        locale: 'es',
        aspectRatio: 2,
        events: "/wp-content/plugins/megabits-control-gastos/includes/mgb_fecth_events.php",
        eventColor: 'cyan',
        eventTextColor: 'darkblue',
        eventBorderColor: 'darkblue',
        selectable: true,

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            // change the border color
            info.el.style.backgroundColor = 'lightblue';
            if (info.event.extendedProps.byuser == 'SI') {
                var titulo = info.event.title;
                var icono = 'info';
                var codigo = '<p>'+info.event.extendedProps.description+'</p><a href="'+info.event.extendedProps.URL+'">Visitar el enlace</a>';

            } else {
                var titulo = '';
                var icono = '';
                var codigo = info.event.extendedProps.URL;
            }
            Swal.fire({
                title: titulo,
                icon: icono,
                html: codigo,
            })
            .then((result) => {
                Swal.close();
                info.el.style.backgroundColor = 'cyan';
            });
        }
    });
    calendar.render();
});