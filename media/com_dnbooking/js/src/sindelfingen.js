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
