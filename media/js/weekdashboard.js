document.addEventListener('DOMContentLoaded', function () {
    window.updateWeekDashboard = function() {
        const calendarWeek = document.getElementById('filter_calendarWeek').value;
        const year = document.getElementById('filter_year').value;

        if (calendarWeek && year) {
            const form = document.getElementById('adminForm');
            const weekInput = document.createElement('input');
            weekInput.type = 'hidden';
            weekInput.name = 'filter_calendarWeek';
            weekInput.value = calendarWeek;
            form.appendChild(weekInput);

            const yearInput = document.createElement('input');
            yearInput.type = 'hidden';
            yearInput.name = 'filter_year';
            yearInput.value = year;
            form.appendChild(yearInput);

            Joomla.submitform('weekdashboard.display', form);
        }
    }
});

