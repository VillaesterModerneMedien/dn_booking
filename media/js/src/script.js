import { filterSpecial,doubleDeko } from "./sindelfingen.js";
import { setCustomExtras } from "./extrasfilter.js";
import { checkTimeslot, setQuarters, seperateDate, getAvailableTimeslot, removeOptions } from "./timeslots.js";


/**
 * @copyright  (C) Add your copyright here
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * State if chosen date and time is valid
 * @type {boolean}
 */
let dateValid = false;

/**
 * Current step in the booking process.
 * @type {number}
 */
let step = 1;

/**
 * Maximum number of steps in the booking process.
 * @type {number}
 */
let maxSteps = 1;

let roomID = 'null';

/*
 * Updates the room status based on the selected date and number of visitors.
 * @param {string} date - The selected date.
 * @param {number} visitors - The number of visitors.
 */
function setMinPackage(packageField){
    const minPackage = 5;
    packageField.setAttribute('min', minPackage);
    packageField.value = minPackage;
}

function updateRoomStatus(date, visitors){
    let xhr = new XMLHttpRequest();

    const time = extractTimeFromDateTime();
    let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=reservation.getBlockedRooms';

    xhr.open('POST', url, true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status === 200) {
            let blocked = JSON.parse(this.responseText);
            let rooms = document.querySelectorAll('.roomList .room');
                if(blocked.rooms !== undefined) {
                    let blockedRooms = blocked.rooms;
                    blocked.rooms = blocked.rooms.concat(filterSpecial(blockedRooms)); //Spezialfilter für geteilte Räume->siehe externe JS-Datei
                }
                else{
                    let blockedRooms = ['all available'];
                }
                rooms.forEach(function(room) {
                    let roomid = parseInt(room.getAttribute('data-room-id'));
                    if (blocked.rooms.includes(roomid)) {
                        room.classList.add('disabled');
                        room.removeEventListener('click', handleRoomClick);
                    }
                    else {
                        room.classList.remove('disabled');
                        room.removeEventListener('click', handleRoomClick);
                        room.addEventListener('click', handleRoomClick);
                    }
                    if(blocked.isHolidayOrWeekend === 1 || blocked.isHigherPrice == "1")
                    {
                        room.classList.add('holiday')
                        room.classList.remove('regular')
                    }
                    else {
                        room.classList.add('regular')
                        room.classList.remove('holiday')
                    }
                });
        }
    };

    xhr.onerror = function() {
        console.log('Request failed');
    };

    xhr.send('date=' + encodeURIComponent(date) + '&visitors=' + encodeURIComponent(visitors) + '&time=' + encodeURIComponent(time));
}

/**
 * Checks the selected date and number of visitors.
 * @param {string} date - The selected date.
 * @param {number} visitors - The number of visitors.
 */
function checkDate(date, visitors){
    // Create an AJAX request
    let xhr = new XMLHttpRequest();
    let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=reservation.getBlockedRooms';
    let translation = Joomla.getOptions('com_dnbooking.translations');
    const time = extractTimeFromDateTime();

    xhr.open('POST', url, true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status === 200) {
            console.log(this.responseText);
            let blocked = JSON.parse(this.responseText);
            console.log(blocked);

            let rooms = document.querySelectorAll('.roomList .room');
            if((blocked.times === undefined || blocked.times === '') && date !== '' && time !== ''){
                dateValid = true;
                step = 2;
                setStep(step);
                updateRoomStatus(date, visitors);
            }
            else if(blocked.times === 'timeclosed'){
                setMessage(translation.timeclosed + blocked.start + translation.till + blocked.end + translation.opened);
            }
            else if(blocked.times === 'dayclosed'){
                setMessage(translation.dayclosed );
            }
            else if(date === '' || time === '') {
                setMessage(translation.enterdate);
            }
        }
    };

    xhr.onerror = function() {
        console.log('Request failed');
    };

    xhr.send('date=' + encodeURIComponent(date) + '&visitors=' + encodeURIComponent(visitors) + '&time=' + encodeURIComponent(time));
}

/**
 * Displays a message to the user.
 * @param {string} message - The message to display.
 */
export function setMessage(message){
    UIkit.notification({
        message: message,
        status: 'danger',
        pos: 'top-center',
        timeout: 5000
    });
}

/**
 * Handles the click event on a room element.
 */
function handleRoomClick() {
    let radioButton = this.querySelector('input[type="radio"]');
    let rooms = document.querySelectorAll('.roomList .room');
    rooms.forEach(function(room) {
        room.classList.remove('activeRoom');
    });
    if (radioButton) {
        radioButton.checked = true;
    }
    this.classList.add('activeRoom');
}

