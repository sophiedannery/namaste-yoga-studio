// Remplissage de la page une fois le DOM prêt
document.addEventListener('DOMContentLoaded', () => {
    loadSessions();

    // Filtres
    const statusSelect  = document.getElementById('status-filter');

    if (statusSelect) {
        statusSelect.addEventListener('change', applyFilters);
    }
});


// Chargement des sessions
async function loadSessions() {
    const statusEl = document.getElementById('session-status');
    const tbody = document.getElementById('session-table-body');

    if (!tbody) {
        console.error('Élément #session-table-body introuvable');
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
        const pastSessions = sessions.filter((session) => {
            if (!session.startAt) {
                return false;
            }
            const startDateObj = new Date(session.startAt);
            return startDateObj < now;
        });

        tbody.innerHTML = '';

        if (!pastSessions.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Aucune session dans votre historique.
                    </td>
                </tr>
            `;
            if (statusEl) statusEl.textContent = '';
            return;
        }

        pastSessions.forEach((session) => {
            const tr = document.createElement('tr');

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

            tr.innerHTML = `
                <td>${session.id ?? ''}</td>
                <td>${startDate}</td>
                <td>${title}</td>
                <td>${capacity}</td>
                <td>${price} €</td>
                <td>${status}</td>
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


// Appliquer les filtres statut
function applyFilters() {
    const statusSelect  = document.getElementById('status-filter');
    const selectedStatus  = statusSelect ? statusSelect.value : '';

    const rows = document.querySelectorAll('#session-table-body tr');

    rows.forEach((row) => {
        const statusCell  = row.children[6]; // Statut

        if (!statusCell) return;

        const statusText  = statusCell.textContent.trim().toUpperCase();

        let matchStatus  = true;

        if (selectedStatus !== '') {
            matchStatus = (statusText === selectedStatus.toUpperCase());
        }

        row.style.display = (matchStatus) ? '' : 'none';
    });
}
