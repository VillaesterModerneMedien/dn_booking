/**
 * @copyright  (C) Add your copyright here
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Function to generate calendar
function generateCalendar(year, month) {
    // Days of the week
    const days = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];

    // First day of the month
    let firstDay = new Date(year, month).getDay();
    firstDay = (firstDay === 0) ? 6 : firstDay - 1; // Adjust for Monday start

    // Last day of the month
    let lastDay = new Date(year, month + 1, 0).getDate();

    // Generate calendar header
    let calendar = '<table class="table"><thead><tr>' + days.map(day => `<th>${day}</th>`).join('') + '</tr></thead><tbody><tr>';

    // Add empty cells for days of the previous month
    for (let i = 0; i < firstDay; i++) {
        calendar += '<td></td>';
    }

    // Add cells for each day of the month
    for (let i = 1; i <= lastDay; i++) {
        if ((i + firstDay) % 7 === 0) {
            calendar += `<td>${i}</td></tr><tr>`;
        } else {
            calendar += `<td>${i}</td>`;
        }
    }

    // Close the last row
    calendar += '</tr></tbody></table>';

    // Insert the calendar into the div
    document.getElementById('calendar').innerHTML = calendar;
}



document.addEventListener('DOMContentLoaded', function () {
    let date = new Date();
    const yearSelect = document.getElementById('yearSelect');
    const monthSelect = document.getElementById('monthSelect');
    generateCalendar(date.getFullYear(), date.getMonth());
    yearSelect.addEventListener('change', function() {
        generateCalendar(this.value, monthSelect.value);
    });
     yearSelect.addEventListener('change', function() {
     generateCalendar(this.value, monthSelect.value);
     });
     monthSelect.addEventListener('input', function() {
     generateCalendar(yearSelect.value, this.value);
     });
});

// Generate the current month's calendar on page load




