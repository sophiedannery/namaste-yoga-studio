
window.loadSessions = async function loadSessions() {
    const status = document.getElementById('status');
    const results = document.getElementById('results');
    const input   = document.getElementById('filterId');
    const selectLevel = document.getElementById('filterLevel');
    const selectTeacher = document.getElementById('filterTeacher');
    const selectStyle = document.getElementById('filterStyle');

    if (!status || !results || !input || !selectLevel || !selectTeacher || !selectStyle) return;

    const idRaw = input.value.trim();
    const level = selectLevel.value;
    const teacher = selectTeacher.value;
    const style = selectStyle.value;

    const params = new URLSearchParams();
    if (idRaw !== '') params.set('id', idRaw);
    if (level !== '') params.set('level', level);
    if (teacher !== '') params.set('teacher', teacher);
    if (style !== '') params.set('style', style);


    const url = `/api/sessions${params.toString() ? '?' + params.toString() : ''}`;

    console.log('filters →', { idRaw, level, teacher, style });
    console.log('URL →', url);

    status.textContent = 'Chargement…';
    results.innerHTML = '';


    try {
        const res = await fetch(url, { cache: 'no-store' });

        if (res.status === 404) { // id demandé mais introuvable
            status.textContent = 'Aucune session trouvée';
            return;
        }
        if (!res.ok) throw new Error('HTTP ' + res.status);

        const data = await res.json();

        if (!Array.isArray(data) || data.length === 0) {
        status.textContent = 'Aucune session trouvée';
        return;
        }

        status.textContent = ''; // on cache le message
        results.innerHTML = data.map(s =>
        `
        <div class="card border">
            <div class="card-body p-4 pb-0 align-items-center">
                <div class="row g-4 align-items-center ">

                    <div class="col-sm-4">
                        <h3>${s.title}</h3>
                        <h4>Heure début - Heure fin</h4>
                        <p class="medium">Date</p>
                    </div>

                    <div class="col-sm-4">
                        <p>
                            <i class="fa-solid fa-users me-2"></i>
                            ${s.capacity}
                            place
                        </p>
                        <p>
                            <i class="fa-solid fa-tag"></i>
                            PRIX
                            €
                        </p>
                        <p>
                            <i class="fa-solid fa-star"></i>
                            Niveau :  ${s.level}
                        </p>
                    </div>

                    <div class="col-sm-4 text-center">
                        <h4 class="mt-2">${s.teacher}</h4>
                    </div>

                   


                </div>
            </div>
        </div>


        `
        ).join('');
    } catch (e) {
        status.textContent = 'Erreur de chargement';
        console.error(e);
    }
};

window.loadSessions();