/**
 * Renders the order HTML and displays it in a modal.
 */
function renderOrderHTML() {
    const form = document.getElementById('reservationForm');
    let formData = new FormData(form);
    let xhr = new XMLHttpRequest();
    let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=reservation.getOrderHTML';
    let translation = Joomla.getOptions('com_dnbooking.translations');

    xhr.open('POST', url, true);

    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (this.status === 200) {
            let response = this.responseText;

            UIkit.modal.confirm(response, {
                i18n: {
                    ok: translation.btn_ok,
                    cancel: translation.btn_cancel
                }
            }).then(function() {
                form.submit();
            }, function () {
                console.log('Rejected.')
            });
        }
    };

    xhr.onerror = function() {
        console.log('Request failed');
    };
    let encodedData = new URLSearchParams(formData).toString();
    xhr.send(encodedData);
}

/**
 * Updates the visibility of the elements based on the current step.
 * @param {number} newStep - The new step.
 */
function setStep(newStep) {
    step = newStep;
    let scrollToElement = null;
    const nextButton = document.getElementById('dnnext');
    document.querySelectorAll('[data-step]').forEach(element => {
        const elementStep = parseInt(element.getAttribute('data-step'), 10);
        if(elementStep <= step) {
            element.style.display = '';
            if(elementStep === step) {
                scrollToElement = element;
            }
        } else {
            element.style.display = 'none';
        }
    });
    if(scrollToElement) {
        scrollToElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    if(step <= 1){
        nextButton.style.display = 'none';
    }
    else {
        nextButton.style.display = 'block';
    }
}

/**
 * Extracts the time from the date and time input field.
 * @returns {string} The extracted time.
 */
function extractTimeFromDateTime() {
    const dateTimeInput = document.getElementById('jform_reservation_date');
    const dateTimeString = dateTimeInput.getAttribute('data-alt-value');
    const timeString = dateTimeString.split(' ')[1];
    return timeString;
}

export function parseDateString(dateString) {
    let [datePart, timePart] = dateString.split(' ');
    let [day, month, year] = datePart.split('.');
    let [hours, minutes, seconds] = timePart.split(':');
    return new Date(year, month - 1, day, hours, minutes, seconds);
}
function formatDate(date) {
    // Verwendung der toLocaleDateString-Methode mit spezifischen Optionen
    return date.toLocaleDateString('de-DE', { day: '2-digit', month: '2-digit', year: 'numeric' });
}
function checkDateInput(dateInput) {
    const options = Joomla.getOptions('com_dnbooking.frontendparams');
    const minDays = parseInt(options.minDate);
    const maxDays = parseInt(options.maxDate);
    let selectedDate = parseDateString(dateInput);
    const today = new Date();
    const min = new Date();
    const max = new Date();

    min.setDate(today.getDate() + minDays);
    max.setDate(today.getDate() + maxDays);

    let minDateString = formatDate(min);
    let maxDateString = formatDate(max);

    if((selectedDate >= min) && (selectedDate <= max))
    {
        return true;
    }

    setMessage('Bitte wählen Sie ein Datum, welches zwischen dem ' + minDateString + ' und dem ' + maxDateString + ' liegt');
    return false;
}

function setMinPackage(packageField){
    const minPackage = 5;
    packageField.setAttribute('min', minPackage);
    packageField.value = minPackage;
}

function checkRequiredFields()
{

    const requiredFields = document.querySelectorAll('.required');
    const radioButtons = document.querySelectorAll('#jform_room_id input[type="radio"]');
    const roomList = document.getElementById('jform_room_id');

    let inputsValid = true;
    let roomsValid = false;

    requiredFields.forEach(function(field){
        if(field.value === ''){
            field.classList.add('errorField');
                field.addEventListener('change', function(){
                field.classList.remove('errorField');
            });
            inputsValid = false;
        }
    });
    for (const radioButton of radioButtons) {
        if (radioButton.checked) {
            roomList.classList.remove('errorField');
            roomsValid = true;
            break;
        }
        else {
            roomList.classList.add('errorField');
        }
    }

    if (roomsValid === true && inputsValid !== false){
        return true;
    }
    return false;
}

function setSubforms() {
    const birthdayChildrenInput = document.getElementById('jform_additional_info__birthdaychildren');
    const childrenContainer = document.getElementById('childrenContainer');

    const numberOfChildren = parseInt(birthdayChildrenInput.value, 10);
    if (isNaN(numberOfChildren)) {
        console.error('Die Eingabe ist keine gültige Zahl.');
        return;
    }

    const subformElement = document.querySelector('joomla-field-subform.subform-repeatable');

    const currentGroups = subformElement.querySelectorAll('.subform-repeatable-group');
    const currentCount = currentGroups.length;

    // Gruppen hinzufügen, falls mehr Kinder hinzugefügt werden sollen
    if (numberOfChildren > currentCount) {
        for (let i = currentCount; i < numberOfChildren; i++) {
            subformElement.addRow();
        }
    }
    // Gruppen entfernen, falls weniger Kinder angezeigt werden sollen
    else if (numberOfChildren < currentCount) {
        for (let i = currentCount; i > numberOfChildren; i--) {
            const row = currentGroups[i - 1];
            subformElement.removeRow(row);
        }
    }
}

/**
 * Initializes the reservation process when the DOM is ready.
 */
document.addEventListener('DOMContentLoaded', function () {
    const radioButtons = document.querySelectorAll('#jform_room_id input[type="radio"]');
    const roomList = document.getElementById('jform_room_id');

    const dateInput = document.getElementById('jform_reservation_date');
    const personsPackageInput = document.getElementById('jform_additional_info__visitorsPackage');
    const birthdayChildrenInput = document.getElementById('jform_additional_info__birthdaychildren');
    const checkBooking = document.getElementById('checkBooking');
    const checkStatus = document.getElementById('checkStatus');
    const buttons = document.querySelectorAll("button");
    const extras = document.querySelectorAll(".extraListItem");
    const nextButton = document.getElementById('dnnext');
    const reservationdateButton = document.getElementById('jform_reservation_date_btn');
    const uniqueSteps = new Set();
    const elements = document.querySelectorAll('[data-step]');

    birthdayChildrenInput.setAttribute('max',5);
    birthdayChildrenInput.setAttribute('min',1);
    birthdayChildrenInput.value = 1;

    nextButton.style.display='none';


    elements.forEach(element => {
        const stepValue = element.getAttribute('data-step');
        uniqueSteps.add(stepValue);
    });
    maxSteps = uniqueSteps.size;

    checkBooking.addEventListener('click', function() {
        if(checkRequiredFields() === true){
            doubleDeko(roomID)
            renderOrderHTML();
        }
        else{
            setMessage('Bitte füllen Sie alle Pflichtfelder aus');
        }
    });

    dateInput.addEventListener('change', function() {
        dateValid = false;
        getAvailableTimeslot(dateInput);
        if(step > 1){
            step=1;
            setStep(step);
        }
    });

    personsPackageInput.addEventListener('change', function() {
        updateRoomStatus(dateInput.value, personsPackageInput.value)
        birthdayChildrenInput.setAttribute('max',this.value);
    });

    birthdayChildrenInput.addEventListener('change', function() {
        if(parseInt(this.value, 10) < 1)
        {
            setMessage('Mindestens ein Geburtstagskind angeben');
            this.value = 1;
        }
        else if (parseInt(this.value, 10) > parseInt(personsPackageInput.value, 10))
        {
            setMessage('Die Anzahl der Geburtstagskinder darf nicht größer sein als die Anzahl der Besucher mit Paket');
            this.value = this.getAttribute('max');
        }
        setSubforms();
    });

    checkStatus.addEventListener('click', function(event) {
        event.preventDefault();

        if (personsPackageInput.value < 5) {
            setMessage('Es müssen mindestens 5 Geburtstagspakete gebucht werden');
            personsPackageInput.value = 5;
        }

        if(checkDateInput(dateInput.getAttribute('data-alt-value')) === false){
            dateValid = false;
        }
        else{
            checkDate(dateInput.value, personsPackageInput.value);
        }
    });

    buttons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
        });
    });


    nextButton.addEventListener('click', event => {
        if (step < maxSteps && dateValid === true){
            step++;
            setStep(step);
        }
        if (dateValid === false){
            setMessage('Bitte zuerst Verfügbarkeit prüfen');
            step = 1;
            setStep(step);
        }
    });


    radioButtons.forEach(function(radioButton){
        radioButton.addEventListener('click', function(){
            roomID = this.getAttribute('value');
            roomList.classList.remove('errorField');
        });
    });

    reservationdateButton.addEventListener('click', event => {
        removeOptions();
    });

    setQuarters(dateInput);
    setStep(step);
    setMinPackage(personsPackageInput);
    setSubforms();
    setCustomExtras(extras);
});

