// Remplissage de la page une fois le DOM prêt
document.addEventListener('DOMContentLoaded', () => {
    loadReservations();

    // --- Gestion du modal d'annulation ---
    const modalEl    = document.getElementById('confirmCancelReservationModal');
    const titleEl    = document.getElementById('confirmCancelReservationLabel');
    const bodyEl     = document.getElementById('confirmCancelReservationBody');
    const confirmBtn = document.getElementById('confirmCancelReservationButton');

    // Variables pour garder la reservation ciblée par le modal
    let cancelModalReservationId = null;
    let cancelModalCard = null;

    if (modalEl && confirmBtn) {
        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const reservationId   = button.getAttribute('data-reservation-id');

            cancelModalReservationId = reservationId;
            cancelModalCard = button.closest('.reservation-card');

            // Texte du modal
            titleEl.textContent = "Confirmer l'annulation de votre réservation";
            bodyEl.textContent  = `Voulez-vous vraiment annuler votre réservation?`;
        });

        confirmBtn.addEventListener('click', async function () {
            if (!cancelModalReservationId || !cancelModalCard) return;

            try {
                const response = await fetch(`/api/reservations/cancel/${cancelModalReservationId}`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }

                // Mise à jour du statut dans la card
                const statusEl = cancelModalCard.querySelector('.reservation-status');
                if (statusEl) {
                    statusEl.textContent = 'Vous avez annulé votre réservation';
                    statusEl.classList.remove('bg-success', 'text-bg-success');
                    statusEl.classList.remove('bg-secondary', 'text-bg-secondary');
                    statusEl.classList.add('bg-warning', 'text-bg-warning');
                }

                const button = cancelModalCard.querySelector('.btn-cancel-reservation');
                if (button) {
                    button.disabled = true;
                }

            } catch (error) {
                console.error(error);
                alert("Impossible d'annuler la réservation pour le moment.");
            }
        });
    }


});


// Chargement des reservations
async function loadReservations() {

    const statusEl  = document.getElementById('reservation-status');
    const container = document.getElementById('reservation-cards-container');

    if (!container) {
        console.error('Élément #reservation-cards-container introuvable');
        return;
    }

    if (statusEl) {
        statusEl.textContent = 'Chargement...';
    }

    try {
        const response = await fetch('/api/reservations/my', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const reservations = await response.json();

        reservations.sort((a, b) => {
            const aDate = new Date(a.session.startAt);
            const bDate = new Date(b.session.startAt);
            return aDate - bDate;
        });

        const now = new Date();
        const upcomingReservations = reservations.filter((reservation) => {
            if (!reservation.session.startAt) {
                return false;
            }
            const startDateObj = new Date(reservation.session.startAt);
            return startDateObj > now;
        });

        container.innerHTML = '';

        if (!upcomingReservations.length) {
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    Aucune réservation à venir.
                </div>
            `;
            if (statusEl) statusEl.textContent = '';
            return;
        }

        upcomingReservations.forEach((reservation) => {

            if (!reservation.session?.startAt || !reservation.session?.endAt) {
            return; 
            }

            const startDateObj = new Date(reservation.session.startAt);
            const endDateObj = new Date(reservation.session.endAt);
    
            const dateLabel = startDateObj.toLocaleDateString('fr-FR', {
                weekday: 'long', 
                day: '2-digit', 
                month: 'long',
                year: 'numeric'
            })

            const startTimeLabel = startDateObj.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            })

            const endTimeLabel = endDateObj.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            })

            const timeRangeLabel = `${startTimeLabel} - ${endTimeLabel}`;

            const price    = reservation.session?.price ?? '—';
            const statusSession   = reservation.session?.status ?? '—';
            const statutReservation   = reservation.statut ?? '—';
            const title    = reservation.session?.classType?.title ?? '—';
            const room    = reservation.session?.room?.nameRoom ?? '—';
            const teacher    = reservation.session?.teacher?.firstName ?? '—';
            const level    = reservation.session?.classType?.level ?? '—';

            const isReservationCancelled = String(statutReservation).toUpperCase() === 'CANCELLED';
            const isSessionCancelled = String(statusSession).toUpperCase() === 'CANCELLED';

            const isCancelled = isSessionCancelled || isReservationCancelled;

            let badgeText;
            let badgeClasses;

            if (isSessionCancelled) {
                badgeText = 'Séance annulée par le studio';
                badgeClasses = 'bg-secondary text-bg-secondary';
            } else if (isReservationCancelled) {
                badgeText = 'Vous avez annulé votre réservation';
                badgeClasses = 'bg-warning text-bg-warning';
            } else {
                badgeText = 'Séance confirmée';
                badgeClasses = 'bg-success text-bg-success';
            }

            const col = document.createElement('div');
            col.className = 'col-sm-6 mb-3';

            const card = document.createElement('div');
            card.className = 'card shadow-sm reservation-card';

            card.innerHTML = `
            <h5 class="card-header">${dateLabel}</h5>
                <div class="card-body">
                <span class="badge mb-3 reservation-status ${badgeClasses}">
                    ${badgeText}
                </span>
                    <h5 class="card-title">${title}</h5>

                    <p class="card-text">${timeRangeLabel}</p>

                    <p class="card-text mb-0">
                            <strong>Tarif :</strong> ${price} €
                        </p>
                    <p class="card-text mb-0">
                            <strong>Salle :</strong> ${room}
                        </p>
                    <p class="card-text mb-0">
                            <strong>Niveau :</strong> ${level}
                        </p>
                    <p class="card-text">
                            <strong>Professeur :</strong> ${teacher}
                        </p>

                    <button 
                            class="btn btn-sm btn-outline-danger btn-cancel-reservation"
                            data-reservation-id="${reservation.id}"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmCancelReservationModal"
                            ${isCancelled ? 'disabled' : ''}
                        >
                            Annuler ma réservation
                        </button>
                        
                </div>
            `;

            col.appendChild(card);
            container.appendChild(col);
        });

        if (statusEl) {
            statusEl.textContent = '';
        }

        

    } catch (error) {
        console.error(error);
        if (statusEl) {
            statusEl.textContent = 'Erreur lors du chargement des réservations.';
        }
        container.innerHTML = `
            <div class="text-center text-danger py-5">
                Impossible de charger les réservations.
            </div>
        `;
    }
}



