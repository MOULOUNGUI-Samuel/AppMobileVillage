<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from appoinx-app-html.vercel.app/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 15 Jul 2025 08:33:35 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="{{ asset('assets/img/logo1.png') }}"
      type="image/x-icon"
    />
    <title>Sign In - Appoinx HTML App</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#4a2f26">
  </head>
  <body>
    <main class="flex-center h-100">
      <!-- Overlay + Modal -->
<!-- Écran PWA d'installation -->
<div id="installOverlay" class="d-flex justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75" style="z-index: 9999; display: none;">
    <div class="bg-white p-4 rounded shadow text-center" style="max-width: 400px; width: 90%;">
        <!-- Logo/Image -->
        <img src="{{ asset('assets/img/logo_yodi.jpg') }}" alt="Logo YODI EVENTS" class="mb-3" style="max-width: 120px;">

        <h5 class="mb-3">Installer YODI EVENTS</h5>
        <p>Pour une meilleure expérience, vous pouvez ajouter cette application sur votre téléphone.</p>

        <!-- Bouton d'installation -->
        <button id="installBtn" class="btn btn-primary w-100">
            📲 Installer sur mon téléphone
        </button>
    </div>
</div>
      <section class="sign-in-area">
        <h2 class="heading-2">Se connecter</h2>
        <p class="paragraph-small pt-3">
         Accédez à votre compte en toute sécurité. Connectez-vous pour gérer votre expérience personnalisée.
        </p>

        <form class="input-field-area d-flex flex-column gap-4" method="POST" action="{{ route('login') }}">
          @csrf
          <div class="input-field-item">
            <p>E-mail</p>
            <div class="input-field">
              <input type="text" name="email" class="" placeholder="Email" />
            </div>
          </div>
          <div class="input-field-item">
            <p>Mot de passe</p>
            <div
              class="d-flex justify-content-between align-items-center input-field"
            >
              <input type="password" class="" name="password" placeholder="******" />
              <i class="ph ph-eye-closed"></i>
            </div>
          </div>
          <div class="d-flex flex-column gap-8">
            <a
              href="forget-password.html"
              class="d-block text-end fw-semibold"
              >Mot de passe oublié?</a
            >
            <button class="link-button d-block" type="submit" onclick="this.form.submit();">Se connecter</button>
          </div>

          <div class="position-relative continue-with">
            <img
              src="assets/img/line.png"
              class="line-left position-absolute"
              alt=""
            />
            <img
              src="assets/img/line.png"
              class="line-right position-absolute"
              alt=""
            />
            <span class="text-center continue-with">Ou continuer avec</span>
          </div>

          <div class="pt-3 flex-center gap-4 third-party-authentication">
            <button class="item">
              <img src="assets/img/google.svg" alt="" />
            </button>
            <button class="item">
              <img src="assets/img/apple.svg" alt="" />
            </button>
            <button class="item">
              <img src="assets/img/facebook.svg" alt="" />
            </button>
          </div>

          <div class="sign-in-up m-body">
            Vous n'avez pas de compte ?  <a href="sign-up.html">Inscrivez-vous</a> ici
          </div>
        </form>
      </section>
    </main>
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('sw.js');
    }

    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        const overlay = document.getElementById('installOverlay');
        const installBtn = document.getElementById('installBtn');

        // Affiche le bloc d’installation
        overlay.style.display = 'flex';

        installBtn.addEventListener('click', () => {
            deferredPrompt.prompt();

            deferredPrompt.userChoice.then(choice => {
                if (choice.outcome === 'accepted') {
                    console.log("✅ Application installée !");
                    overlay.remove(); // Masquer le bloc
                } else {
                    console.log("❌ Installation refusée.");
                    // Tu peux laisser le bloc affiché si tu veux insister
                }
            });
        });
    });
</script>
    <!-- Js Dependencies -->
    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/service-worker-settings.js') }}"></script>
  </body>

<!-- Mirrored from appoinx-app-html.vercel.app/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 15 Jul 2025 08:33:44 GMT -->
</html>
