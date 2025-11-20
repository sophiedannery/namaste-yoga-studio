// Remplissage de la page une fois le DOM prêt
document.addEventListener('DOMContentLoaded', () => {
    loadSessions();

    // Filtres
    const statusSelect  = document.getElementById('status-filter');

    if (statusSelect) {
        statusSelect.addEventListener('change', applyFilters);
    }

    // --- Gestion du modal d'annulation ---
    const modalEl    = document.getElementById('confirmCancelSessionAdminModal');
    const titleEl    = document.getElementById('confirmCancelSessionAdminLabel');
    const bodyEl     = document.getElementById('confirmCancelSessionAdminBody');
    const confirmBtn = document.getElementById('confirmCancelSessionAdminButton');

    // Variables pour garder la session ciblée par le modal
    let cancelModalSessionId = null;
    let cancelModalCard = null;

    if (modalEl && confirmBtn) {
        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const sessionId   = button.getAttribute('data-session-id');
            const title       = button.getAttribute('data-session-title')   || '';
            const date        = button.getAttribute('data-session-date')    || '';

            cancelModalSessionId = sessionId;
            cancelModalCard = button.closest('.session-card');

            // Texte du modal
            titleEl.textContent = "Confirmer l'annulation de ce cours";
            bodyEl.textContent  = `Voulez-vous vraiment annuler le cours "${title}" du ${date} ?`;
        });

        confirmBtn.addEventListener('click', async function () {
            if (!cancelModalSessionId || !cancelModalCard) return;

            try {
                const response = await fetch(`/api/sessions/cancel/${cancelModalSessionId}`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Erreur HTTP : ${response.status}`);
                }

                // Mise à jour du statut dans la card
                const statusEl = cancelModalCard.querySelector('.session-status');
                if (statusEl) {
                    statusEl.textContent = 'CANCELLED';
                    statusEl.classList.remove('bg-success', 'text-bg-success');
                    statusEl.classList.add('bg-secondary', 'text-bg-secondary');
                }

                const button = cancelModalCard.querySelector('.btn-cancel-session');
                if (button) {
                    button.disabled = true;
                }

                // Réappliquer les filtres (si statut filtré)
                applyFilters();

            } catch (error) {
                console.error(error);
                alert("Impossible d'annuler la session pour le moment.");
            }
        });
    }

    // GEstion du modal élèves
    // --- Modal liste des élèves ---
const studentsModalEl = document.getElementById('sessionStudentsModal');
const studentsTitleEl = document.getElementById('sessionStudentsModalLabel');
const studentsListEl  = document.getElementById('sessionStudentsList');

if (studentsModalEl && studentsListEl) {
    studentsModalEl.addEventListener('show.bs.modal', async function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const sessionId   = button.getAttribute('data-session-id');
        const title       = button.getAttribute('data-session-title')   || '';
        const date        = button.getAttribute('data-session-date')    || '';

        // Titre du modal
        studentsTitleEl.textContent = `Élèves inscrits – ${title} (${date})`;

        // État "chargement..."
        studentsListEl.innerHTML = `
            <li class="list-group-item text-muted">Chargement...</li>
        `;

        try {
            const response = await fetch(`/api/sessions/${sessionId}/students`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }

            const students = await response.json();

            if (!students.length) {
                studentsListEl.innerHTML = `
                    <li class="list-group-item text-muted">
                        Aucun élève inscrit pour le moment.
                    </li>
                `;
                return;
            }

            studentsListEl.innerHTML = '';
            students.forEach((student) => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';

                const fullName = `${student.lastName ?? ''} ${student.firstName ?? ''}`.trim();
                const email    = student.email ?? '';

                li.innerHTML = `
                    <span>
                        <strong>${fullName || 'Élève'}</strong>
                        ${email ? `<br><small class="text-muted">${email}</small>` : ''}
                    </span>
                `;

                studentsListEl.appendChild(li);
            });

        } catch (error) {
            console.error(error);
            studentsListEl.innerHTML = `
                <li class="list-group-item text-danger">
                    Impossible de charger la liste des élèves.
                </li>
            `;
        }
    });
}


});


// Chargement des sessions
async function loadSessions() {
    const statusEl  = document.getElementById('session-status');
    const container = document.getElementById('session-cards-container');

    if (!container) {
        console.error('Élément #session-cards-container introuvable');
        return;
    }

    if (statusEl) {
        statusEl.textContent = 'Chargement...';
    }

    try {
        const response = await fetch('/api/sessions/my', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const sessions = await response.json();

        const now = new Date();
        const upcomingSessions = sessions.filter((session) => {
            if (!session.startAt) {
                return false;
            }
            const startDateObj = new Date(session.startAt);
            return startDateObj > now;
        });

        container.innerHTML = '';

        if (!upcomingSessions.length) {
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    Aucune session à venir.
                </div>
            `;
            if (statusEl) statusEl.textContent = '';
            return;
        }

        upcomingSessions.forEach((session) => {
            const startDate = session.startAt
                ? new Date(session.startAt).toLocaleString('fr-FR', {
                    dateStyle: 'short',
                    timeStyle: 'short'
                })
                : 'À planifier';

            const price    = session.price ?? '—';
            const capacity = session.capacity ?? '—';
            const status   = session.status ?? '—';
            const title    = session.classType?.title ?? '—';
            const room    = session.room?.nameRoom ?? '—';

            const isCancelled = String(status).toUpperCase() === 'CANCELLED';

            const card = document.createElement('div');
            card.className = 'card shadow-sm session-card';

            card.innerHTML = `
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                    <div>
                        <h3 class="h5 mb-1">${title}</h3>
                        <p class="mb-1">
                            ${startDate}
                        </p>
                        
                    </div>
                    <div>
                        <p class="mb-1">
                            <strong>Places :</strong> ${capacity}
                        </p>
                        <p class="mb-0">
                            <strong>Tarif :</strong> ${price} €
                        </p>
                        <p class="mb-0">
                            <strong>Salle :</strong> ${room}
                        </p>
                        
                    </div>
                    <div class="text-md-end d-flex flex-column align-items-md-end align-items-start gap-2">
                        <span class="badge session-status ${
                            isCancelled ? 'bg-secondary text-bg-secondary' : 'bg-success text-bg-success'
                        }">
                            ${status === 'CANCELLED' ? 'Séance annulée' : 'Séance confirmée'}
                        </span>
                        <button 
                            class="btn btn-sm btn-secondary btn-students-session me-1"
                            data-session-id="${session.id}"
                            data-session-title="${title}"
                            data-session-date="${startDate}"
                            data-bs-toggle="modal"
                            data-bs-target="#sessionStudentsModal"
                        >
                            Liste des inscrits
                        </button>
                        <button 
                            class="btn btn-sm btn-outline-danger btn-cancel-session"
                            data-session-id="${session.id}"
                            data-session-title="${title}"
                            data-session-date="${startDate}"
                            data-bs-toggle="modal"
                            data-bs-target="#confirmCancelSessionAdminModal"
                            ${isCancelled ? 'disabled' : ''}
                        >
                            Annuler
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(card);
        });

        if (statusEl) {
            statusEl.textContent = '';
        }

        // Appliquer les filtres une première fois (au cas où un statut serait déjà sélectionné)
        applyFilters();

    } catch (error) {
        console.error(error);
        if (statusEl) {
            statusEl.textContent = 'Erreur lors du chargement des sessions.';
        }
        container.innerHTML = `
            <div class="text-center text-danger py-5">
                Impossible de charger les sessions.
            </div>
        `;
    }
}


// Appliquer les filtres par statut
function applyFilters() {
    const statusSelect   = document.getElementById('status-filter');
    const selectedStatus = statusSelect ? statusSelect.value : '';

    const cards = document.querySelectorAll('.session-card');

    cards.forEach((card) => {
        const statusEl = card.querySelector('.session-status');
        if (!statusEl) return;

        const statusText = statusEl.textContent.trim().toUpperCase();
        let matchStatus  = true;

        if (selectedStatus !== '') {
            matchStatus = (statusText === selectedStatus.toUpperCase());
        }

        card.style.display = matchStatus ? '' : 'none';
    });
}
