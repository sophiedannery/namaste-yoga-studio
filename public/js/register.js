// ------------------------------------------------------
// Registration form validation 
// ------------------------------------------------------

// Select the input fields 
const inputRegEmail = document.querySelector('[data-js="reg-email"]');
const inputRegFirstName = document.querySelector('[data-js="reg-firstName"]');
const inputRegLastName = document.querySelector('[data-js="reg-lastName"]');
const inputRegPassword = document.querySelector('[data-js="reg-password"]');
const inputRegConfirmPassword = document.querySelector('[data-js="reg-confirmPassword"]');
const btnValidationRegister = document.getElementById('btn-validation-register');

// Add event listeners to trigger validation when the user types
if (inputRegEmail) inputRegEmail.addEventListener("keyup", validateRegisterForm);
if (inputRegFirstName) inputRegFirstName.addEventListener("keyup", validateRegisterForm);
if (inputRegLastName) inputRegLastName.addEventListener("keyup", validateRegisterForm);
if (inputRegPassword) inputRegPassword.addEventListener("keyup", validateRegisterForm);
if (inputRegConfirmPassword) inputRegConfirmPassword.addEventListener("keyup", validateRegisterForm);


// Checks if an input is not empty
function validateRegisterRequired(input) {
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
// Email format validation using a regex
function validateRegisterMail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const mailUser = input.value;
    if (mailUser.match(emailRegex)) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }
}
// Password strength check
function validateRegisterPassword(input) {
    const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/;
    const passwordUser = input.value;
    if (passwordUser.match(passwordRegex)) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }
}
// Confirmation password check
function validateConfirmationPassword(inputPwd, inputConfirPwd) {
    if (inputPwd.value == inputConfirPwd.value) {
        inputConfirPwd.classList.add("is-valid");
        inputConfirPwd.classList.remove("is-invalid");
        return true;
    } else {
        inputConfirPwd.classList.add("is-invalid");
        inputConfirPwd.classList.remove("is-valid");
        return false;
    }
}

// Runs all field validations and toggles the submit button
function validateRegisterForm() {
    const emailOk = validateRegisterMail(inputRegEmail);
    const firstNameOk = validateRegisterRequired(inputRegFirstName);
    const lastNameOk = validateRegisterRequired(inputRegLastName);
    const passwordOk = validateRegisterPassword(inputRegPassword);
    const confirmPasswordOk = validateConfirmationPassword(inputRegPassword, inputRegConfirmPassword);
    // Enable submit only if every check passes
    if (emailOk && firstNameOk && lastNameOk && passwordOk && confirmPasswordOk) {
        btnValidationRegister.disabled = false;
    } else {
        btnValidationRegister.disabled = true;
    }
}