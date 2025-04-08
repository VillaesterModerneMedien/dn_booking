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

export function doubleDeko(roomID){
    let checkedItem = document.querySelector('.deko.checked');
    if(checkedItem){
        let input = checkedItem.querySelector('input[type="number"]');
        if(roomID === '13'){
            input.value=2;
        }
    }

}
export function setMinPackage(packageField){
    const minPackage = 5;
    packageField.setAttribute('min', minPackage);
    packageField.value = minPackage;
}


