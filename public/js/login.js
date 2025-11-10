// Vérifier les champs requis du formulaire de connexion 
const inputUsername = document.getElementById("username");
const inputPassword = document.getElementById("password");

inputUsername.addEventListener("keyup", validateForm);
inputPassword.addEventListener("keyup", validateForm);

function validateForm() {
    validateRequired(inputUsername);
    validateRequired(inputPassword);
}

function validateRequired(input) {
    if (input.value != '') {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
    }
}