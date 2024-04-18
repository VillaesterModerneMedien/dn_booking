/**
 * @copyright  (C) Add your copyright here
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

let step = 1;
let maxSteps = 1;
let lastValue = 0;

function changeChildrenNumber(input) {
    // Lese den aktuellen Wert des Eingabefelds
    const currentValue = parseInt(input, 10) || 0;

    // Vergleiche den aktuellen Wert mit dem letzten Wert
    if (currentValue > lastValue) {
        // Füge ein neues Formularfeld hinzu
        addFormField();
    } else if (currentValue < lastValue) {
        // Entferne das letzte Formularfeld
        removeFormField();
    }

    // Aktualisiere den letzten Wert
    lastValue = currentValue;
}

function addFormField() {
    // Erstelle die neuen Formularfelder für Name, Geburtsdatum und Geschlecht
    const formGroup = document.createElement('div');
    const examplechild = document.getElementById('childExample');
    let child = lastValue + 1;
    let childHTML = examplechild.innerHTML;
    childHTML = childHTML.replace(/childname/g, 'childname-' + child);
    childHTML = childHTML.replace(/childdate/g, 'childdate-' + child);
    childHTML = childHTML.replace(/childgender/g, 'childgender-' + child);
    formGroup.innerHTML = '<h4>Kind ' + child + '</h4><div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="" id="child-' + child + '">' + childHTML + '</div>';
    // Füge die neuen Felder zum Dokument hinzu
    document.querySelector('#childrenContainer').appendChild(formGroup);
}

function removeFormField() {
    // Wähle den Container, der die Formularfelder enthält
    const childrenContainer = document.querySelector('#childrenContainer');
    if (childrenContainer.children.length > 1) {
        // Entferne das letzte Element
        childrenContainer.removeChild(childrenContainer.lastElementChild);
    }
}

function updateRoomStatus(date, visitors, time){
    // Create an AJAX request
    let xhr = new XMLHttpRequest();

    // Define the URL to the BookingController's getAvailableRooms method
    let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=booking.getBlockedRooms';

    // Open the request
    xhr.open('POST', url, true);

    // Set the request header
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Define what happens on successful data submission
    xhr.onload = function() {
        if (this.status === 200) {
            let blocked = JSON.parse(this.responseText);
            let rooms = document.querySelectorAll('.roomList .room');
                if(blocked.rooms !== undefined) {
                    let blockedRooms = blocked.rooms;
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
                    });
        }
    };

    xhr.onerror = function() {
        console.log('Request failed');
    };

    xhr.send('date=' + encodeURIComponent(date) + '&visitors=' + encodeURIComponent(visitors) + '&time=' + encodeURIComponent(time));
}

function checkDate(date, visitors, time){
    // Create an AJAX request
    let xhr = new XMLHttpRequest();

    // Define the URL to the BookingController's getAvailableRooms method
    let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=booking.getBlockedRooms';

    // Open the request
    xhr.open('POST', url, true);

    // Set the request header
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Define what happens on successful data submission
    xhr.onload = function() {
        if (this.status === 200) {
            let blocked = JSON.parse(this.responseText);
            step = 2;
            let rooms = document.querySelectorAll('.roomList .room');
            if((blocked.times === undefined || blocked.times === '') && date !== '' && time !== ''){
                setStep(step);
                updateRoomStatus(date, visitors, time);
            }
            else if(blocked.times === 'timeclosed'){
                setMessage('Bitte wählen Sie eine andere Uhrzeit, an diesem Tag haben wir von: ' + blocked.start + ' bis ' + blocked.end + ' Uhr geöffnet');
            }
            else if(blocked.times === 'dayclosed'){
                setMessage('An diesem Tag haben wir leider nicht geöffnet, bitte versuchen Sie es mit einem anderen Datum');
            }
            else if(date === '' || time === '') {
                setMessage('Bitte geben Sie ein Datum und eine Uhrzeit ein');
            }
        }
    };

    xhr.onerror = function() {
        console.log('Request failed');
    };

    xhr.send('date=' + encodeURIComponent(date) + '&visitors=' + encodeURIComponent(visitors) + '&time=' + encodeURIComponent(time));
}

function setMessage(message){
    UIkit.notification({
        message: message,
        status: 'danger',
        pos: 'top-center',
        timeout: 5000
    });
}
function handleRoomClick() {
    let radioButton = this.querySelector('input[type="radio"]');
    let rooms = document.querySelectorAll('.roomList .room');
    rooms.forEach(function(room) {
        room.classList.remove('activeRoom');
        console.log(room);
    });
    if (radioButton) {
        radioButton.checked = true;
    }
    this.classList.add('activeRoom');
}

function renderOrderHTML() {
    const form = document.getElementById('bookingForm');
    let formData = new FormData(form);
    let xhr = new XMLHttpRequest();
    let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=booking.getOrderHTML';

    xhr.open('POST', url, true);

    // Set the request header
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Define what happens on successful data submission
    xhr.onload = function() {
        if (this.status === 200) {
            let response = this.responseText;
            UIkit.modal.confirm(response).then(function() {
                console.log('Confirmed.')
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

// Funktion, die aufgerufen wird, um die Sichtbarkeit der Elemente zu aktualisieren
function setStep(newStep) {
    step = newStep;
    let scrollToElement = null;
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
}


document.addEventListener('DOMContentLoaded', function () {

    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const personsInput = document.getElementById('visitors');
    const personsPackageInput = document.getElementById('visitorsPackage');
    const birthdaychildrenInput = document.getElementById('birthdaychildren');
    const checkBooking = document.getElementById('checkBooking');
    const checkStatus = document.getElementById('checkStatus');
    const inputs = document.querySelectorAll('.checkrooms');
    const buttons = document.querySelectorAll("button");
    const uniqueSteps = new Set();
    const elements = document.querySelectorAll('[data-step]');

    elements.forEach(element => {
        const stepValue = element.getAttribute('data-step');
        uniqueSteps.add(stepValue);
    });
    maxSteps = uniqueSteps.size;

    checkBooking.addEventListener('click', function() {
        renderOrderHTML();
    });
    dateInput.addEventListener('change', function() {
        step=1;
        setStep(step);
    });
    timeInput.addEventListener('change', function() {
        step = 1;
        setStep(step);
    });
    personsPackageInput.addEventListener('change', function() {
        updateRoomStatus(dateInput.value, personsPackageInput.value, timeInput.value)
    });
    birthdaychildrenInput.addEventListener('change', function() {
        changeChildrenNumber(birthdaychildrenInput.value);
    });
    checkStatus.addEventListener('click', function(event) {
        event.preventDefault();
        checkDate(dateInput.value, personsPackageInput.value, timeInput.value);
    });
    buttons.forEach(button => {
        button.addEventListener('click', function(event) {
            // Verhindere die Standardaktion des Buttons
            event.preventDefault();
        });
    });
    document.querySelectorAll('[dnnext], [dnprev]').forEach(button => {
        button.addEventListener('click', event => {
            if(event.target.hasAttribute('dnnext')) {
                if (step < maxSteps){
                    step++;
                }
            } else if(event.target.hasAttribute('dnprev')) {
                step = Math.max(1, step - 1);
            }

            setStep(step);
        });
    });
    setStep(step);
});
