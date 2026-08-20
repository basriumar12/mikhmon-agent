<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => {
                console.log('[PWA] Service Worker registered:', reg.scope);
                reg.addEventListener('updatefound', () => {
                    const newWorker = reg.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'activated') {
                            if (confirm('Update tersedia! Muat ulang untuk versi terbaru?')) {
                                window.location.reload();
                            }
                        }
                    });
                });
            })
            .catch(err => console.warn('[PWA] SW registration failed:', err));
    });
}

// Prompt install on Android
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    // Show custom install button
    const installBtn = document.getElementById('pwa-install-btn');
    if (installBtn) {
        installBtn.style.display = 'flex';
        installBtn.addEventListener('click', () => {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((result) => {
                if (result.outcome === 'accepted') {
                    installBtn.style.display = 'none';
                }
                deferredPrompt = null;
            });
        });
    }
});

// Detect if running as installed PWA
if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
    document.body.classList.add('pwa-mode');
}
</script>

<style>
#pwa-install-btn {
    display: none;
    position: fixed;
    bottom: 80px;
    right: 20px;
    z-index: 9999;
    padding: 12px 20px;
    background: linear-gradient(135deg, #6366f1, #3b82f6);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
    align-items: center;
    gap: 8px;
    transition: transform 0.2s, box-shadow 0.2s;
}
#pwa-install-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(99, 102, 241, 0.5);
}
#pwa-install-btn svg {
    width: 18px;
    height: 18px;
}

.pwa-mode .fi-sidebar {
    padding-bottom: 60px;
}
</style>

<button id="pwa-install-btn">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
    </svg>
    Install App
</button>
