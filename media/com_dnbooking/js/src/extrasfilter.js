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
        extra.classList.add('deko');

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
export function setCustomExtras(extras) {
    const singleCheck = [14];
    const optionsCheck = [16, 17, 18, 19, 20];
    let extraOptions = [];

    // Die ursprüngliche UL-Liste referenzieren
    const originalUl = document.querySelector('.extraList');

    // Ziel-ULs erstellen und die Klassen der ursprünglichen UL übernehmen
    const ulSingleCheck = document.createElement('ul');
    const ulOptionsCheck = document.createElement('ul');

    ulSingleCheck.className = originalUl.className;
    ulOptionsCheck.className = originalUl.className;

    originalUl.parentNode.insertBefore(ulSingleCheck, originalUl.nextSibling);
    ulSingleCheck.parentNode.insertBefore(ulOptionsCheck, ulSingleCheck.nextSibling);

// Joomla Sprachvariablen verwenden
    const singleCheckHeading = document.createElement('h2');
    singleCheckHeading.innerText = JoomlaLang['COM_DNBOOKING_SINGLE_CHECK_LIST'];

    const optionsCheckHeading = document.createElement('h2');
    optionsCheckHeading.innerText = JoomlaLang['COM_DNBOOKING_OPTIONS_CHECK_LIST'];

    ulSingleCheck.parentNode.insertBefore(singleCheckHeading, ulSingleCheck);
    ulOptionsCheck.parentNode.insertBefore(optionsCheckHeading, ulOptionsCheck);

    extras.forEach(extra => {
        let extraID = extra.getAttribute('data-extra-id');
        if (singleCheck.includes(parseInt(extraID))) {
            createSingleCheck(extra);
            ulSingleCheck.appendChild(extra);
        } else if (optionsCheck.includes(parseInt(extraID))) {
            if (!extraOptions.includes(extra)) {
                extraOptions.push(extra);
            }
        }
    });

    extraOptions.forEach(extra => {
        createOptionsCheck(extraOptions);
        ulOptionsCheck.appendChild(extra);
    });
}


