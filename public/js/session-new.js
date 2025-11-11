// ------------------------------------------------------
// Session form validation
// ------------------------------------------------------

// Select the input fields
const selClass = document.querySelector('[data-js="sess-classType"]');
const selRoom = document.querySelector('[data-js="sess-room"]');
const inputStart = document.querySelector('[data-js="sess-startAt"]');
const inputEnd = document.querySelector('[data-js="sess-endAt"]');
const inputCap = document.querySelector('[data-js="sess-capacity"]');
const inputPrice = document.querySelector('[data-js="sess-price"]');
const inputDetails = document.querySelector('[data-js="sess-details"]');
const btnValidationSessionNew = document.getElementById("btn-validation-session-new");

// Add event listeners to trigger validation when the user types
if (selClass) selClass.addEventListener("change", validateSessionForm);
if (selRoom) selRoom.addEventListener("change", validateSessionForm);
if (inputStart) inputStart.addEventListener("change", validateSessionForm);
if (inputEnd) inputEnd.addEventListener("change", validateSessionForm);
if (inputCap) inputCap.addEventListener("input", validateSessionForm);
if (inputPrice) inputPrice.addEventListener("input", validateSessionForm);
if (inputDetails) inputDetails.addEventListener("input", validateSessionForm);

// Set up date startAt
(function initMinDates(){
    if (!inputStart) return;
    const now = new Date(); now.setSeconds(0,0);
    const nowStr = new Date(now.getTime() - now.getTimezoneOffset()*60000)
                    .toISOString().slice(0,16);

    inputStart.min = nowStr;
    if (inputEnd) {
        inputEnd.min = inputStart.value || nowStr;

        // si on change le début, on ajuste le min de fin
        inputStart.addEventListener('change', () => {
        inputEnd.min = inputStart.value || nowStr;
        if (inputEnd.value && inputEnd.value <= inputStart.value) {
            inputEnd.value = ''; // force à choisir une fin valide
        }
        validateSessionForm();
        });
    }
})();


// Checks if an input is not empty
function validateSessionFormRequired(input) {
    if(input.value != '') {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }
}

// Checks the capacity
function validateSessionCapacity(input) {
    if (!input) return true;

    if (input.value >= 1) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }

}


// Checks the price
function validateSessionPrice(input) {
    if (!input) return true;

    if (input.value === '' || input.validity.valid) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }
}

// Checks the start
function isStartNotPast(input) {
    if (!input) return false;
    const now = new Date(); now.setSeconds(0,0);
    const nowStr = new Date(now.getTime() - now.getTimezoneOffset()*60000).toISOString().slice(0,16);

    if (input.value && input.value >= nowStr) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }
}

// Checks the end
function isEndAfterStart(startInput, endInput) {
    if (!startInput || !endInput) return false;
    if (!!endInput.value && !!startInput.value && endInput.value > startInput.value) {
        endInput.classList.add("is-valid");
        endInput.classList.remove("is-invalid");
        return true;
    } else {
        endInput.classList.add("is-invalid");
        endInput.classList.remove("is-valid");
        return false;
    }
}

// Checks details length
function validateDetailsLength(input, max = 500) {
    if (!input) return true;
    if ((input.value || '').length <= max) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }
}


// Runs all field validations and toggles the submit button
function validateSessionForm() {
    const classOk = validateSessionFormRequired(selClass);
    const capacityOk = validateSessionCapacity(inputCap);
    const priceOk = validateSessionPrice(inputPrice);
    const startOk = isStartNotPast(inputStart);
    const endOk = isEndAfterStart(inputStart, inputEnd);
    const detailsOk = validateDetailsLength(inputDetails);

    if(classOk && priceOk && startOk && endOk && detailsOk && capacityOk) {
        btnValidationSessionNew.disabled = false;
    } else {
        btnValidationSessionNew.disabled = true;
    }

}