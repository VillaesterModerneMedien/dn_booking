document.addEventListener('DOMContentLoaded', function () {
    let currentDate = new Date();

    let options = Joomla.getOptions('com_dnbooking');
    let text = options.texte;
    let zeiten = options.zeiten;
    let keysZeiten = Object.keys(zeiten);
    let farben = options.farben;
    let keysFarben = Object.keys(farben);
    let customTimes = [];
    let editingDayID = 0;
    let editingDate = '';
    let editingTime = 0;
    let initialized = 0;

    let openingHoursModal = new bootstrap.Modal(document.getElementById('openingHoursModal'), options);
    let modalTitle = document.getElementById('modal-title');
    let modalSelect = document.getElementById('timeSelect');
    currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth());

    checkMonth(currentDate.getMonth());

    function afterAjaxSuccess(response) {
        response.forEach(element => {
            let date = new Date(element.day);
            let day = date.getDate();
            element.dayID = day;
            customTimes[day] = element;
        });
        generateCalendar(currentDate); // Jetzt generieren wir den Kalender, nachdem die Daten verfügbar sind
    }
    function checkMonth(date) {
        date+=1;
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php?option=com_dnbooking&task=openinghours.checkMonth');
        let token = Joomla.getOptions('csrf.token', '');

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        if (token) {
            xhr.setRequestHeader('X-CSRF-Token', token);
        }
        xhr.onload = function() {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                afterAjaxSuccess(response);
            } else {
            }
        };
        xhr.send('date=' + date + '&token=' + token);
    }


    function sendTaskRequest(task, dayId, time) {
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

        xhr.onload = function() {
            if (xhr.status === 200) {
               openingHoursModal.hide();
               location.reload();
            } else {
                alert(taskText + ' ' + text.failed);
            }
        };
        xhr.send('dayID=' + dayId + '&opening_time=' + time  + '&token=' + token); // Senden Sie die notwendigen Daten
    }

    //TODO JOOMLA PARAMS, siehe Slack

    function formatDate(d) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }

    function monthDays(d) {
        return new Date(d.getFullYear(), d.getMonth() + 1, 0).getDate();
    }

    function readifyDate(dateString) {
        let dateParts = dateString.split("-");
        let day = dateParts[2];
        let month = dateParts[1];
        let year = dateParts[0];
        return day + "." + month + "." + year;
    }
    function isDateCustom(dateString) {
        return customTimes.some(function(bookedDay) {
            return bookedDay.day === dateString;
        });
    }
    function generateCalendar(d) {
        let month =d.getMonth();

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
                    cal[i].push('<td class="daySpacer"><div class="dayInner">&nbsp;</div></td>');
                } else {
                    let date = new Date(d.getFullYear(), d.getMonth(), day);
                    let dateString = formatDate(date);
                    let customDate = isDateCustom(dateString);

                    let dayId = customTimes[day] ? customTimes[day].id : false;
                    let dayName = details.weekDays[j];
                    let classes ="day";
                    let style = "";

                    if(customDate){
                        let customOpeningtimesColor = farben[keysFarben[customTimes[day].opening_time]].openinghour_color;
                        style ="background-color: " + customOpeningtimesColor;
                        classes += ' customTime';
                    }
                    else{
                        // Den dritten Schlüssel im Objekt erhalten
                        let openingtimesColor = JSON.parse(zeiten[keysZeiten[j]]).openingtimes_color;
                        style ="background-color: " + openingtimesColor;
                    }
                    let icons = '<div class="icons"><span class="icon-edit editOpeningHour" data-id="false" data-custom-date="' + customDate + '" aria-hidden="true"></span></div>';

                    cal[i].push(`<td class="${classes}" style="${style}" data-date="${dateString}" data-day="${details.weekDays[j]}" ${dayId ? 'data-id="' + dayId + '"' : ''}><div
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
            dayElement.addEventListener('click', function(event) {
                let date = dayElement.getAttribute('data-date');
                editingDayID = dayElement.getAttribute('data-id');
                editingDate = date;
                modalTitle.innerText = readifyDate(date);
                if (isDateCustom(date)){
                    let day = new Date(date)
                    day = day.getDate();
                    let options = modalSelect.options;
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].value == 'regular_opening_hours' + customTimes[day].opening_time) {
                            options[i].selected = true;
                            break;
                        }
                    }
                }
                openingHoursModal.show();
            });
        });
    }

    modalSelect.addEventListener('change', function () {
        editingTime = modalSelect.value;
        editingTime = editingTime.replace('regular_opening_hours', '');
    });

    document.getElementById('saveChanges').addEventListener('click', function () {
        if(editingDayID){
            sendTaskRequest('edit', editingDayID, editingTime);
        }
        else{
            sendTaskRequest('add', editingDate, editingTime);
        }
    });

    document.getElementById('left').addEventListener('click', function () {
        document.querySelector('table').innerHTML = '';
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);
        checkMonth(currentDate.getMonth());
    });

    document.getElementById('right').addEventListener('click', function () {
        document.querySelector('table').innerHTML = '';
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1);
        checkMonth(currentDate.getMonth());
    });


});
