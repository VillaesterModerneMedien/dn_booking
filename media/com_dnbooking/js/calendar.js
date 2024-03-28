document.addEventListener('DOMContentLoaded', function () {
    let currentDate = new Date();

    let dayIds = {
        '2024-03-01': 1,
        '2024-03-15': 2,
        '2024-03-20': 3,
    };

    let options = Joomla.getOptions('com_dnbooking');
    let text = options.texte;
    let zeiten = options.zeiten;
    function checkMonth(date) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php?option=com_dnbooking&task=openinghours.checkMonth');
        let token = Joomla.getOptions('csrf.token', '');

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        if (token) {
            xhr.setRequestHeader('X-CSRF-Token', token);
        }

        xhr.onload = function() {

        };
        xhr.send('date=' + date + '&token=' + token);
    }


    function sendTaskRequest(task, dayId, date) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php?option=com_dnbooking&task=openinghours.' + task);
        let token = Joomla.getOptions('csrf.token', '');

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        if (token) {
            xhr.setRequestHeader('X-CSRF-Token', token);
        }

        if(task === 'add') {
            taskText = text.add;
        }
        else if (task === 'edit') {
            taskText = text.edit;
        }
        else if (task === 'delete') {
            taskText = text.delete;
        }

        xhr.onload = function() {
            if (xhr.status === 200) {
                alert(taskText + ' ' + text.success);
                // Hier könnten Sie die Kalenderansicht aktualisieren oder andere Aktionen durchführen
            } else {
                alert(taskText + ' ' + text.failed);
            }
        };
        xhr.send('dayID=' + dayId + '&date=' + date  + '&token=' + token); // Senden Sie die notwendigen Daten
    }

    //TODO JOOMLA PARAMS, siehe Slack

    function formatDate(d) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }

    function monthDays(d) {
        return new Date(d.getFullYear(), d.getMonth() + 1, 0).getDate();
    }

    function generateCalendar(d) {
        let month = d.getMonth();
        checkMonth(month);
        const weekdays = [text.monday, text.tuesday, text.wednesday, text.thursday, text.friday, text.saturday, text.sunday];
        const months = [text.january, text.february, text.march, text.april, text.may, text.june, text.july, text.august, text.september, text.october, text.november, text.december];
  
        let details = {
            totalDays: monthDays(d),
            weekDays: weekdays,
            months: months,
        };

        let start = new Date(d.getFullYear(), d.getMonth()).getDay()-1;
        let cal = [];
        let day = 1;

        // Fügen Sie eine Zeile für die Wochentage hinzu
        cal.push('<tr>');
        for (let j = 0; j < 7; j++) {
            cal[0] += '<td><div class="dayInner">' + details.weekDays[j] + '</div></td>';
        }
        cal[0] += '</tr>';

        // Tage des Monats
        for (let i = 1; i <= 6; i++) { // Beginnen mit der zweiten Reihe
            cal.push(['<tr>']);
            for (let j = 0; j < 7; j++) {
                if ((i === 1 && j < start) || day > details.totalDays) {
                    cal[i].push('<td><div class="dayInner">&nbsp;</div></td>');
                } else {
                    let date = new Date(d.getFullYear(), d.getMonth(), day);
                    let dateString = formatDate(date);
                    let dayId = dayIds[dateString];

                    let classes = 'day' + (dayId ? ' closed' : '');
                    let icons = dayId ? '<div class="icons"><span class="icon-edit editOpeningHour" data-id="' + dayId + '" aria-hidden="true"></span><span class="icon-trash trashOpeningHour" data-id="' + dayId + '" data-day="' + details.weekDays[j] + '" aria-hidden="true"></span></div>' : '<div class="icons"><span class="icon-add addOpeningHour" aria-hidden="true"></span></div>';

                    cal[i].push(`<td class="${classes}" data-date="${dateString}" data-day="${details.weekDays[j]}" ${dayId ? 'data-id="' + dayId + '"' : ''}><div
class="dayInner">${day++}${icons}</div></td>`);
                }
            }
            cal[i].push('</tr>');
        }
        cal = cal.reduce(function (a, b) { return a.concat(b); }, []).join('');
        document.querySelector('table').innerHTML = cal;
        document.getElementById('month').textContent = details.months[d.getMonth()];
        document.getElementById('year').textContent = d.getFullYear();

        // Ereignishandler für Tage im Kalender
        document.querySelectorAll('.day').forEach(function(dayElement) {
            dayElement.addEventListener('mouseover', function() {
                this.classList.add('hover');
            });
            dayElement.addEventListener('mouseout', function() {
                this.classList.remove('hover');
            });
            dayElement.addEventListener('click', function(event) {
                let targetClass = event.target.className;
                let date = this.getAttribute('data-date');
                let dayId = this.getAttribute('data-id');
                if (targetClass.includes('editOpeningHour')) {
                    sendTaskRequest('edit', dayId, date);
                } else if (targetClass.includes('trashOpeningHour')) {
                    sendTaskRequest('delete', dayId, date);
                } else if (targetClass.includes('addOpeningHour')) {
                    sendTaskRequest('add', null, date);
                }
            });
        });
    }

    document.getElementById('left').addEventListener('click', function () {
        document.querySelector('table').innerHTML = '';
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);

        generateCalendar(currentDate);
    });

    document.getElementById('right').addEventListener('click', function () {
        document.querySelector('table').innerHTML = '';
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1);
        generateCalendar(currentDate);
    });


    generateCalendar(currentDate);
});
