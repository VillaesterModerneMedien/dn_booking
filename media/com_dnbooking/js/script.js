/**
 * @copyright  (C) Add your copyright here
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

function disableRooms(date){
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

            blockedRooms.forEach(function(room) {
                let roomElement = document.querySelector(`[data-room-id="${room}"]`);
                roomElement.classList.add('disabled');
            });
        }
    };

    xhr.onerror = function() {
        console.log('Request failed');
    };

    xhr.send('date=' + encodeURIComponent(date));
}
function resetRoomClasses(){
    let roomElements = document.querySelectorAll('.room');
    roomElements.forEach(function(room) {
        room.classList.remove('disabled');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Get the date input element
    const dateInput = document.getElementById('date');

    // Add event listener for change event
    dateInput.addEventListener('change', function() {
        let selectedDate = this.value;
        resetRoomClasses();
        disableRooms(selectedDate);
    });

});
