document.addEventListener('DOMContentLoaded', function () {
    var currentDate = new Date();



    var dayIds = {
        '2024-03-01': 1,
        '2024-03-15': 2,
        '2024-03-20': 3,
    };

    console.log('testParams', testParams);

    function sendTaskRequest(task, dayId, date) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php?option=com_dnbooking&task=openinghours.' + task); // Ändern Sie dies in Ihre Joomla-Task-URL
        var token = Joomla.getOptions('csrf.token', '');

        console.log('token', token);

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        if (token) {
            xhr.setRequestHeader('X-CSRF-Token', token);
        }

        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('Task erfolgreich: ' + task + ' für ' + date);
                // Hier könnten Sie die Kalenderansicht aktualisieren oder andere Aktionen durchführen
            } else {
                alert('Fehler beim Ausführen des Tasks: ' + task);
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
        var lang = document.documentElement.lang || 'en-gb'; // Standardmäßig auf Englisch (Großbritannien), falls kein Lang-Attribut gesetzt ist
        const translations = {
            'en-gb': {
                weekDays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
            },
            'de-de': {
                weekDays: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
                months: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember']
            }
            // Fügen Sie hier weitere Sprachen hinzu
        };

        var details = {
            totalDays: monthDays(d),
            weekDays: translations[lang].weekDays,
            months: translations[lang].months,
        };

        var start = new Date(d.getFullYear(), d.getMonth()).getDay();
        var cal = [];
        var day = 1;

        // Fügen Sie eine Zeile für die Wochentage hinzu
        cal.push('<tr>');
        for (var j = 0; j < 7; j++) {
            cal[0] += '<td><div class="dayInner">' + details.weekDays[j] + '</div></td>';
        }
        cal[0] += '</tr>';

        // Tage des Monats
        for (var i = 1; i <= 6; i++) { // Beginnen mit der zweiten Reihe
            cal.push(['<tr>']);
            for (var j = 0; j < 7; j++) {
                if ((i === 1 && j < start) || day > details.totalDays) {
                    cal[i].push('<td><div class="dayInner">&nbsp;</div></td>');
                } else {
                    var date = new Date(d.getFullYear(), d.getMonth(), day);
                    var dateString = formatDate(date);
                    var dayId = dayIds[dateString];

                    var classes = 'day' + (dayId ? ' closed' : '');
                    var icons = dayId ? '<div class="icons"><span class="icon-edit editOpeningHour" data-id="' + dayId + '" aria-hidden="true"></span><span class="icon-trash trashOpeningHour" data-id="' + dayId + '" data-day="' + details.weekDays[j] + '" aria-hidden="true"></span></div>' : '<div class="icons"><span class="icon-add addOpeningHour" aria-hidden="true"></span></div>';

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
                var targetClass = event.target.className;
                var date = this.getAttribute('data-date');
                var dayId = this.getAttribute('data-id');
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
