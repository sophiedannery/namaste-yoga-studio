// Remplissage de la page une fois le DOM prêt
document.addEventListener('DOMContentLoaded', () => {
    loadSessions();

    // Filtres
    const teacherSelect = document.getElementById('teacher-filter');
    const statusSelect  = document.getElementById('status-filter');

    if (teacherSelect) {
        teacherSelect.addEventListener('change', applyFilters);
    }

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
        const response = await fetch('/api/sessions/show', {
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
                        Aucune session.
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
            const teacherFirstName = session.teacher?.firstName ?? '—';

            tr.innerHTML = `
                <td>${session.id ?? ''}</td>
                <td>${startDate}</td>
                <td>${title}</td>
                <td>${teacherFirstName}</td>
                <td>${capacity}</td>
                <td>${price} €</td>
                <td>${status}</td>
            `;

            tbody.appendChild(tr);
        });

        buildTeacherFilterOptions();

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


// Remplir le filtre teacher
function buildTeacherFilterOptions() {
    const select = document.getElementById('teacher-filter');
    if (!select) return;

    select.innerHTML = '<option value="">Tous les professeurs</option>';

    const names = new Set();

    const rows = document.querySelectorAll('#session-table-body tr');
    rows.forEach((row) => {
        const teacherCell = row.children[3];
        if (!teacherCell) return;

        const name = teacherCell.textContent.trim();
        if (name && name !== '—') {
            names.add(name);
        }
    });

    names.forEach((name) => {
        const option = document.createElement('option');
        option.value = name;
        option.textContent = name;
        select.appendChild(option);
    });
}


// Appliquer les filtres teacher et statut
function applyFilters() {
    const teacherSelect = document.getElementById('teacher-filter');
    const statusSelect  = document.getElementById('status-filter');

    const selectedTeacher = teacherSelect ? teacherSelect.value : '';
    const selectedStatus  = statusSelect ? statusSelect.value : '';

    const rows = document.querySelectorAll('#session-table-body tr');

    rows.forEach((row) => {
        const teacherCell = row.children[3]; // Professeur
        const statusCell  = row.children[6]; // Statut

        if (!teacherCell || !statusCell) return;

        const teacherName = teacherCell.textContent.trim();
        const statusText  = statusCell.textContent.trim().toUpperCase();

        let matchTeacher = true;
        let matchStatus  = true;

        if (selectedTeacher !== '') {
            matchTeacher = (teacherName === selectedTeacher);
        }

        if (selectedStatus !== '') {
            matchStatus = (statusText === selectedStatus.toUpperCase());
        }

        row.style.display = (matchTeacher && matchStatus) ? '' : 'none';
    });
}
