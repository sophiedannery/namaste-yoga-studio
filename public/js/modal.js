// Modal annulation de session par professeur
document.addEventListener('DOMContentLoaded', function () {
    const modalEl   = document.getElementById('confirmCancelSessionModal');
    const form      = document.getElementById('cancelForm');
    const tokenEl   = document.getElementById('cancelToken');
    const titleEl   = document.getElementById('confirmCancelSessionLabel');
    const bodyEl    = document.getElementById('confirmCancelSessionBody');

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

//Modal annulation de réservation par élève
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

//Modal confirmation de réservation par élève
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