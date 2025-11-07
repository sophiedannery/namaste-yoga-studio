    // public/js/sessions.js
    window.loadSessions = async function loadSessions() {
    const status  = document.getElementById('status');   // <p id="status" class="d-none">…</p>
    const results = document.getElementById('results');   // contiendra les cartes

    const id      = document.getElementById('filterId').value.trim();
    const level   = document.getElementById('filterLevel').value;
    const teacher = document.getElementById('filterTeacher').value;
    const style   = document.getElementById('filterStyle').value;


    // Construire proprement ?id=…&level=…&teacher=…&style=…
    const params = new URLSearchParams();
    if (id)      params.set('id', id);
    if (level)   params.set('level', level);
    if (teacher) params.set('teacher', teacher);
    if (style)   params.set('style', style);

    const qs  = params.toString();
    const url = `/sessions/fragment${qs ? `?${qs}` : ''}`;

    // Afficher "Chargement…" pendant la requête
    status.classList.remove('d-none');
    status.textContent = 'Chargement…';

    try {
        // On demande du HTML, pas du JSON
        const res = await fetch(url, { headers: { 'X-Requested-With': 'fetch' }, cache: 'no-store' });
        if (!res.ok) throw new Error('HTTP ' + res.status);

        const html = await res.text(); // <- on lit du texte (HTML)
        results.innerHTML = html;      // <- on remplace le contenu par le fragment reçu

        status.textContent = '';
        status.classList.add('d-none');
    } catch (e) {
        status.textContent = 'Erreur de chargement';
        console.error(e);
    }
    };

    // Quand l’utilisateur touche un filtre, on charge le fragment HTML
    ['filterId','filterLevel','filterTeacher','filterStyle'].forEach(id =>
    document.getElementById(id)?.addEventListener('input', window.loadSessions)
    );
