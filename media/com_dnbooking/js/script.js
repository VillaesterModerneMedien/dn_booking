/**
 * @copyright  (C) Add your copyright here
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

function updateRoomStatus(date, visitors){
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
            let blockedRooms = JSON.parse(this.responseText);
            let rooms = document.querySelectorAll('.roomList .room');
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
    };

    xhr.onerror = function() {
        console.log('Request failed');
    };

    xhr.send('date=' + encodeURIComponent(date) + '&visitors=' + encodeURIComponent(visitors));
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

document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    const personsInput = document.getElementById('visitors');
    const inputs = document.querySelectorAll('.checkrooms');
    const checkBooking = document.getElementById('checkBooking');

    updateRoomStatus(dateInput.value, personsInput.value);

    checkBooking.addEventListener('click', function() {
        renderOrderHTML();
    });


    inputs.forEach(function(input) {
        input.addEventListener('change', function() {
            //resetRoomClasses();
            updateRoomStatus(dateInput.value, personsInput.value);
        });
        input.addEventListener('input', function() {
            //resetRoomClasses();
            updateRoomStatus(dateInput.value, personsInput.value);
        });
    });

});
