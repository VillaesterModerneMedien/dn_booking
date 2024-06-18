function createSingleCheck(extra){
    const input = extra.querySelector('input[type="number"]');
    const label = extra.querySelector('label');
    input.setAttribute('hidden', 'true');
    label.setAttribute('hidden', 'true');

    extra.addEventListener('click', function(){
        const value = input.value;
        if (value === '0') {
            input.value = '1';
            extra.classList.add('checked');
        } else {
            input.value = '0';
            extra.classList.remove('checked');
        }
    });
}

function resetExtraOptions(extraOptions){
    extraOptions.forEach(extra => {
        const input = extra.querySelector('input[type="number"]');
        input.value = '0';
        extra.classList.remove('checked');
    });
}
function createOptionsCheck(extraOptions){
    extraOptions.forEach(extra => {
        const input = extra.querySelector('input[type="number"]');
        const label = extra.querySelector('label');
        input.setAttribute('hidden', 'true');
        label.setAttribute('hidden', 'true');

        extra.addEventListener('click', function(){
            const value = input.value;
            if (value === '0') {
                resetExtraOptions(extraOptions)
                input.value = '1';
                extra.classList.add('checked');
            } else {
                input.value = '0';
                extra.classList.remove('checked');
            }
        });
    })
}
export function setCustomExtras(extras){
    const singleCheck = [14];
    const optionsCheck = [16,17,18,19,20];
    let extraOptions = [];

    extras.forEach(extra => {
        let extraID = extra.getAttribute('data-extra-id');
        if (singleCheck.includes(parseInt(extraID))) {
            createSingleCheck(extra);
        }
        else if (optionsCheck.includes(parseInt(extraID))) {
            if(extraOptions.includes(extra) === false) {
                extraOptions.push(extra);
            }
        }
    });

    createOptionsCheck(extraOptions);
}
