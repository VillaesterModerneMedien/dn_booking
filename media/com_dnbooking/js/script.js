/**
 * @copyright  (C) Add your copyright here
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

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
            if(blocked.times === undefined || blocked.times === ''){
                if(blocked.rooms !== undefined) {
                    let blockedRooms = blocked.rooms;
                    rooms.forEach(function(room) {
                        let roomid = parseInt(room.getAttribute('data-room-id'));
                        if (blockedRooms.includes(roomid)) {
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
            }
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
            let rooms = document.querySelectorAll('.roomList .room');
            if((blocked.times === undefined || blocked.times === '') && date !== '' && time !== ''){
                let step2 = document.querySelectorAll('.step2');
                step2.forEach(function(step){
                    step.classList.remove('hidden');
                });
                updateRoomStatus(date, visitors, time);
            }
            else if(blocked.times === 'timeclosed'){
                setMessage('Zu dieser Zeit haben wir leider nicht geöffnet, bitte versuchen Sie es mit einer anderen Uhrzeit');
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

function disableFields(classname)
{
    let fields = document.querySelectorAll(classname);
    fields.forEach(function(field){
        field.classList.add('hidden');
    });
}
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const personsInput = document.getElementById('visitors');
    const checkBooking = document.getElementById('checkBooking');
    const checkStatus = document.getElementById('checkStatus');
    const inputs = document.querySelectorAll('.checkrooms');

    checkBooking.addEventListener('click', function() {
        renderOrderHTML();
    });
    dateInput.addEventListener('change', function() {
        disableFields('.step2')
    });
    timeInput.addEventListener('change', function() {
        disableFields('.step2')
    });
    personsInput.addEventListener('change', function() {
        updateRoomStatus(dateInput.value, personsInput.value, timeInput.value)
    });
    checkStatus.addEventListener('click', function(event) {
        event.preventDefault();
        checkDate(dateInput.value, personsInput.value, timeInput.value);
    });
});
