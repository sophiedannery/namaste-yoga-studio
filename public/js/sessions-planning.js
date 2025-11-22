// Remplissage de la page une fois le DOM prêt
document.addEventListener('DOMContentLoaded', () => {
    loadSessions();

    // Filtres
    const typeSelect  = document.getElementById('type-filter');
    const teacherSelect = document.getElementById('teacher-filter');
    const levelSelect = document.getElementById('level-filter');
    const resetBtn      = document.getElementById('reset-filters');

    if (typeSelect) {
        typeSelect.addEventListener('change', applyFilters);
    }
    if (teacherSelect) {
        teacherSelect.addEventListener('change', applyFilters);
    }
    if (levelSelect) {
        levelSelect.addEventListener('change', applyFilters);
    }
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            if (typeSelect)    typeSelect.value = '';
            if (teacherSelect) teacherSelect.value = '';
            if (levelSelect)   levelSelect.value = '';

            applyFilters();
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
        const response = await fetch('/api/sessions/show', {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const sessions = await response.json();

        // On filtre pour n'avoir que les sessions à venir
        const now = new Date();
        const upcomingSessions = sessions.filter((session) => {
            if (!session.startAt) {
                return false;
            }
            const startDateObj = new Date(session.startAt);
            return startDateObj > now;
        });

        // On vide le container
        container.innerHTML = '';

        // Message si il n'y a pas de sessions à venir
        if (!upcomingSessions.length) {
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    Aucune session à venir.
                </div>
            `;
            if (statusEl) statusEl.textContent = '';
            return;
        }

        // On crée une carte par session
        upcomingSessions.forEach((session) => {

            // On prépare les variables
            const startDate = session.startAt
                ? new Date(session.startAt).toLocaleString('fr-FR', {
                    dateStyle: 'short',
                    timeStyle: 'short'
                })
                : 'À planifier';
            const price    = session.price ?? '—';
            const capacity = session.capacity ?? '—';
            const title    = session.classType?.title ?? '—';
            const level = session.classType?.level ?? '—';
            const teacherFirstName = session.teacher?.firstName ?? '—';
            const avatarUrl = session.avatarUrl ?? null;
            const avatarSrc = avatarUrl ? `/uploads/photos/${avatarUrl}` : '/photos/avatar.png';

            // On crée la div 
            const card = document.createElement('div');
            // on lui rajoute des classes
            card.className = 'card shadow-sm session-card';

            card.dataset.type    = title.toUpperCase();
            card.dataset.level   = level.toUpperCase();
            card.dataset.teacher = teacherFirstName.toUpperCase();

            // On remplit la carte 
            card.innerHTML = `
                <div class="card-body p-4 pb-0 align-items-center">
                
                    <div class="row g-4 align-items-center">

                        <div class="col-sm-4">
                            <h3>${title}</h3>
                            <h4>
                                ${startDate}
                            <h4>
                        </div>

                        <div class="col-sm-4 align-items-center">
                            <p class="mb-1">
                                <i class="fa-solid fa-users me-2"></i>
                                ${capacity} places
                            </p>
                            <p class="mb-1">
                                <i class="fa-solid fa-star"></i>
                                ${level}
                            </p>
                            <p class="mb-0">
                                <i class="fa-solid fa-tag"></i>
                                ${price} €
                            </p>
                        </div >

                        <div class="col-sm-4 text-center">
                            <img src="${avatarSrc}" 
                            alt="Photo de ${teacherFirstName}" 
                            class="rounded-circle" 
                            style="width: 80px; height: 80px; object-fit: cover;">
                            <h4 class="mt-2">
                                ${teacherFirstName}
                            </h4>
                        </div>

                        <div class="col-12 text-center mt-2">
                            
                            <a href="/session-details/${session.id}" class="btn btn-primary my-3">Détails du cours</a>
                        </div>

                    </div>
                </div>
            `;

            container.appendChild(card);
        });

        buildFiltersOptions();

        if (statusEl) {
            statusEl.textContent = '';
        }

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


function buildFiltersOptions() {
    const typeSelect    = document.getElementById('type-filter');
    const teacherSelect = document.getElementById('teacher-filter');
    const levelSelect   = document.getElementById('level-filter');

    const types    = new Set();
    const teachers = new Set();
    const levels   = new Set();

    const cards = document.querySelectorAll('.session-card');

    cards.forEach((card) => {
        const type    = card.dataset.type;
        const teacher = card.dataset.teacher;
        const level   = card.dataset.level;

        if (type && type !== '—')    types.add(type);
        if (teacher && teacher !== '—') teachers.add(teacher);
        if (level && level !== '—')  levels.add(level);
    });

    function fillSelect(select, values, defaultLabel) {
        if (!select) return;

        select.innerHTML = `<option value="">${defaultLabel}</option>`;

        Array.from(values).sort().forEach((val) => {
            const option = document.createElement('option');
            option.value = val;
            option.textContent = val;
            select.appendChild(option);
        });
    }

    fillSelect(typeSelect, types, 'Tous les cours');
    fillSelect(teacherSelect, teachers, 'Tous les professeurs');
    fillSelect(levelSelect, levels, 'Tous les niveaux');
}


function applyFilters() {
    const typeSelect    = document.getElementById('type-filter');
    const teacherSelect = document.getElementById('teacher-filter');
    const levelSelect   = document.getElementById('level-filter');
    const statusEl      = document.getElementById('session-status');
    const selectedType    = typeSelect ? typeSelect.value.toUpperCase() : '';
    const selectedTeacher = teacherSelect ? teacherSelect.value.toUpperCase() : '';
    const selectedLevel   = levelSelect ? levelSelect.value.toUpperCase() : '';
    const cards = document.querySelectorAll('.session-card');

    let anyVisible = false;

    cards.forEach((card) => {
        const cardType    = (card.dataset.type || '').toUpperCase();
        const cardTeacher = (card.dataset.teacher || '').toUpperCase();
        const cardLevel   = (card.dataset.level || '').toUpperCase();

        let matchType    = true;
        let matchTeacher = true;
        let matchLevel   = true;

        if (selectedType !== '') {
            matchType = (cardType === selectedType);
        }
        if (selectedTeacher !== '') {
            matchTeacher = (cardTeacher === selectedTeacher);
        }
        if (selectedLevel !== '') {
            matchLevel = (cardLevel === selectedLevel);
        }

        const isVisible = (matchType && matchTeacher && matchLevel);
        card.style.display = isVisible ? '' : 'none';

        if (isVisible) {
            anyVisible = true;
        }
    });

    if (statusEl) {
        if (!anyVisible) {
            statusEl.textContent = 'Aucun cours ne correspond à ces filtres. Modifiez vos critères pour voir les cours disponibles.';
        } else {
            statusEl.textContent = '';
        }
    }
}

