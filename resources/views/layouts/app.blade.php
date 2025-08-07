<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ config('app.name', 'GESTVILLAGE') }}</title>
        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/img/logo1.png') }}') }}" type="image/x-icon" />

        <link rel="shortcut icon" href="{{ asset('assets/img/logo1.png') }}') }}" type="image/x-icon" />

        <!-- Polices & Icônes (CDN) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
            integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://unpkg.com/@phosphor-icons/web@2.0.3"></script>

        <!-- Stylesheets CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
        <!-- Importation de Font Awesome pour les icônes -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Manifest PWA -->
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
            <meta name="theme-color" content="#4a2f26">
        <link rel="manifest" href="manifest.json" />
        
        <style>
            .footer-menu a {
                text-decoration: none;
                color: inherit;
            }

            .footer-menu .link-item {
                font-size: 26px;
                color: #868e96;
                transition: color 0.2s ease-in-out;
            }

            .footer-menu .link-item.active {
                color: #343a40;
            }


            /* Cible directement les liens <a> dans le menu */
            .footer-menu a {
                text-decoration: none;
                /* Supprime le soulignement du lien */
                color: inherit;
                /* Fait en sorte que le lien n'ait pas sa propre couleur (bleue) */
                /* mais hérite de son parent, ce qui nous donne le contrôle */
            }

            /* Style de base pour toutes les icônes dans les liens du menu */
            .footer-menu .link-item {
                font-size: 26px;
                /* Ajustez la taille si nécessaire */
                color: #868e96;
                /* Une couleur neutre pour les icônes inactives */
                transition: color 0.2s ease-in-out;
                /* Effet de transition doux */
            }

            /* Style spécifique pour l'icône quand son lien est actif */
            .footer-menu .link-item.active {
                color: #343a40;
                /* Une couleur plus foncée ou la couleur principale de votre thème */
            }

            /* Style pour l'aperçu du message */
            .message-preview {
                color: #888;
                font-size: 0.9em;
            }

            /* --- STYLE AJOUTÉ POUR LA BORDURE VERTE --- */
            .message-item.top-doctor-item {
                /* Crée la bordure verte fine */
                border: 1.2px solid #1b3133;
                /* Une couleur turquoise qui correspond à l'interface */

                /* Arrondit les coins de la bordure */
                border-radius: 20px;

                /* Optionnel : supprime l'ombre par défaut s'il y en a une pour un look plus épuré */
                /* box-shadow: none; */
            }

            /* --- FIN DU STYLE AJOUTÉ --- */

            .message-item .doctor-img {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                overflow: hidden;
            }

            /* --- STYLES AJOUTÉS POUR LE BOUTON --- */
            .message-button {
                /* 1. Définir la couleur de fond */
                background-color: rgb(59, 100, 62);

                /* 2. Créer un carré */
                width: 44px;
                height: 44px;

                /* 3. Arrondir les bords */
                border-radius: 12px;

                /* 4. Centrer l'icône à l'intérieur */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .message-button i {
                /* 5. Mettre l'icône en blanc */
                color: white;
            }

            /* --- FIN DES STYLES AJOUTÉS --- */
            p.name {
                /* permet d'éviter la couleur bleue par défaut à un élément contenu dans un lien */
                color: black;
            }

            /* S'assure qu'il reste noir même lorsque le lien parent est survolé */
            a:hover p.name {
                color: black;
            }


            /* --- VARIABLES DE COULEUR --- */
            :root {
                --primary-color: #5e2e1e;
                /* Marron du toit */
                --primary-hover: #4b2317;
                /* Variante plus sombre */
                --accent-color: #1b3133;
                /* Vert olive (murs) */
                --danger-color: #a04127;
                /* Rouge terre cuite */
                --background-color: #f6f2e6;
                /* Beige des murs */
                --card-bg-color: #ffffff;
                /* Cartes, fond clair */
                --text-dark: #4a2f26;
                --text-primary: #4b2317;
                /* Texte "LE VILLAGE" */
                --text-light: #a39387;
                /* Variante douce du brun */
                --border-color: #e2dccc;
                /* Bordures légères */
                --shadow-color: rgba(0, 0, 0, 0.05);
            }


            /* --- STYLES GÉNÉRAUX --- */
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                background-color: #e2dccc;
                margin: 0;
                padding: 2rem;
                /* Cet espacement peut être ajusté ou déplacé sur le container principal */
                color: var(--text-dark);
            }

            * {
                box-sizing: border-box;
            }

            /* --- EN-TÊTE PRINCIPAL --- */
            .main-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem 2rem;
                background-color: var(--card-bg-color);
                border: 1px solid var(--border-color);
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px -1px var(--shadow-color);
                margin-bottom: 2rem;
            }

            .main-header h1 {
                font-size: 1.5rem;
                margin: 0;
            }

            .header-actions button {
                background-color: var(--primary-color);
                color: white;
                border: none;
                padding: 0.75rem 1.25rem;
                border-radius: 0.375rem;
                font-size: 0.9rem;
                font-weight: 600;
                cursor: pointer;
                transition: background-color 0.2s ease-in-out;
                margin-left: 1rem;
            }

            .header-actions button:hover {
                background-color: var(--primary-hover);
            }

            .header-actions button i {
                margin-right: 0.5rem;
            }

            /* --- SYSTÈME D'ONGLETS COULISSANTS --- */
            .tab-container {
                width: 100%;
                background-color: var(--card-bg-color);
                padding: 0.5rem;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px -1px var(--shadow-color);
                margin-bottom: 2rem;
            }

            .tab-buttons {
                position: relative;
                display: flex;
                list-style: none;
                padding: 0;
                margin: 0;
                width: 100%;
            }

            .tab-button {
                flex: 1;
                padding: 1rem 0;
                text-align: center;
                font-weight: 600;
                cursor: pointer;
                color: var(--text-light);
                transition: color 0.3s ease;
                z-index: 2;
            }

            .tab-button.active {
                color: white;
            }

            .slider {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                background-color: var(--primary-color);
                border-radius: 0.5rem;
                z-index: 1;
                transition: left 0.3s ease-in-out, width 0.3s ease-in-out;
            }

            .tab-content-container {
                padding-top: 1rem;
            }

            .tab-content {
                display: none;
                animation: fadeIn 0.5s;
            }

            .tab-content.active {
                display: block;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            /* --- CONTENU DES CARTES --- */
            .cards-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 2rem;
            }

            .cash-register-card {
                background-color: var(--card-bg-color);
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px -1px var(--shadow-color);
                border: 1px solid var(--border-color);
                display: flex;
                flex-direction: column;
                padding: 1.5rem;
            }

            .card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            .card-header .settings-icon {
                font-size: 1.5rem;
                color: var(--text-light);
                cursor: pointer;
            }

            .card-header .actions-btn {
                background-color: #f7fafc;
                border: 1px solid var(--border-color);
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                cursor: pointer;
            }

            .card-body h2 {
                margin: 0 0 0.5rem 0;
                font-size: 1rem;
                color: var(--text-light);
                text-transform: uppercase;
            }

            .card-body .amount {
                font-size: 2.5rem;
                font-weight: 700;
                margin: 0 0 0.5rem 0;
            }

            .card-body .manager {
                color: var(--text-light);
                font-size: 0.9rem;
                margin: 0;
            }

            .card-footer {
                margin-top: auto;
                padding-top: 1.5rem;
            }

            .card-footer .view-movements-btn {
                width: 100%;
                background-color: var(--primary-color);
                color: white;
                border: none;
                padding: 0.75rem;
                border-radius: 0.375rem;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: background-color 0.2s ease-in-out;
            }

            .card-footer .view-movements-btn:hover {
                background-color: var(--primary-hover);
            }

            .card-footer .view-movements-btn i {
                margin-right: 0.5rem;
            }

            /* --- Contenu Rapport Financier (placeholder) --- */
            .report-placeholder {
                padding: 2rem;
                text-align: center;
                background-color: var(--card-bg-color);
                border-radius: 0.5rem;
            }

            .report-placeholder i {
                font-size: 4rem;
                color: var(--primary-color);
                margin-bottom: 1rem;
            }

            /* --- STYLES DE LA FENÊTRE MODALE --- */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .modal-content {
                background-color: #fefefe;
                margin: 10% auto;
                border-radius: 0.5rem;
                width: 90%;
                max-width: 500px;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                animation: fadeIn 0.3s;
            }

            .modal-header {
                padding: 1rem 1.5rem;
                background-color: var(--primary-color);
                color: white;
                border-top-left-radius: 0.5rem;
                border-top-right-radius: 0.5rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-header h2 {
                margin: 0;
                font-size: 1.25rem;
            }

            .close-btn {
                color: white;
                font-size: 1.75rem;
                font-weight: bold;
                cursor: pointer;
                opacity: 0.8;
                transition: opacity 0.2s;
            }

            .close-btn:hover {
                opacity: 1;
            }

            .modal-body {
                padding: 2rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: var(--text-dark);
            }

            .form-group input,
            .form-group select {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid var(--border-color);
                border-radius: 0.375rem;
                font-size: 1rem;
            }

            .form-group select {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
            }


            /* Dans la balise <style> de votre layout app.blade.php */

            /* =================================================================== */
            /* AJUSTEMENTS RESPONSIVES POUR UTILISER L'ESPACE SUR MOBILE           */
            /* =================================================================== */

            /* On applique ces styles uniquement sur les écrans de petite taille (ex: téléphones) */
            @media (max-width: 576px) {

                /*
                * ÉTAPE 1 : On force les conteneurs principaux à réduire leurs marges latérales.
                * Les classes 'px-6' créent un grand espace vide sur les côtés.
                * On le remplace par un espacement plus petit (1rem) pour mobile.
                */
                .notification-top-area.px-6,
                .px-6.py-8.pb-45 {
                    padding-left: 0rem !important;
                    /* On surcharge la valeur de 'px-6' */
                    padding-right: 0rem !important;
                    /* On surcharge la valeur de 'px-6' */
                }

                /*
                * ÉTAPE 2 : On réduit le grand padding à l'intérieur de chaque carte utilisateur.
                * La classe 'p-5' est trop grande pour un mobile et "mange" l'espace intérieur.
                * On le remplace par un padding plus adapté.
                */
                .w-100.top-doctor-item.p-5 {
                    padding: 1.25rem 1rem !important;
                    /* On surcharge 'p-5' par un padding vertical et horizontal plus petit */
                }

                /*
                * ÉTAPE 3 (Optionnel) : On peut aussi réduire l'écart entre les éléments
                * pour un rendu plus compact sur mobile.
                */
                /* .d-flex.gap-4 {
                    gap: 1rem !important; /* On réduit l'espace de la classe 'gap-4' */
                /* } */
            }

            .colo-primary {
                color: #4b2317;
            }
        </style>

    </head>

<body style="background-color: #e2dccccd">
    <!-- Preloader Start -->
    {{-- <div class="preloader active">
        <div class="flex-center h-100 bgMainColor">
            <div class="main-container flex-center h-100 flex-column">
                <div class="wave-animation">
                    <img src="{{ asset('assets/img/fav.png') }}" alt="" />
                    <div class="waves wave-1"></div>
                    <div class="waves wave-2"></div>
                    <div class="waves wave-3"></div>
                </div>

                <div class="pt-8">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="" />
                </div>
            </div>
        </div>
    </div> --}}


    <main class="home-screen">
        <!-- Preloader End -->
        <!-- =================================== -->
        <!--   DEBUT DE LA SECTION A FIGER       -->
        <!-- =================================== -->
        <div class="sticky-top bg-white shadow-sm ">
            <!-- Header Section Start -->
            <section class="d-flex justify-content-between align-items-center home-header-section w-100 px-3 pt-3">
                <div class="d-flex justify-content-start align-items-center gap-3 mt-3 mb-3">
                    <div class="profile-img">
                        <img src="{{ asset('assets/img/logo1.png') }}" alt="Photo de profil" />
                    </div>
                    <div>
                        <h3 class="heading-3 pb-2">
                            {{ Str::limit(Auth::user()->nom . ' ' . Auth::user()->prenom, 20, '...') }}
                        </h3>
                        <p class="d-inline-flex gap-2 location justify-content-start align-items-center">
                            {{ Auth::user()->role_user }}
                        </p>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                    {{-- Bouton Notification --}}
                    <button
                        class="btn btn-outline-light position-relative d-flex align-items-center justify-content-center p-2"
                        style="border: 1px solid #8B5E3C; border-radius: 10px;">
                        <i class="ph ph-bell fs-5 text-dark"></i>
                        <span
                            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        </span>
                    </button>

                    {{-- Bouton Déconnexion --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="btn btn-outline-light d-flex align-items-center justify-content-center p-2"
                            style="border: 1px solid #8B5E3C; border-radius: 10px;">
                            <i class="fas fa-sign-out-alt colo-primary fs-5"></i>
                        </button>
                    </form>
                </div>
            </section>
            <!-- Header Section End -->
        </div>
        <!-- =================================== -->
        <!--     FIN DE LA SECTION A FIGER       -->
        <!-- =================================== -->

        @yield(section: 'content')
        <!-- Footer Menu Start -->
        <div class="footer-menu-area">
            <div class="footer-menu flex justify-content-center align-items-center">
                <div class="footer-menu flex justify-content-center align-items-center">
                    <div class="d-flex align-items-center h-100 w-100">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="flex-fill text-center">
                            <i
                                class="ph ph-house-line link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"></i>
                        </a>

                        <!-- Calendrier -->
                        <a href="{{ route('calendar') }}" class="flex-fill text-center">
                            <i
                                class="ph-fill ph-calendar link-item {{ request()->routeIs('calendar') ? 'active' : '' }}"></i>
                        </a>

                        <!-- Finances -->
                        <a href="{{ route('finances') }}" class="flex-fill text-center">
                            <i class="ph ph-wallet link-item {{ request()->routeIs('finances') ? 'active' : '' }}"></i>
                        </a>


                        <a href="{{ route('utilisateurs') }}" class="flex-fill text-center">
                            {{-- Pas besoin de vérifier si la route est active ici, car ce bloc n'est jamais affiché si elle l'est --}}
                            <i
                                class="ph ph-users link-item {{ request()->routeIs('utilisateurs') ? 'active' : '' }}"></i>
                        </a>
                    </div>
                </div>

                <!-- bouton  ou icone -->
                <div class="plus-icon position-absolute">
                    <div class="position-relative">
                        <button id="specialityModalOpenButton">
                            <img src="assets/img/plus-icon-bg.png" class="" alt="" />
                            <i class="ph ph-plus-circle"></i>
                        </button>
                        <!-- <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('profile.settings') }}" id="specialityModalOpenButton">
                            <button class="btn p-0 border-0 bg-transparent">
                                <img src="assets/img/plus-icon-bg.png" alt="" />
                                <i class="ph ph-plus"></i>
                            </button>
                            </a>
                        </div> -->

                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Menu End -->
    </main>
    <!-- Upcoming Schedule End -->

    <!-- Doctor Specialist Start -->

    <!-- ============================= Modal start ========================== -->
    <!-- Notification Modal Start -->
    <!-- ======================= CODE HTML CORRIGÉ ======================= -->
    <!-- Notification Modal Start -->
    {{-- Si la route actuelle N'EST PAS 'calendrier', alors on affiche le contenu --}}
    <!-- @if (!Route::is('calendar'))
-->
    <div class="position-fixed top-0 start-0 bottom-0 end-0 notificationModal overflow-auto fullPageModalClose">
        <div class="px-6 pt-8 notification-top-area">
            <div class="d-flex justify-content-start align-items-center gap-4 py-3">
                <button class="back-button flex-center" id="notificationModalCloseButton">
                    <i class="ph ph-caret-left"></i>
                </button>
                <h2>Notifications</h2>
            </div>

            <div class="latest-update d-flex justify-content-between align-items-center pt-8 gap2">
                <p class="title">Dernière mise à jour</p>
                <div class="d-flex justify-content-start align-items-center gap-2 flex-wrap">
                    <p>Filtrer par:</p>
                    <div class="position-relative" id="notificationSortBy">
                        <p class="select-item">
                            <span class="sortByText">Tout</span>
                            <i class="ph ph-caret-down"></i>
                        </p>
                        <div class="notification-sortby-modal modalClose" id="notificationSortByModal">
                            <ul class="d-flex justify-content-start align-items-start gap-2 flex-column">
                                <li class="sortbyItem">Semaine</li>
                                <li class="sortbyItem">Mois</li>
                                <li class="sortbyItem">Année</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="custom-border-area position-relative mt-5 w-100">
                <div class="line-horizontal"></div>
            </div>
        </div>

        <!-- Settings area start -->
        <div class="notification-area pt-5 px-6 d-flex flex-column gap-6 pb-8">
            <div class="">
                <p class="fw-bold n2-color date pb-5">Lundi, 21 Juillet 2025</p>

                <div class="d-flex flex-column gap-4">
                    <div class="notification-item d-flex justify-content-start align-items-start gap-5">
                        <div class="flex-center p-4 icon">
                            <i class="ph-fill ph-x"></i>
                        </div>
                        <div class="">
                            <p class="notification-title pb-2">Approbation de réservaion!</p>
                            <p class="desc n2-color">
                                Cher admin, vous avez une nouvelle demande de réservation en attente. Veuillez
                                l'examiner et l'approuver dès que possible.
                            </p>
                        </div>
                    </div>
                    <div class="notification-item d-flex justify-content-start align-items-start gap-5">
                        <div class="flex-center p-4 icon">
                            <i class="ph-fill ph-x"></i>
                        </div>
                        <div class="">
                            <p class="notification-title pb-2">Approbation de sortie financière!</p>
                            <p class="desc n2-color">
                                Cher admin, vous avez une nouvelle demande de d'approbation financière en attente.
                                Veuillez l'examiner et l'approuver dès que possible.
                            </p>
                        </div>
                    </div>
                    <div class="notification-item active d-flex justify-content-start align-items-start gap-5">
                        <div class="flex-center p-4 icon">
                            <i class="ph-fill ph-calendar-check"></i>
                        </div>
                        <div class="d-flex justify-content-start align-items-start flex-column">
                            <p class="notification-title pb-2">Réservation approuvée!</p>
                            <p class="desc n2-color pb-4">
                                Réservation approuvée! Veuillez vérifier les détails de celle-ci dans votre tableau de
                                bord.
                            </p>
                            <a href="#" class="primary-button flex-center gap-2">Annuler <i
                                    class="ph ph-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="notification-item active d-flex justify-content-start align-items-start gap-5">
                        <div class="flex-center p-4 icon">
                            <i class="ph-fill ph-calendar-check"></i>
                        </div>
                        <div class="d-flex justify-content-start align-items-start flex-column">
                            <p class="notification-title pb-2">Sortie financière approuvée!</p>
                            <p class="desc n2-color pb-4">
                                Sortie de finances approuvée! Veuillez vérifier les détails de la transaction dans votre
                                tableau de bord.
                            </p>
                            <a href="#" class="primary-button flex-center gap-2">Annuler <i
                                    class="ph ph-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Settings area end -->
    </div>
    <!--
@endif -->
    <!-- Notification Modal End -->

    <!-- Doctor Speciality Modal Start (Finance) -->
    <div
        class="px-6 pb-8 position-fixed top-0 start-0 bottom-0 end-0 specialityModal fullPageModalClose overflow-auto">
        <div class="px-6 pt-8 notification-top-area">
            <div class="d-flex justify-content-start align-items-center gap-4 py-3">
                <button class="back-button flex-center" id="specialityModalCloseButton">
                    <i class="ph ph-caret-left"></i>
                </button>
                <h2>Approbations en attente</h2>
            </div>
        </div>

        <!-- speciality section start -->
        <div class="pt-8">
            <div class="row doctor-speciality g-4">
                <!-- Item 1: Réservations en attente -->
                <div class="col-12">
                    <!-- Le conteneur a "position-relative" pour l'icône et "text-center" pour le titre -->
                    <div class="item text-center px-3 py-4 position-relative">
                        <!-- Icône positionnée à gauche et centrée verticalement -->
                        <i class="ph ph-list fs-2 position-absolute top-50 start-0 translate-middle-y ps-3"></i>
                        <h3 class="title small mb-0">
                            <!-- Le lien couvre toute la carte grâce à "stretched-link" -->
                            <a href="#" class="stretched-link text-decoration-none text-dark">Réservations en
                                attente</a>
                        </h3>
                    </div>
                </div>

                <!-- Item 2: Finances -->
                <div class="col-12">
                    <div class="item text-center px-3 py-4 position-relative">
                        <i class="ph ph-wallet fs-2 position-absolute top-50 start-0 translate-middle-y ps-3"></i>
                        <h3 class="title mb-0">
                            <a href="#" class="stretched-link text-decoration-none text-dark">Sortie de finances
                                en attente</a>
                        </h3>
                    </div>
                </div>
            </div>
            <!-- speciality section end -->
        </div>
        <!-- Doctor Speciality Modal End -->

        <!-- Logout Modal Start -->
        <div id="logoutModal"
            class="logoutModalClose position-fixed bottom-0 start-0 w-100 bg-white p-4 shadow-lg rounded-top z-50"
            style="min-height: 180px;">
            <p class="text-center fw-semibold fs-5 mb-3">Voulez-vous vous déconnecter ?</p>

            <div class="d-flex justify-content-around">
                <button class="btn btn-outline-secondary px-4" onclick="closeLogoutModal()">Annuler</button>

                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4">Se déconnecter</button>
                </form>
            </div>
        </div>
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
        <!-- Logout Modal End -->
        <!-- Logout Modal End -->
        <!-- ============================= Modal end ========================== -->
        <!-- Js Dependencies -->
        <script src="{{ asset('assets/js/plugins/swiper-bundle.min.js') }}"></script>
        <script>
            // Gestion des modales utilisateurs
            document.addEventListener('DOMContentLoaded', function() {
                // Gestion du bouton plus
                const plusButton = document.getElementById('specialityModalOpenButton');
                if (plusButton) {
                    plusButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        // Ouvrir la modale d'approbations
                        const specialityModal = document.querySelector('.specialityModal');
                        if (specialityModal) {
                            specialityModal.style.display = 'block';
                        }
                    });
                }
                // Modale de création
                const openUserModalBtn = document.getElementById('openUserModalBtn');
                const userModal = document.getElementById('userCreationModal');
                const userCloseBtn = userModal.querySelector('.close-btn');

                // Modale de visualisation
                const viewModal = document.getElementById('userViewModal');
                const viewCloseBtn = viewModal.querySelector('.close-btn');

                // Modale d'édition
                const editModal = document.getElementById('userEditModal');
                const editCloseBtn = editModal.querySelector('.close-btn');

                // Modale de suppression
                const deleteModal = document.getElementById('userDeleteModal');
                const deleteCloseBtn = deleteModal.querySelector('.close-btn');
                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

                // Fonction pour ouvrir une modale
                function openModal(modal) {
                    if (modal) modal.style.display = 'block';
                }

                // Fonction pour fermer une modale
                function closeModal(modal) {
                    if (modal) modal.style.display = 'none';
                }

                // Gestion de la création
                if (openUserModalBtn && userModal && userCloseBtn) {
                    openUserModalBtn.addEventListener('click', function() {
                        openModal(userModal);
                    });

                    userCloseBtn.addEventListener('click', function() {
                        closeModal(userModal);
                    });
                }

                // Gestion de la visualisation
                document.querySelectorAll('.user-item').forEach(item => {
                    item.addEventListener('click', function(event) {
                        // Ignorer les clics sur les boutons d'édition et de suppression
                        if (event.target.closest('.edit-user-btn') || event.target.closest(
                                '.delete-user-btn')) {
                            return;
                        }

                        const userData = {
                            name: this.getAttribute('data-name'),
                            email: this.getAttribute('data-email'),
                            role: this.getAttribute('data-role')
                        };

                        // Remplir les informations
                        document.getElementById('view-user-name').textContent = userData.name;
                        document.getElementById('view-user-email').textContent = userData.email;
                        document.getElementById('view-user-role').textContent = userData.role;

                        openModal(viewModal);
                    });
                });

                // Gestion de l'édition
                document.querySelectorAll('.edit-user-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const userData = {
                            name: this.getAttribute('data-name'),
                            email: this.getAttribute('data-email'),
                            role: this.getAttribute('data-role')
                        };

                        // Remplir le formulaire d'édition
                        document.getElementById('edit-user-name').value = userData.name;
                        document.getElementById('edit-user-email').value = userData.email;
                        document.getElementById('edit-user-role').value = userData.role;

                        openModal(editModal);
                    });
                });

                // Gestion de la suppression
                document.querySelectorAll('.delete-user-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const userName = this.getAttribute('data-name');
                        // Afficher un message de confirmation avec le nom de l'utilisateur
                        console.log(`Suppression de ${userName}`);
                        openModal(deleteModal);
                    });
                });

                // Gestion des fermetures
                [viewCloseBtn, editCloseBtn, deleteCloseBtn].forEach(btn => {
                    if (btn) {
                        btn.addEventListener('click', function() {
                            closeModal(this.closest('.modal'));
                        });
                    }
                });

                // Gestion de la confirmation de suppression
                if (confirmDeleteBtn && cancelDeleteBtn) {
                    confirmDeleteBtn.addEventListener('click', function() {
                        // Ici, vous pouvez ajouter la logique de suppression
                        console.log('Suppression confirmée');
                        closeModal(deleteModal);
                    });

                    cancelDeleteBtn.addEventListener('click', function() {
                        closeModal(deleteModal);
                    });
                }

                // Fermeture en cliquant en dehors
                [userModal, viewModal, editModal, deleteModal].forEach(modal => {
                    if (modal) {
                        modal.addEventListener('click', function(event) {
                            if (event.target === this) {
                                closeModal(this);
                            }
                        });
                    }
                });
            });
        </script>
        <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery.countdown.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/chart.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/waypoints.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery.meanmenu.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/parallax.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/wow.min.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery.nice-select.min.js') }}"></script>

        <script>
            // Gestion de la modale d'approbations
            document.addEventListener('DOMContentLoaded', function() {
                const openButton = document.getElementById('specialityModalOpenButton');
                const closeButton = document.getElementById('specialityModalCloseButton');
                const modal = document.querySelector('.specialityModal');

                if (openButton && closeButton && modal) {
                    // Ouvrir la modale
                    openButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        modal.style.display = 'block';
                    });

                    // Fermer la modale
                    closeButton.addEventListener('click', function() {
                        modal.style.display = 'none';
                    });

                    // Fermer la modale en cliquant en dehors
                    window.addEventListener('click', function(event) {
                        if (event.target === modal) {
                            modal.style.display = 'none';
                        }
                    });
                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // --- GESTION DES ONGLETS COULISSANTS ---
                const tabs = document.querySelectorAll('.tab-button');
                const slider = document.querySelector('.slider');
                const contents = document.querySelectorAll('.tab-content');

                function updateSlider(activeTab) {
                    if (!activeTab || !slider) return;
                    slider.style.width = `${activeTab.offsetWidth}px`;
                    slider.style.left = `${activeTab.offsetLeft}px`;
                }

                const initialActiveTab = document.querySelector('.tab-button.active');
                if (initialActiveTab) {
                    // Utiliser un petit délai pour s'assurer que le rendu est terminé
                    setTimeout(() => updateSlider(initialActiveTab), 50);
                }

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        tabs.forEach(t => t.classList.remove('active'));
                        contents.forEach(c => c.classList.remove('active'));

                        tab.classList.add('active');
                        const contentId = tab.getAttribute('data-tab');
                        const activeContent = document.getElementById(contentId);
                        if (activeContent) {
                            activeContent.classList.add('active');
                        }

                        updateSlider(tab);
                    });
                });

                window.addEventListener('resize', () => {
                    const activeTab = document.querySelector('.tab-button.active');
                    updateSlider(activeTab);
                });

                // --- GESTION DE LA MODALE ---
                const modal = document.getElementById('creationModal');
                const openBtn = document.getElementById('openModalBtn');
                const closeBtn = document.querySelector('.close-btn');

                if (modal && openBtn && closeBtn) {
                    openBtn.onclick = function() {
                        modal.style.display = 'block';
                    }
                    closeBtn.onclick = function() {
                        modal.style.display = 'none';
                    }
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    }
                }
            });
        </script>

<script>
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
</script>

</body>

</html>
