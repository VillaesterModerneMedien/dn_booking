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
        // Replace 'your_url_here' with the actual URL
        let url = Joomla.getOptions('system.paths').base + '/index.php?option=com_dnbooking&task=booking.showRooms';

        // Open the request
        xhr.open('POST', url, true);

        // Set the request header
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Define what happens on successful data submission
        xhr.onload = function() {
            if (this.status === 200) {
                // Output the result

                document.getElementById('rooms').innerHTML = this.responseText;
            }
        };

        // Define what happens in case of error
        xhr.onerror = function() {
            console.log('Request failed');
        };

        // Send the request with the date data
        xhr.send('date=' + encodeURIComponent(selectedDate));
    });
});