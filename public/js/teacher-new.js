//Validation formulaire d'ajout de compte professeur par l'admin
const inputTeacherFirstName = document.querySelector('[data-js="teacherFirstName"]');
const inputTeacherLastName = document.querySelector('[data-js="teacherLastName"]');
const inputTeacherEmail = document.querySelector('[data-js="teacherEmail"]');
const inputTeacherPassword = document.querySelector('[data-js="teacherPassword"]');
const btnValidationTeacherRegister = document.getElementById("btn-validation-teacher-register");

inputTeacherFirstName.addEventListener("keyup", validateRegisterTeacherForm);
inputTeacherLastName.addEventListener("keyup", validateRegisterTeacherForm);
inputTeacherEmail.addEventListener("keyup", validateRegisterTeacherForm);
inputTeacherPassword.addEventListener("keyup", validateRegisterTeacherForm);

function validateRegisterTeacherForm() {
    const teacherFirstNameOk = validateRegisterTeacherRequired(inputTeacherFirstName);
    const teacherLastNameOk = validateRegisterTeacherRequired(inputTeacherLastName);
    const teacherEmailOk = validateRegisterTeacherEmail(inputTeacherEmail);
    const teacherPasswordOk = validateRegisterTeacherPassword(inputTeacherPassword);

    if (teacherFirstNameOk && teacherLastNameOk && teacherEmailOk && teacherPasswordOk) {
        btnValidationTeacherRegister.disabled = false;
    } else {
        btnValidationTeacherRegister.disabled = true;
    }
}

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