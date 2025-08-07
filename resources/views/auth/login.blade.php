<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo1.png') }}" type="image/x-icon" />
    <title>YODI EVENTS</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta name="theme-color" content="#4a2f26">
    <link rel="manifest" href="manifest.json">
</head>

<body style="background-color: #e2dccccd">
    <main class="flex-center h-100">
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
                        <input type="text" name="email" class="" placeholder="Email"
                            style="color: #4b2317;" required/>
                    </div>
                </div>
                <div class="input-field-item mb-2">
                    <p>Mot de passe</p>
                    <div class="d-flex justify-content-between align-items-center input-field bg-light shadow">
                        <input type="password" class="border-0 bg-transparent flex-grow-1" name="password"
                            id="passwordInput" placeholder="******" style="color: #4b2317; outline: none;" required/>
                        <i class="ph ph-eye-closed" id="togglePassword" style="cursor: pointer; color: #4b2317;"></i>
                    </div>
                </div>
                <div class="d-flex flex-column gap-8">
                    <a href="#" class="d-block text-end fw-semibold">Mot de passe oublié ?</a>
                    <button class="link-button d-block shadow btn-action" type="submit" data-loader-target="connecter">Se
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
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js');
        }

        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            const btn = document.createElement('button');
            btn.textContent = '📲 Installer sur mon téléphone !';
            btn.id = 'installBtn';
            document.body.appendChild(btn);

            // Appliquer les styles et animations
            const style = document.createElement('style');
            style.innerHTML = `
          #installBtn {
              position: fixed;
              top: 50px;
              left: 50px;
              padding: 12px 24px;
              background: #5D4037;
              color: white;
              border: none;
              border-radius: 8px;
              font-size: 16px;
              cursor: pointer;
              box-shadow: 0 4px 8px rgba(0,0,0,0.15);
              opacity: 0;
              transform: translateY(20px);
              animation: fadeInUp 1s ease forwards;
              z-index: 9999;
          }

          #installBtn:hover {
              background-color: #5D4037;
              transform: scale(1.05);
              transition: background-color 0.3s, transform 0.3s;
          }

          @keyframes fadeInUp {
              from {
                  opacity: 0;
                  transform: translateY(20px);
              }
              to {
                  opacity: 1;
                  transform: translateY(0);
              }
          }
      `;
            document.head.appendChild(style);

            // Action au clic
            btn.addEventListener('click', () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(choice => {
                    if (choice.outcome === 'accepted') {
                        btn.remove();
                        console.log("✅ L'application YODI EVENTS a été installée !");
                    } else {
                        console.log("❌ Installation refusée.");
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
