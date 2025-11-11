// ------------------------------------------------------
// Login form validation 
// ------------------------------------------------------

// Select the username and password input fields by their IDs
const inputUsername = document.getElementById("username");
const inputPassword = document.getElementById("password");

// Add event listeners to trigger validation when the user types
if (inputUsername) inputUsername.addEventListener("keyup", validateForm);
if (inputPassword) inputPassword.addEventListener("keyup", validateForm);

// Checks both fields when called
function validateForm() {
    validateRequired(inputUsername);
    validateRequired(inputPassword);
}

// Checks if an input is not empty
// Adds or removes Bootstrap validation classes accordingly
function validateRequired(input) {
    if (input.value != '') {
        input.classList.add("is-valid");
        input.classList.remove("is-invalid");
    } else {
        input.classList.add("is-invalid");
        input.classList.remove("is-valid");
    }
}