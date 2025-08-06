<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from appoinx-app-html.vercel.app/profile-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 15 Jul 2025 08:33:16 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="assets/img/fav-logo.png"
      type="image/x-icon"
    />
    <title>Profile Settings - Appoinx HTML App</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="manifest" href="manifest.json" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>
  <body>
    <!-- Preloader Start -->
    <div class="preloader active">
      <div class="flex-center h-100 bgMainColor">
        <div class="main-container flex-center h-100 flex-column">
          <div class="wave-animation">
            <img src="assets/img/fav.png" alt="" />
            <div class="waves wave-1"></div>
            <div class="waves wave-2"></div>
            <div class="waves wave-3"></div>
          </div>

          <div class="pt-8">
            <img src="assets/img/logo.png" alt="" />
          </div>
        </div>
      </div>
    </div>
    <!-- Preloader End -->
    <main class="">
      <section
        class="px-6 pt-8 notification-top-area d-flex justify-content-between align-items-center"
      >
        <div class="d-flex justify-content-start align-items-center gap-4 py-3">
            <a href="{{ route('dashboard') }}" class="back-button flex-center"><i class="ph ph-caret-left"></i></a>
          </a>
          <h2>Approbations en attente</h2>
        </div>
        <!-- <div class="more-btn">
          <i class="ph-thin ph-dots-three-circle"></i>
        </div> -->
      </section>

      <section class="settings-area px-6 pt-6">
        <!-- <div
          class="profile-info d-flex justify-content-between align-items-center"
        >
          <div class="d-flex justify-content-start align-items-center gap-4">
            <div class="">
              <img src="assets/img/profile-settings-img.png" alt="" />
            </div>
            <div class="">
              <h3 class="heading-3 text-white">Nayeem Raj</h3>
              <p class="text-white pt-2 fw-light">+5463 516 513 51</p>
            </div>
          </div>
          <a href="edit-profile.html" class="edit-icon flex-center">
            <i class="ph ph-pencil-simple-line"></i>
          </a>
        </div> -->

        <div class="pt-8 d-flex flex-column gap-6">
          <a
            href="notification-setting.html"
            class="d-flex justify-content-between align-items-center setting-item text-dark"
          >
            <div class="d-flex justify-content-start align-items-center gap-4">
              <div class="flex-center">
                <i class="ph ph-bell setting-icon"></i>
              </div>
              <p class="fs-5 fw-semibold">Approbations financières (Sorties)</p>
            </div>
            <div class="chevron_icon">
              <i class="ph ph-caret-right"></i>
            </div>
          </a>
          <a
            href="add-card.html"
            class="d-flex justify-content-between align-items-center setting-item text-dark"
          >
            <div class="d-flex justify-content-start align-items-center gap-4">
              <div class="flex-center">
                <i class="ph ph-cardholder setting-icon"></i>
              </div>
              <p class="fs-5 fw-semibold">Approbations des réservations</p>
            </div>
            <div class="chevron_icon">
              <i class="ph ph-caret-right"></i>
            </div>
          </a>
          <div
            class="d-flex justify-content-between align-items-center setting-item"
          >
            <div class="d-flex justify-content-start align-items-center gap-4">
              <div class="flex-center">
                <i class="ph ph-eye setting-icon"></i>
              </div>
              <p class="fs-5 fw-semibold">Dark Mode</p>
            </div>
            <div class="switch">
              <input id="switch-rounded" type="checkbox" />
              <label for="switch-rounded"></label>
            </div>
          </div>

          <!-- <div
            class="logoutModalButton d-flex justify-content-between align-items-center setting-item"
          >
            <div class="d-flex justify-content-start align-items-center gap-4">
              <div class="flex-center">
                <i class="ph ph-users-three setting-icon logout"></i>
              </div>
              <p class="fs-5 fw-semibold logout-text">Logout</p>
            </div>
            <div class="chevron_icon">
              <i class="ph ph-caret-right"></i>
            </div>
          </div> -->
          <div onclick="openLogoutModal()"
            class="logoutModalButton d-flex justify-content-between align-items-center setting-item" style="cursor: pointer;">
            <div class="d-flex justify-content-start align-items-center gap-4">
              <div class="flex-center">
                <i class="ph ph-users-three setting-icon logout"></i>
              </div>
              <p class="fs-5 fw-semibold logout-text">Logout</p>
            </div>
            <div class="chevron_icon">
              <i class="ph ph-caret-right"></i>
            </div>
          </div>

        </div>
      </section>
    </main>

    <!-- Footer Menu Start -->
    <div class="footer-menu-area">
      <div class="footer-menu flex justify-content-center align-items-center">
        <div
          class="d-flex justify-content-between align-items-center px-6 h-100"
        >
          <a href="home.html" class="flex-center"
            ><i class="ph ph-house link-item"></i
          ></a>
          <a href="my-appoinment.html" class="flex-center"
            ><i class="ph ph-calendar link-item"></i
          ></a>
          <a href="chat-list.html" class="flex-center"
            ><i class="ph ph-messenger-logo link-item"></i
          ></a>
          <a href="profile-settings.html" class="flex-center"
            ><i class="ph-fill ph-user link-item active"></i
          ></a>
        </div>

        <div class="plus-icon position-absolute">
          <div class="position-relative">
            <img src="assets/img/plus-icon-bg.png" class="" alt="" />
            <i class="ph ph-plus"></i>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer Menu End -->

    <!-- Logout Modal Start -->
    <div class="">
      <div class="logout-modal-bg" id="logoutModalBg"></div>
      <div
        class="px-6 pt-17 logout-modal-area pb-8 logoutModalClose"
        id="logoutModal"
      >
        <div
          class="sort-options w-100 text-center px-10 d-flex flex-column justify-content-center align-items-center"
        >
          <h2 class="heading-2 y300-color">Log Out</h2>
          <div class="custom-border-area position-relative my-5 w-100">
            <div class="line-horizontal"></div>
          </div>
          <p>Are you sure you want to log out</p>
          <div
            class="w-100 pt-8 d-flex justify-content-between align-items-center gap-4"
          >
            <div class="gender-button" id="cancelButton">
              <button>Cancel</button>
            </div>
            <a
              href="sign-in.html"
              class="gender-button active"
              id="logoutButton"
            >
              <span>Logout</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- Logout Modal End -->

    <!-- Js Dependencies -->
    <script src="assets/js/plugins/bootstrap.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/service-worker-settings.js"></script>
  </body>

<!-- Mirrored from appoinx-app-html.vercel.app/profile-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 15 Jul 2025 08:33:21 GMT -->
</html>
