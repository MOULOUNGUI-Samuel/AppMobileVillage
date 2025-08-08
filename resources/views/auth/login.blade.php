<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo1.png') }}" type="image/x-icon" />
    <title>YODI EVENTS</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- ============ CORRECTIONS PWA ============ -->
    <meta name="theme-color" content="#4a2f26">
    <!-- Le chemin doit être absolu depuis la racine publique -->
    <link rel="manifest" href="/manifest.json">
    <!-- ========================================= -->
</head>
<style>
    /* Style pour le bouton d'installation (Android) */
#installBtn {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 24px;
    background: #4a2f26; /* Votre couleur de thème */
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    opacity: 0;
    animation: fadeInUp 0.5s 0.5s ease forwards;
    z-index: 9999;
}

/* Style pour la bannière d'instructions (iOS) */
#ios-install-banner {
    position: fixed;
    bottom: 20px;
    left: 10px;
    right: 10px;
    background-color: rgba(40, 40, 40, 0.95);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    color: white;
    padding: 15px;
    border-radius: 12px;
    text-align: center;
    font-size: 15px;
    z-index: 1000;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    animation: fadeInUp 0.5s 0.5s ease forwards;
}

#ios-install-banner .share-icon {
    display: inline-block;
    vertical-align: text-bottom;
    width: 20px;
    height: 20px;
    margin: 0 2px;
}

#close-install-banner {
    position: absolute;
    top: 5px;
    right: 10px;
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    line-height: 1;
    padding: 0;
    cursor: pointer;
    opacity: 0.7;
}

/* Animation commune pour l'apparition */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) translateX(-50%);
    }
    to {
        opacity: 1;
        transform: translateY(0) translateX(-50%);
    }
}
/* Ajustement pour la bannière qui est en pleine largeur */
#ios-install-banner {
    animation-name: slideUpFull;
}
@keyframes slideUpFull {
    from {
        opacity: 0;
        transform: translateY(100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<body style="background-color: #e2dccccd">
    <main class="flex-center h-100">
        <!-- Ce bouton sera utilisé par Android/Chrome -->
<button id="installBtn" class="d-none"></button>

<!-- Cette bannière sera utilisée par iOS -->
<div id="ios-install-banner" class="d-none">
    <button id="close-install-banner" aria-label="Fermer">&times;</button>
    Pour une meilleure expérience, ajoutez cette application à votre écran d'accueil. Appuyez sur 
    <img src="https://img.icons8.com/ios/50/ffffff/share-3.png" alt="Share Icon" class="share-icon"> 
    puis sur "Sur l'écran d'accueil".
</div>
        <section class="sign-in-area">
            <!-- Logo/Image -->
            <img src='{{ asset('assets/img/logo1.png') }}' alt="Logo YODI EVENTS" class="mb-3"
                style="max-width: 150px;">

            <h2 class="heading-2">Se connecter</h2>
            <p class="paragraph-small pt-3">
                Accédez à votre compte en toute sécurité. Connectez-vous pour gérer votre expérience personnalisée.
            </p>

            <form class="input-field-area d-flex flex-column gap-2" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-field-item">
                    <p>E-mail</p>
                    <div class="input-field  bg-light shadow">
                        <input type="text" name="email" class="" placeholder="Email" style="color: #4b2317;"
                            required />
                    </div>
                </div>
                <div class="input-field-item mb-2">
                    <p>Mot de passe</p>
                    <div class="d-flex justify-content-between align-items-center input-field bg-light shadow">
                        <input type="password" class="border-0 bg-transparent flex-grow-1" name="password"
                            id="passwordInput" placeholder="******" style="color: #4b2317; outline: none;" required />
                        <i class="ph ph-eye-closed" id="togglePassword" style="cursor: pointer; color: #4b2317;"></i>
                    </div>
                </div>
                <div class="d-flex flex-column gap-8">
                    <a href="#" class="d-block text-end fw-semibold">Mot de passe oublié ?</a>
                    <button class="link-button d-block shadow btn-action" type="submit"
                        data-loader-target="connecter">Se
                        connecter</button>
                </div>
            </form>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('passwordInput');
            const togglePassword = document.getElementById('togglePassword');

            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                this.className = isPassword ? 'ph ph-eye' : 'ph ph-eye-closed';
            });
        });
    </script>

    <!-- ============ SCRIPT D'INSTALLATION PWA CORRIGÉ ============ -->
    <script>
       // ===============================================
//         SCRIPT PWA COMPLET ET UNIVERSEL
// ===============================================

// Enregistrement robuste du Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('✅ Service Worker enregistré avec succès:', registration.scope);
            })
            .catch(error => {
                console.error('❌ Échec de l\'enregistrement du Service Worker:', error);
            });
    });
}

