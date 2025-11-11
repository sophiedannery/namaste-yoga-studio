// ------------------------------------------------------
// Load and render session list based on current filters
// ------------------------------------------------------
    
    window.loadSessions = async function loadSessions() {
    const status  = document.getElementById('status');   
    const results = document.getElementById('results');  

    // Read current filter values 
    const level   = document.getElementById('filterLevel').value;
    const teacher = document.getElementById('filterTeacher').value;
    const style   = document.getElementById('filterStyle').value;

    // Build query string from non-empty filters
    const params = new URLSearchParams();
    if (level)   params.set('level', level);
    if (teacher) params.set('teacher', teacher);
    if (style)   params.set('style', style);

    const qs  = params.toString();
    const url = `/sessions/fragment${qs ? `?${qs}` : ''}`;

    // Show loading state
    status.classList.remove('d-none');
    status.textContent = 'Chargement…';

    try {
        // Fetch the HTML fragment (server should return a partial)
        const res = await fetch(url, { headers: { 'X-Requested-With': 'fetch' }, cache: 'no-store' });
        if (!res.ok) throw new Error('HTTP ' + res.status);

        // Insert HTML fragment into the results container
        const html = await res.text(); 
        results.innerHTML = html;      

        // Hide status after successful render
        status.textContent = '';
        status.classList.add('d-none');
    } catch (e) {
        // Display a friendly error and keep the console details for debugging
        status.textContent = 'Erreur de chargement';
        console.error(e);
    }
    };

    // Attach live filtering: re-run on any input change
    ['filterId','filterLevel','filterTeacher','filterStyle'].forEach(id =>
    document.getElementById(id)?.addEventListener('input', window.loadSessions)
    );
