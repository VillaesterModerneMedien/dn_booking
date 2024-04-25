/**
 * @copyright  (C) Add your copyright here
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

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

/**
 * Last value of the children number input.
 * @type {number}
 */
let lastValue = 0;

/**
 * Changes the number of children form fields based on the input value.
 * @param {HTMLInputElement} input - The input element for the number of children.
 */
function changeChildrenNumber(input) {
    const currentValue = parseInt(input, 10) || 0;

    if (currentValue > lastValue) {
        addFormField();
    } else if (currentValue < lastValue) {
        removeFormField();
    }

    lastValue = currentValue;
}


/**
 * Adds a new child form field.
 */

/*
function addFormField() {

    const formGroup = document.createElement('div');
    const examplechild = document.getElementById('childExample');
    let child = lastValue + 1;
    let childHTML = examplechild.innerHTML;
    childHTML = childHTML.replace(/childname/g, 'childname-' + child);
    childHTML = childHTML.replace(/childdate/g, 'childdate-' + child);
    childHTML = childHTML.replace(/childgender/g, 'childgender-' + child);
    formGroup.innerHTML = '<h4>Kind ' + child + '</h4><div class="uk-grid tm-grid-expand uk-grid-margin" uk-grid="" id="child-' + child + '">' + childHTML + '</div>';

    document.querySelector('#childrenContainer').appendChild(formGroup);
}
*/
/**
 * Removes the last child form field.
 */
/*
function removeFormField() {

    const childrenContainer = document.querySelector('#childrenContainer');
    if (childrenContainer.children.length > 1) {

        childrenContainer.removeChild(childrenContainer.lastElementChild);
    }
}
*/
/**
 * Updates the room status based on the selected date and number of visitors.
 * @param {string} date - The selected date.
 * @param {number} visitors - The number of visitors.
 */

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
                }
                else{
                    let blockedRooms = ['all available'];
                }
                rooms.forEach(function(room) {
                    let roomid = parseInt(room.getAttribute('data-room-id'));
                    if (blocked.rooms.includes(roomid)) {
                        room.classList.add('disabled');
                        //room.input.addAttribute('disabled');
                        room.removeEventListener('click', handleRoomClick);
                    }
                    else {
                        room.classList.remove('disabled');
                        //room.input.removeAttribute('disabled');
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
            let blocked = JSON.parse(this.responseText);
            step = 2;
            let rooms = document.querySelectorAll('.roomList .room');
            if((blocked.times === undefined || blocked.times === '') && date !== '' && time !== ''){
                setStep(step);
                updateRoomStatus(date, visitors);
            }
            else if(blocked.times === 'timeclosed'){
                setMessage(translation.timeclosed + blocked.start + translation.till + blocked.end + translation.opened);
            }
            else if(blocked.times === 'dayclosed'){
                setMessage(translation.dayxclosed );
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
function setMessage(message){
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
        console.log(room);
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

/**
 * Extracts the time from the date and time input field.
 * @returns {string} The extracted time.
 */
function extractTimeFromDateTime() {
    const dateTimeInput = document.getElementById('jform_reservation_date');
    const dateTimeString = dateTimeInput.getAttribute('data-alt-value'); // Get the full date and time string
    const timeString = dateTimeString.split(' ')[1]; // Split the string and get the time part
    return timeString;
}

/**
 * Initializes the reservation process when the DOM is ready.
 */
document.addEventListener('DOMContentLoaded', function () {

    const dateInput = document.getElementById('jform_reservation_date');
    const personsPackageInput = document.getElementById('jform_additional_info__visitorsPackage');
    const birthdaychildrenInput = document.getElementById('jform_additional_info__birthdaychildren-lbl');
    const checkBooking = document.getElementById('checkBooking');
    const checkStatus = document.getElementById('checkStatus');
    //const inputs = document.querySelectorAll('.checkrooms');
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

    personsPackageInput.addEventListener('change', function() {
        updateRoomStatus(dateInput.value, personsPackageInput.value)
    });
    birthdaychildrenInput.addEventListener('change', function() {
        changeChildrenNumber(birthdaychildrenInput.value);
    });
    checkStatus.addEventListener('click', function(event) {
        event.preventDefault();
        checkDate(dateInput.value, personsPackageInput.value);
    });
    buttons.forEach(button => {
        button.addEventListener('click', function(event) {
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
document.addEventListener('DOMContentLoaded', function() {
    const birthdayChildrenInput = document.getElementById('jform_additional_info__birthdaychildren');

    if (!birthdayChildrenInput) {
        console.error('Das Eingabefeld für Geburtstagskinder wurde nicht gefunden.');
        return;
    }

    birthdayChildrenInput.addEventListener('change', function() {
        const numberOfChildren = parseInt(this.value, 10);
        if (isNaN(numberOfChildren)) {
            console.error('Die Eingabe ist keine gültige Zahl.');
            return;
        }

        const subformElement = document.querySelector('joomla-field-subform.subform-repeatable');
        const subformTemplateContent = document.querySelector('.subform-repeatable-template-section').content;
        const currentGroups = subformElement.querySelectorAll('.subform-repeatable-group');
        const currentCount = currentGroups.length;

        // Sicherstellen, dass das Template-Element als Referenzpunkt dient
        const templateElement = document.querySelector('.subform-repeatable-template-section');

        // Gruppen hinzufügen, falls mehr Kinder hinzugefügt werden sollen
        if (numberOfChildren > currentCount) {
            for (let i = currentCount; i < numberOfChildren; i++) {
                let newGroup = document.importNode(subformTemplateContent, true);
                newGroup.querySelector('[data-group]').setAttribute('data-group', `addinfos2_subform${i}`);
                newGroup.querySelectorAll('input, select, label').forEach(element => {
                    const baseName = 'addinfos2_subformX';
                    if (element.id) element.id = element.id.replace(baseName, `addinfos2_subform${i}`);
                    if (element.htmlFor) element.htmlFor = element.htmlFor.replace(baseName, `addinfos2_subform${i}`);
                    if (element.name) element.name = element.name.replace(baseName, `addinfos2_subform${i}`);
                });

                subformElement.insertBefore(newGroup, templateElement);
            }
        }
        // Gruppen entfernen, falls weniger Kinder angezeigt werden sollen
        else if (numberOfChildren < currentCount) {
            for (let i = currentCount; i > numberOfChildren; i--) {
                currentGroups[i - 1].remove();
            }
        }
    });
});
