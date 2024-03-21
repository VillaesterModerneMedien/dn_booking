document.addEventListener('DOMContentLoaded', function () {
    var currentDate = new Date();

    var dayIds = {
        '2024-03-01': 1,
        '2024-03-15': 2,
        '2024-03-20': 3,
        // Weitere Daten
    };

    function formatDate(d) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }

    function monthDays(d) {
        return new Date(d.getFullYear(), d.getMonth() + 1, 0).getDate();
    }

    function sendTaskRequest(task, dayId, date) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/path/to/joomla/task/' + task); // Ändern Sie dies in Ihre Joomla-Task-URL
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('Task erfolgreich: ' + task + ' für ' + date);
                // Hier könnten Sie die Kalenderansicht aktualisieren oder andere Aktionen durchführen
            } else {
                alert('Fehler beim Ausführen des Tasks: ' + task);
            }
        };
        xhr.send('id=' + dayId + '&date=' + date); // Senden Sie die notwendigen Daten
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
        for (var i = 0; i <= 6; i++) {
            cal.push(['<tr>']);
            for (var j = 0; j < 7; j++) {
                var date = new Date(d.getFullYear(), d.getMonth(), day - start + 1);
                var dateString = formatDate(date);
                var dayId = dayIds[dateString]; // Nehmen Sie an, dass dayIds ein externes Objekt ist
                var classes = 'day' + (dayId ? ' closed' : '');
                var icons = dayId ? '<div class="icons"><span class="icon-edit editCalendar" data-id="' + dayId + '" aria-hidden="true"></span><span class="icon-trash trashCalendar" data-id="' + dayId + '" aria-hidden="true"><div></div>' : '<div class="icons"><i class="add-icon addCalendar">➕</i></div>';
                if (i === 0) {
                    cal[i].push('<td><div class="dayInner">' + details.weekDays[j] + '</div></td>');
                } else if (day > details.totalDays || j < start && i === 1) {
                    cal[i].push('<td><div class="dayInner">&nbsp;</div></td>');
                } else {
                    cal[i].push(`<td class="${classes}" data-date="${dateString}" ${dayId ? 'data-id="' + dayId + '"' : ''}><div class="dayInner">${day++}${icons}</div></td>`);
                }
            }
            cal[i].push('</tr>');
        }
        cal = cal.reduce(function (a, b) { return a.concat(b); }, []).join('');
        document.querySelector('table').innerHTML += cal;
        document.getElementById('month').textContent = details.months[d.getMonth()];
        document.getElementById('year').textContent = d.getFullYear();

        document.querySelectorAll('.day').forEach(function(day) {
            day.addEventListener('mouseover', function() {
                this.classList.add('hover');
            });
            day.addEventListener('mouseout', function() {
                this.classList.remove('hover');
            });
            day.addEventListener('click', function(event) {
                var targetClass = event.target.className;
                var date = this.getAttribute('data-date');
                var dayId = this.getAttribute('data-id');
                if (targetClass.includes('edit-icon')) {
                    sendTaskRequest('edit', dayId, date);
                } else if (targetClass.includes('delete-icon')) {
                    sendTaskRequest('delete', dayId, date);
                } else if (targetClass.includes('add-icon')) {
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
