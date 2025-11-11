// ------------------------------------------------------
// Teacher Registration form validation 
// ------------------------------------------------------

// Select the input fields
const inputTeacherFirstName = document.querySelector('[data-js="teacherFirstName"]');
const inputTeacherLastName = document.querySelector('[data-js="teacherLastName"]');
const inputTeacherEmail = document.querySelector('[data-js="teacherEmail"]');
const inputTeacherPassword = document.querySelector('[data-js="teacherPassword"]');
const btnValidationTeacherRegister = document.getElementById("btn-validation-teacher-register");

// Add event listeners to trigger validation when the user types
if (inputTeacherFirstName) inputTeacherFirstName.addEventListener("keyup", validateRegisterTeacherForm);
if (inputTeacherLastName) inputTeacherLastName.addEventListener("keyup", validateRegisterTeacherForm);
if (inputTeacherEmail) inputTeacherEmail.addEventListener("keyup", validateRegisterTeacherForm);
if (inputTeacherPassword) inputTeacherPassword.addEventListener("keyup", validateRegisterTeacherForm);

// Runs all field validations and toggles the submit button
function validateRegisterTeacherForm() {
    const teacherFirstNameOk = validateRegisterTeacherRequired(inputTeacherFirstName);
    const teacherLastNameOk = validateRegisterTeacherRequired(inputTeacherLastName);
    const teacherEmailOk = validateRegisterTeacherEmail(inputTeacherEmail);
    const teacherPasswordOk = validateRegisterTeacherPassword(inputTeacherPassword);

    // Enable submit only if every check passes
    if (teacherFirstNameOk && teacherLastNameOk && teacherEmailOk && teacherPasswordOk) {
        btnValidationTeacherRegister.disabled = false;
    } else {
        btnValidationTeacherRegister.disabled = true;
    }
}

// Checks if an input is not empty
function validateRegisterTeacherRequired(input) {
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
function validateRegisterTeacherEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailTeacher = input.value;
    if (emailTeacher.match(emailRegex)) {
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
function validateRegisterTeacherPassword(input) {
    const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/;
    const passwordTeacher = input.value;
    if (passwordTeacher.match(passwordRegex)) {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
        return true;
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
        return false;
    }
}