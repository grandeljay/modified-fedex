/**
 * FedEx
 *
 * @author  Jay Trees <fedex@grandels.email>
 * @link    https://github.com/grandeljay/modified-fedex
 * @package GrandeljayFedex
 */

"use strict";

function DOMContentLoaded() {
    const observerOptions  = {
        'root'      : null,
        'threshold' : 0
    };
    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const textarea = entry.target;

                textarea.setAttribute('readonly', 'readonly');

                const url     = textarea.getAttribute('data-url');
                const factor  = textarea.getAttribute('data-factor');
                const options = {
                    'method'  : 'POST',
                    'headers' : {
                        'Content-Type' : 'application/json',
                        'Accept'       : 'text/html',
                    },
                    'body'    : JSON.stringify({
                        'factor' : factor,
                        'json'   : textarea.value
                    })
                };

                fetch(url, options)
                    .then(response => {
                        return response.text();
                    })
                    .then(html => {
                        textarea.style.display = 'none';
                        textarea.insertAdjacentHTML('afterend', html);
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    })
                    .finally(() => {
                        textarea.removeAttribute('readonly');
                    });
            }
        });
    };
    const observer         = new IntersectionObserver(observerCallback, observerOptions);

    let apiElements = document.querySelectorAll('[data-url]');

    apiElements.forEach(apiElement => {
        observer.observe(apiElement);
    });

    let factor_preview = document.getElementById('factor-preview');

    if (factor_preview) {
        factor_preview.addEventListener('click', function (event) {
            event.preventDefault();

            let input_factor = document.querySelector('[name="factor"]');
            let factor       = input_factor.value;
            let href         = this.getAttribute('href');

            href = href.replace(/factor=[\d\.]+/, 'factor=' + factor);

            window.location.href = href;
        });
    }
}

document.addEventListener('DOMContentLoaded', DOMContentLoaded);

/**
 * Change
 */
function documentChange(event) {
    if (event.target.matches('[data-name]')) {
        let functionElement = event.target.closest('[data-function]');
        let functionName    = functionElement.getAttribute('data-function');
        let functionToCall  = window[functionName];

        if (typeof functionToCall === 'function') {
            functionToCall.call(event.target);
        } else {
            console.error('Function not found:', functionName);
        }
    }
}

document.addEventListener('change', documentChange);

function inputShippingNationalFirstChange() {
    let table     = this.closest('table');
    let tableRows = table.querySelectorAll('tbody > tr');
    let tableData = [];

    let apiElement = this.closest('details').querySelector('[data-url]');

    tableRows.forEach(tableRow => {
        let inputWeightMax   = tableRow.querySelector('[data-name="weight-max"]');
        let inputWeightCosts = tableRow.querySelector('[data-name="weight-costs"]');

        if (tableRow.classList.contains('remove')) {
            return;
        }

        tableData.push({
            'weight-max'   : inputWeightMax.value,
            'weight-costs' : inputWeightCosts.value
        });
    });

    apiElement.value = JSON.stringify(tableData);
}

function inputWeightChange() {
    let table     = this.closest('table');
    let tableRows = table.querySelectorAll('tbody > tr');
    let tableData = [];

    let apiElement = this.closest('details').querySelector('[data-url]');

    tableRows.forEach(tableRow => {
        let inputWeightMax   = tableRow.querySelector('[data-name="weight-max"]');
        let inputWeightCosts = tableRow.querySelector('[data-name="weight-costs"]');

        if (tableRow.classList.contains('remove')) {
            return;
        }

        tableData.push({
            'weight-max'   : inputWeightMax.value,
            'weight-costs' : inputWeightCosts.value
        });
    });

    apiElement.value = JSON.stringify(tableData);
}

function inputSurchargeChange() {
    let table     = this.closest('table');
    let tableRows = table.querySelectorAll('tbody > tr');
    let tableData = [];

    let apiElement = this.closest('details').querySelector('[data-url]');

    tableRows.forEach(tableRow => {
        let inputName     = tableRow.querySelector('[data-name="name"]');
        let inputCosts    = tableRow.querySelector('[data-name="costs"]');
        let inputType     = tableRow.querySelector('[data-name="type"]');
        let inputWeight   = tableRow.querySelector('[data-name="weight"]');
        let inputDateFrom = tableRow.querySelector('[data-name="date-from"]');
        let inputDateTo   = tableRow.querySelector('[data-name="date-to"]');

        if (tableRow.classList.contains('remove')) {
            return;
        }

        tableData.push({
            'name'      : inputName.value,
            'costs'     : inputCosts.value,
            'type'      : inputType.value,
            'weight'    : inputWeight.value,
            'date-from' : inputDateFrom.value,
            'date-to'   : inputDateTo.value,
        });
    });

    apiElement.value = JSON.stringify(tableData);
}

function inputPickPackChange() {
    inputWeightChange.call(this);
}
/** */

/**
 * Click
 */
function documentClick(event) {
    if (event.target.matches('tfoot input[type="button"][data-url]')) {
        inputAddClick.call(event.target);
    }

    if (
        event.target.matches('td button[value="remove"]') ||
        event.target.matches('td button[value="remove"] > img')
    ) {
        inputRemoveClick.call(event.target);
    }
}

document.addEventListener('click', documentClick);

function inputAddClick() {
    const url           = this.getAttribute('data-url');
    const table         = this.closest('table');
    const options       = {
        'method'  : 'GET',
        'headers' : {
            'Content-Type' : 'application/json',
            'Accept'       : 'text/html',
        }
    };

    table.classList.add('loading');

    fetch(url, options)
        .then(response => {
            return response.text();
        })
        .then(html => {
            let tbody = table.querySelector('tbody');

            tbody.insertAdjacentHTML('afterend', html);
        })
        .catch(error => {
            console.error('Fetch error:', error);
        })
        .finally(() => {
            table.classList.remove('loading');
        });
}

function inputRemoveClick() {
    const tr          = this.closest('tr');
    const input       = tr.querySelector('[data-name]');
    const eventChange = new Event('change', {
        'bubbles' : true
    });

    tr.classList.add('remove');
    input.dispatchEvent(eventChange);
    tr.style.display = 'none';
}
