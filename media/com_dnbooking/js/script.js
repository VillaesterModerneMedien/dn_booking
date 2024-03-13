/**
 * @copyright  (C) Add your copyright here
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

document.addEventListener('DOMContentLoaded', function () {
    // Get the date input element
    let dateInput = document.getElementById('date');

    // Add event listener for change event
    dateInput.addEventListener('change', function() {
        // Get the selected date
        let selectedDate = this.value;

        // Create an AJAX request
        let xhr = new XMLHttpRequest();

        // Define the URL to the BookingController's getAvailableRooms method
        let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=booking.showRooms';

        // Open the request
        xhr.open('POST', url, true);

        // Set the request header
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Define what happens on successful data submission
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('rooms').innerHTML = this.responseText;

                // Create and dispatch the dnbookingLoadedRooms event
                let event = new CustomEvent('dnbookingLoadedRooms');
                document.dispatchEvent(event);
            }
        };


        xhr.onerror = function() {
            console.log('Request failed');
        };

        xhr.send('date=' + encodeURIComponent(selectedDate));
    });



    // Add event listener for custom event
    document.addEventListener('dnbookingLoadedRooms', attachRoomListItemHandler);

    // Callback function that is executed after loading the LI elements
    function attachRoomListItemHandler() {
        const roomListItems = document.querySelectorAll('li.roomlistRoom');
        roomListItems.forEach(item => {
            console.log('item', item);
            item.addEventListener('click', handleRoomListItemClick);
        });
    }

    // Callback function for the event listener
    // Callback function for the event listener
    function handleRoomListItemClick(e) {

        const roomListItems = document.querySelectorAll('li.roomlistRoom');

        roomListItems.forEach(item => {
            item.classList.remove('activeRoom');
        });

        const liElement = e.target.closest('li.roomlistRoom');

        if (liElement) {
            liElement.classList.add('activeRoom');

            // Create an AJAX request
            let xhr = new XMLHttpRequest();

            // Define the URL to the BookingController's setCustomerForm method
            let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=booking.setCustomerForm';

            // Open the request
            xhr.open('GET', url, true);

            // Define what happens on successful data submission
            xhr.onload = function() {
                if (this.status === 200) {
                    // Output the result into the 'customer' div
                    document.getElementById('customer').innerHTML = this.responseText;
                }
            };

            xhr.onerror = function() {
                console.log('Request failed');
            };

            // Send the request
            xhr.send();
        }
    }
});
