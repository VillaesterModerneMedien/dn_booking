export function filterSpecial(blockedRooms) {
    let roomSets = [
        {
            fullRoom: 13,    
            partRooms: [7, 14]
        }
    ];

    let result = [];

    roomSets.forEach(set => {
        if (blockedRooms.some(room => set.partRooms.includes(room))) {
            result.push(set.fullRoom);
        }
        if (blockedRooms.includes(set.fullRoom)) {
            result = result.concat(set.partRooms);
        }
    });
    return result;
}

export function setMinPackage(packageField){
    const minPackage = 5;
    packageField.setAttribute('min', minPackage);
    packageField.value = minPackage;
}

export function checkDateInput(dateInput) {
    function parseDateString(dateString) {
        let [datePart, timePart] = dateString.split(' ');
        let [day, month, year] = datePart.split('.');
        let [hours, minutes, seconds] = timePart.split(':');
        return new Date(year, month - 1, day, hours, minutes, seconds);
    }

    let selectedDate = parseDateString(dateInput);

    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 3);
    return selectedDate >= minDate;
}