// Logique d'installation gérant Android et iOS
document.addEventListener('DOMContentLoaded', function() {
    
    // --- Fonctions utilitaires ---
    const isIos = () => /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
    const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

    // Si l'application est déjà installée, on ne fait rien.
    if (isInStandaloneMode()) {
        console.log("🚀 Application lancée en mode standalone.");
        return;
    }

    // --- Logique pour iOS ---
    if (isIos()) {
        const banner = document.getElementById('ios-install-banner');
        if(banner) {
            banner.classList.remove('d-none'); // On affiche la bannière d'instructions
        }

        const closeBtn = document.getElementById('close-install-banner');
        if(closeBtn) {
            closeBtn.addEventListener('click', () => {
                banner.style.display = 'none';
            });
        }
    }

    // --- Logique pour Android & Chrome Desktop ---
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        // L'événement est déclenché, mais on l'empêche si on est sur iOS (double sécurité)
        if (isIos()) {
            return;
        }
        
        // Empêche la mini-infobulle de Chrome de s'afficher
        e.preventDefault();
        // Sauvegarde l'événement pour pouvoir le déclencher plus tard
        deferredPrompt = e;

        // Met à jour l'interface utilisateur pour notifier l'utilisateur qu'il peut installer la PWA
        const installBtn = document.getElementById('installBtn');
        if(installBtn) {
            installBtn.textContent = '📲 Installer sur mon téléphone !';
            installBtn.classList.remove('d-none'); // Affiche notre bouton personnalisé

            installBtn.addEventListener('click', () => {
                // Cache notre bouton
                installBtn.style.display = 'none';
                // Affiche la demande d'installation
                deferredPrompt.prompt();
                
                // Attend le choix de l'utilisateur
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('✅ L\'utilisateur a accepté l\'installation');
                    } else {
                        console.log('❌ L\'utilisateur a refusé l\'installation');
                    }
                    deferredPrompt = null;
                });
            });
        }
    });

    // Écouteur pour savoir quand la PWA a été installée avec succès
    window.addEventListener('appinstalled', () => {
        console.log('🎉 PWA installée avec succès !');
        // Cache le bouton d'installation si l'événement a lieu
        const installBtn = document.getElementById('installBtn');
        if (installBtn) {
            installBtn.style.display = 'none';
        }
        deferredPrompt = null;
    });

});
    </script>
    {{-- <script>
        // 🔌 GESTION CONNEXION PERDUE
        function showOfflinePopup() {
            const existingPopup = document.getElementById('offline-popup');
            if (existingPopup) return;

            const popup = document.createElement('div');
            popup.id = 'offline-popup';
            popup.innerHTML = `
                <div class="alert alert-danger text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                    📡 Connexion perdue.
                </div>
            `;
            document.body.appendChild(popup);
        }

        function showOnlinePopup() {
            const popup = document.createElement('div');
            popup.id = 'online-popup';
            popup.innerHTML = `
                <div class="alert alert-success text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                    ✅ Connexion rétablie.
                </div>
            `;
            document.body.appendChild(popup);
            setTimeout(() => popup.remove(), 4000);
        }

        function removeOfflinePopup() {
            const popup = document.getElementById('offline-popup');
            if (popup) popup.remove();
            showOnlinePopup();
        }

        window.addEventListener('offline', showOfflinePopup);
        window.addEventListener('online', removeOfflinePopup);

        if (!navigator.onLine) {
            showOfflinePopup();
        }

        // 📶 GESTION QUALITÉ RÉSEAU INTERNET
        function checkNetworkQuality() {
            const start = Date.now();
            fetch(window.location.href, {
                    method: 'HEAD',
                    cache: 'no-store'
                })
                .then(() => {
                    const duration = Date.now() - start;
                    let message = '';
                    if (duration < 100) {
                        message = '🚀 Réseau excellent';
                    } else if (duration < 500) {
                        message = '📶 Réseau moyen';
                    } else {
                        message = '🐢 Réseau lent';
                    }

                    const quality = document.createElement('div');
                    quality.className = 'alert alert-info text-center position-fixed bottom-0 start-0 end-0 m-3 shadow';
                    quality.style.zIndex = 9999;
                    quality.innerText = message;
                    document.body.appendChild(quality);
                    setTimeout(() => quality.remove(), 1000);
                });
        }

        setInterval(checkNetworkQuality, 60000); // Test de réseau toutes les 60s

        // ⏱️ GESTION SESSION EXPIRÉE
        function showSessionExpiredPopup() {
            const existingPopup = document.getElementById('session-popup');
            if (existingPopup) return;

            const popup = document.createElement('div');
            popup.id = 'session-popup';
            popup.innerHTML = `
                <div class="alert alert-warning text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                    ⌛ Session expirée. Redirection en cours...
                </div>
            `;
            document.body.appendChild(popup);

            setTimeout(() => {
                window.location.href = "/login";
            }, 3000);
        }

        function checkSessionExpired() {
            fetch(window.location.href, {
                    method: 'HEAD',
                    cache: 'no-store'
                })
                .then(response => {
                    if (response.status === 419 || response.status === 401) {
                        showSessionExpiredPopup();
                    }
                })
                .catch(() => {
                    showOfflinePopup();
                });
        }

        setInterval(checkSessionExpired, 60000); // Vérifie expiration session toutes les 60s
    </script> --}}
    <!-- Js Dependencies -->
    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>
</body>

<!-- Mirrored from appoinx-app-html.vercel.app/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 15 Jul 2025 08:33:44 GMT -->

</html>
