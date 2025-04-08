import {parseDateString, setMessage} from './script.js';

export function checkTimeslot(dateInput)  {
    return new Promise((resolve, reject) => {
    let date = seperateDate(dateInput);
    let xhr = new XMLHttpRequest();

    let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=reservation.getTimeslots';

    xhr.open('POST', url, true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status === 200) {
            resolve(this.responseText);
        } else {
            reject('Request failed with status ' + this.status);
        };

        xhr.onerror = function () {
            console.log('Request failed');
        };
    };

    xhr.send('day=' + date[0] + '&month=' + date[1] + '&year=' + date[2] + '&hours=' + date[3] + '&minutes=' + date[4]);
    });
}

export function removeOptions(){
    const selectElement = document.querySelector('.time.time-minutes.form-control.form-select');
    const options = selectElement.querySelectorAll('option');

    options.forEach(option => {
        const value = parseInt(option.value, 10);
        if (value % 15 !== 0) {
            option.remove();
        }
    });
}
export function setQuarters(dateInput){
    let date = parseDateString(dateInput.value);
    let minutes = date.getMinutes();

    date.setSeconds(0);
    date.setMilliseconds(0);
    let remainder = minutes % 15;

    if(remainder >= 8){
        date.setMinutes(minutes + (15 - remainder));
    }
    else {
        date.setMinutes(minutes - remainder);
    }

    let newDate = String(date.getDate()).padStart(2,'0') + '.' + String((date.getMonth()+1)).padStart(2,'0') + '.' + date.getFullYear() + ' ' + date.toTimeString().split(' ',)[0];

    dateInput.value = newDate;
    dateInput.setAttribute('data-alt-value', newDate);
    dateInput.setAttribute('value', newDate);

    return newDate
}

export function getAvailableTimeslot(dateInput) {

    let nextQuarter = setQuarters(dateInput);

      checkTimeslot(dateInput).then(nextSlot => {
        let nextTimeslot = nextSlot + ':00';

        if (nextQuarter !== nextTimeslot) {
            dateInput.value = nextTimeslot;
            dateInput.setAttribute('data-alt-value', nextTimeslot);
            dateInput.setAttribute('value', nextTimeslot);
            setMessage('Der gewählte Zeitpunkt ist nicht verfügbar. Der nächste verfügbare Zeitpunkt wurde ausgewählt.');
        }
    }).catch(error => {
        console.error(error);
    });
}
function seperateDate(dateInput){
    let date = parseDateString(dateInput.value);
    let day = date.getDate();
    let month = date.getMonth()+1;
    let year = date.getFullYear();
    let hours = date.getHours();
    let minutes = date.getMinutes();
    return [day, month, year, hours, minutes];
}
