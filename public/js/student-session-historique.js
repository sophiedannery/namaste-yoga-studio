// Remplissage de la page une fois le DOM prêt
document.addEventListener('DOMContentLoaded', () => {
    loadReservations();

});


// Chargement des reservations
async function loadReservations() {

    const statusEl  = document.getElementById('reservation-status');
    const tbody = document.getElementById('reservation-table-body');

    if (!tbody) {
        console.error('Élément #reservation-table-body introuvable');
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
            return bDate - aDate;
        });

        const now = new Date();
        const pastReservations = reservations.filter((reservation) => {
            if (!reservation.session.startAt) {
                return false;
            }
            const startDateObj = new Date(reservation.session.startAt);
            return startDateObj < now;
        });

        tbody.innerHTML = '';

        if (!pastReservations.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Aucune réservation dans votre historique.
                    </td>
                </tr>
            `;
            if (statusEl) statusEl.textContent = '';
            return;
        }

        pastReservations.forEach((reservation) => {

            const tr = document.createElement('tr');

            // GESTION DE LA DATE
            if (!reservation.session?.startAt || !reservation.session?.endAt) {
            return; 
            }

            const startDateObj = new Date(reservation.session.startAt);
            const endDateObj = new Date(reservation.session.endAt);
    
            const dateLabel = startDateObj.toLocaleDateString('fr-FR', {
                dateStyle: 'short'
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
                badgeText = 'Séance passée';
                badgeClasses = 'bg-success text-bg-success';
            }

            tr.innerHTML = `
                <td>${title}</td>
                <td>${dateLabel}</td>
                <td>${timeRangeLabel}</td>
                <td>${price}</td>
                <td>${teacher}</td>
                <td><span class="badge mb-3 reservation-status ${badgeClasses}">
                    ${badgeText}
                </span></td>
            `;

            tbody.appendChild(tr);

            
        });

        if (statusEl) {
            statusEl.textContent = '';
        }

        

    } catch (error) {
        console.error(error);
            if (statusEl) {
                statusEl.textContent = 'Erreur lors du chargement des sessions.';
            }
            if (tbody.innerHTML.trim() === '') {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Impossible de charger les sessions.
                        </td>
                    </tr>
                `;
            }
    }

}

