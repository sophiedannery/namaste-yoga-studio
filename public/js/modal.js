

// Reservation cancel modal by student
document.addEventListener('DOMContentLoaded', function () {
    const modalEl   = document.getElementById('confirmCancelReservationModal');
    const form      = document.getElementById('cancelReservationForm');
    const tokenEl   = document.getElementById('cancelReservationToken');
    const titleEl   = document.getElementById('confirmCancelReservationLabel');
    const bodyEl    = document.getElementById('confirmCancelReservationBody');

modalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // le bouton qui a ouvert la modale
        const action = button.getAttribute('data-action');
        const token  = button.getAttribute('data-token');
        const title  = button.getAttribute('data-title') || "Confirmer l'annulation";
        const body   = button.getAttribute('data-body')  || "Voulez-vous vraiment annuler ce cours ?";

form.setAttribute('action', action);
tokenEl.value = token;
titleEl.textContent = title;
bodyEl.textContent = body;
});
});

// Reservation confirmation modal by student
document.addEventListener('DOMContentLoaded', function () {
    const modalEl   = document.getElementById('confirmReservationModal');
    const form      = document.getElementById('ReservationForm');
    const tokenEl   = document.getElementById('ReservationToken');
    const titleEl   = document.getElementById('confirmReservationLabel');
    const bodyEl    = document.getElementById('confirmReservationBody');

modalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // le bouton qui a ouvert la modale
        const action = button.getAttribute('data-action');
        const token  = button.getAttribute('data-token');
        const title  = button.getAttribute('data-title') || "Confirmer l'annulation";
        const body   = button.getAttribute('data-body')  || "Voulez-vous vraiment annuler ce cours ?";

form.setAttribute('action', action);
tokenEl.value = token;
titleEl.textContent = title;
bodyEl.textContent = body;
});
});

// Admin: confirm teacher account deletion
document.addEventListener('DOMContentLoaded', function () {
    const modalEl   = document.getElementById('confirmDeleteTeacherModal');
    const form      = document.getElementById('confirmDeleteTeacherForm');
    const tokenEl   = document.getElementById('deleteTeacherToken');
    const titleEl   = document.getElementById('confirmDeleteTeacherLabel');
    const bodyEl    = document.getElementById('confirmDeleteTeacherBody');

modalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // le bouton qui a ouvert la modale
        const action = button.getAttribute('data-action');
        const token  = button.getAttribute('data-token');
        const title  = button.getAttribute('data-title') || "Confirmer l'annulation";
        const body   = button.getAttribute('data-body')  || "Voulez-vous vraiment annuler ce cours ?";

form.setAttribute('action', action);
tokenEl.value = token;
titleEl.textContent = title;
bodyEl.textContent = body;
});
});

// Admin: confirm student account deletion
document.addEventListener('DOMContentLoaded', function () {
    const modalEl   = document.getElementById('confirmDeleteStudentModal');
    const form      = document.getElementById('confirmDeleteStudentForm');
    const tokenEl   = document.getElementById('deleteStudentToken');
    const titleEl   = document.getElementById('confirmDeleteStudentLabel');
    const bodyEl    = document.getElementById('confirmDeleteStudentBody');

modalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // le bouton qui a ouvert la modale
        const action = button.getAttribute('data-action');
        const token  = button.getAttribute('data-token');
        const title  = button.getAttribute('data-title') || "Confirmer l'annulation";
        const body   = button.getAttribute('data-body')  || "Voulez-vous vraiment annuler ce cours ?";

form.setAttribute('action', action);
tokenEl.value = token;
titleEl.textContent = title;
bodyEl.textContent = body;
});
});


// Admin: confirm session cancellation
document.addEventListener('DOMContentLoaded', function () {
    const modalEl   = document.getElementById('confirmCancelSessionAdminModal');
    const form      = document.getElementById('confirmCancelSessionAdminForm');
    const tokenEl   = document.getElementById('cancelSessionAdminToken');
    const titleEl   = document.getElementById('confirmCancelSessionAdminLabel');
    const bodyEl    = document.getElementById('confirmCancelSessionAdminBody');

modalEl.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // le bouton qui a ouvert la modale
        const action = button.getAttribute('data-action');
        const token  = button.getAttribute('data-token');
        const title  = button.getAttribute('data-title') || "Confirmer l'annulation";
        const body   = button.getAttribute('data-body')  || "Voulez-vous vraiment annuler ce cours ?";

form.setAttribute('action', action);
tokenEl.value = token;
titleEl.textContent = title;
bodyEl.textContent = body;
});
});