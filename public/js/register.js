const inputRegEmail = document.querySelector('[data-js="reg-email"]');
const inputRegFirstName = document.querySelector('[data-js="reg-firstName"]');
const inputRegLastName = document.querySelector('[data-js="reg-lastName"]');
const inputRegPassword = document.querySelector('[data-js="reg-password"]');
const inputRegConfirmPassword = document.querySelector('[data-js="reg-confirmPassword"]');
const btnValidationRegister = document.getElementById('btn-validation-register');

inputRegEmail.addEventListener("keyup", validateRegisterForm);
inputRegFirstName.addEventListener("keyup", validateRegisterForm);
inputRegLastName.addEventListener("keyup", validateRegisterForm);
inputRegPassword.addEventListener("keyup", validateRegisterForm);
inputRegConfirmPassword.addEventListener("keyup", validateRegisterForm);

function validateRegisterForm() {
    const emailOk = validateRegisterMail(inputRegEmail);
    const firstNameOk = validateRegisterRequired(inputRegFirstName);
    const lastNameOk = validateRegisterRequired(inputRegLastName);
    validateRegisterRequired(inputRegPassword);
    validateRegisterRequired(inputRegConfirmPassword);

    if (emailOk && firstNameOk && lastNameOk) {
        btnValidationRegister.disabled = false;
    } else {
        btnValidationRegister.disabled = true;
    }
}

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