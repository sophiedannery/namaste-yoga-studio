document.addEventListener('DOMContentLoaded', () => {
    loadProfile();
});


async function loadProfile() {

    const statusEl = document.getElementById('profile-status');
    const firstEl = document.getElementById('profile-firstname');
    const lastEl = document.getElementById('profile-lastname');
    const emailEl = document.getElementById('profile-email');

    if (statusEl) {
        statusEl.textContent = 'Chargement du profil...';
    }

    try {
        const response = await fetch('/api/users/me', {
            method: 'GET',
            headers:{
                'Accept': 'application/json'
            }
        });

        if(!response.ok) {
            if(statusEl) {
                statusEl.textContent = `Erreur lors du chargement (${response.status})`;
            }
            return; 
        }

        const user = await response.json();
        console.log('Profil reçu :', user);

        if (firstEl) firstEl.textContent = user.firstName ?? 'Non renseigné';
        if (lastEl) lastEl.textContent = user.lastName ?? 'Non renseigné';
        if (emailEl) emailEl.textContent = user.email ?? 'Non renseigné';

        if (statusEl) {
            statusEl.textContent = '';
        }
    } catch (error) {
        console.log(error);
        if (statusEl) {
            statusEl.textContent = 'Erreur réseau lors du chargement du profil.';
        }
    }
}