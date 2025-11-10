    window.loadSessions = async function loadSessions() {
    const status  = document.getElementById('status');   
    const results = document.getElementById('results');   
    const level   = document.getElementById('filterLevel').value;
    const teacher = document.getElementById('filterTeacher').value;
    const style   = document.getElementById('filterStyle').value;

    const params = new URLSearchParams();
    if (level)   params.set('level', level);
    if (teacher) params.set('teacher', teacher);
    if (style)   params.set('style', style);

    const qs  = params.toString();
    const url = `/sessions/fragment${qs ? `?${qs}` : ''}`;

    status.classList.remove('d-none');
    status.textContent = 'Chargement…';

    try {
        const res = await fetch(url, { headers: { 'X-Requested-With': 'fetch' }, cache: 'no-store' });
        if (!res.ok) throw new Error('HTTP ' + res.status);

        const html = await res.text(); 
        results.innerHTML = html;      

        status.textContent = '';
        status.classList.add('d-none');
    } catch (e) {
        status.textContent = 'Erreur de chargement';
        console.error(e);
    }
    };

    ['filterId','filterLevel','filterTeacher','filterStyle'].forEach(id =>
    document.getElementById(id)?.addEventListener('input', window.loadSessions)
    );
