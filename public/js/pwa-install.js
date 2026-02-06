// PWA Install Logic
let deferredPrompt;
const installBtn = document.getElementById('install-pwa');

window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    // Stash the event so it can be triggered later.
    deferredPrompt = e;
    // Update UI notify the user they can install the PWA
    if (installBtn) {
        installBtn.style.display = 'block';
    }

    console.log('PWA Install prompt saved.');
});

if (installBtn) {
    installBtn.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        // Show the install prompt
        deferredPrompt.prompt();

        // Wait for the user to respond to the prompt
        const { outcome } = await deferredPrompt.userChoice;
        console.log(`User response to the install prompt: ${outcome}`);

        // We've used the prompt, and can't use it again
        deferredPrompt = null;

        // Hide the install button
        installBtn.style.display = 'none';
    });
}

window.addEventListener('appinstalled', (event) => {
    console.log('PWA was installed');
    if (installBtn) installBtn.style.display = 'none';
});
