@extends('layouts.app')
@section('content')
    <!-- ======================= CODE CORRIGÉ ======================= -->

    <!-- On ouvre la balise principale -->
    <!-- ======================= CSS POUR LA NOUVELLE FONCTIONNALITÉ ======================= -->
    <style>
        .schedule-day .scheduleButton {
            flex-shrink: 0;
            /* Empêche les boutons de se réduire */
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
            border-radius: 8px;
            width: 48px;
            height: 64px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        .schedule-day .scheduleButton.active,
        .schedule-day .scheduleButton:hover {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .schedule-day .scheduleButton.has-events::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            background-color: #dc3545;
            border-radius: 50%;
        }

        .schedule-day .scheduleButton.active.has-events::after {
            background-color: white;
        }

        .month-navigation-button {
            background: none;
            border: 1px solid #ccc;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .event-item {
            border: 1px solid #eee;
            padding: 1rem;
            border-radius: 8px;
            background-color: #fff;
        }

        #eventDetailModal {
            display: none;
            /* Cachée par défaut */
            position: fixed;
            z-index: 1050;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }

        /* Style pour chaque élément de la liste d'événements */
        .event-list-item {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
        }

        .event-list-item .icon-container {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            background-color: #eef7ff;
            /* Bleu clair */
            color: #007bff;
            border-radius: 8px;
            font-size: 1.5rem;
            /* Taille de l'icône */
        }

        .event-list-item .event-name {
            font-weight: 600;
            color: #333;
        }

        .event-list-item .event-category {
            color: #555;
        }

        .event-list-item .event-details-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .event-list-item .event-price {
            color: #333;
            font-weight: 700;
        }

        .custom-border-area {
            flex-grow: 1;
            height: 1px;
            background-color: #e0e0e0;
        }

        /* Styles pour la nouvelle modale de détails */
        #reservationDetailModal {
            display: none;
            /* Cachée par défaut */
            position: fixed;
            z-index: 1050;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s;
        }

        .modal-content {
            background-color: #f9f9f9;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 550px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background-color: #4a5c4f;
            /* Vert foncé de l'image */
            color: white;
            padding: 1rem 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header button {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-body .info-box {
            background-color: white;
            border: 1px solid #e0e0e0;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .modal-body h5 {
            margin-top: 1rem;
            margin-bottom: 1rem;
            color: #b5651d;
            /* Couleur marron de l'image */
        }

        .modal-body .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .schedule-day .scheduleButton {
            flex-shrink: 0;
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
            border-radius: 8px;
            width: 48px;
            height: 64px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        .schedule-day .scheduleButton.active,
        .schedule-day .scheduleButton:hover {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .schedule-day .scheduleButton.has-events::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 50%;
            transform: translateX(-50%);
            width: 5px;
            height: 5px;
            background-color: #dc3545;
            border-radius: 50%;
        }

        .schedule-day .scheduleButton.active.has-events::after {
            background-color: white;
        }

        .month-navigation-button {
            background: none;
            border: 1px solid #ccc;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-form-container {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .search-form-container summary {
            font-weight: 600;
            cursor: pointer;
            list-style: none;
            /* Cache la flèche par défaut */
        }

        .search-form-container summary::-webkit-details-marker {
            display: none;
        }

        /* Pour Chrome/Safari */
        .search-form-container summary::before {
            content: '► ';
            font-size: 0.8em;
        }

        .search-form-container[open]>summary::before {
            content: '▼ ';
        }

        .search-form-container form {
            margin-top: 1rem;
        }

        #year-selector {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 0.25rem 0.5rem;
            font-weight: bold;
        }

        .event-list-item {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
        }
    </style>
    <section class="px-6 pt-8 notification-top-area mx-3" style="margin-top: -30px">
        <div class="d-flex justify-content-between align-items-center gap-4 py-3">
            <a href="{{ route('dashboard') }}" class="p1-color back-button flex-center">
                <i class="ph ph-caret-left"></i>
            </a>
            <h2 class="color-primary">Mon calendrier</h2>
        </div>
    </section>

    <!-- La balise ouvrante est ici... -->
    <div class="schedule-section w-100 px-6 pt-8 overflow-hidden mx-1" style="margin-top: -30px">
        <div class="d-flex justify-content-between align-items-center pb-5">
            <h6>Mes réservations</h6>
            <div class="flex-center gap-3">
                <button id="prev-month-btn" class="month-navigation-button flex-center">
                    <i class="ph ph-caret-left" style="font-size: 13px"></i>
                </button>
                <div>
                    <p id="currentMonthDisplay" class="fw-bold text-center mb-0">Juillet 2025</p>
                    <!-- BOUTON AJOUTÉ -->
                    <button id="show-all-month-btn" class="btn btn-link btn-sm p-0 d-none">Voir tout le mois</button>
                </div>
                <button id="next-month-btn" class="month-navigation-button flex-center">
                    <i class="ph ph-caret-right" style="font-size: 13px"></i>
                </button>
            </div>
        </div>
        <div class="schedule-area">
            <div id="schedule-day-container"
                class="schedule-day d-flex justify-content-start align-items-center gap-3 overflow-auto pb-3"></div>
        </div>
    </div>

    <!-- Formulaire de recherche par période -->
    </div>
    <style>
        .loader-container {
            width: 100%;
            /* Prend toute la largeur disponible */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            /* Ajoute un peu d'espace */
            min-height: 150px;
            /* Donne une hauteur minimale pour éviter que la page ne saute */
        }
    </style>
    <!-- Conteneur pour la barre des jours -->
    <div id="schedule-day-scroller" class="schedule-area mx-2">
        <div class="schedule-day d-flex justify-content-start align-items-center gap-3 overflow-auto pb-3">

        </div>
    </div>

    <!-- Conteneur pour la liste des événements -->
    <div id="event-list-container" class="d-flex flex-column gap-4 px-6 pt-4">

        <!-- ======================= LOADER AJOUTÉ ICI ======================= -->
        <div class="loader-container">
            <div class="spinner-border color-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
        <!-- =================================================================== -->

    </div>

    <!-- Le conteneur pour la liste des événements sera rempli ici -->
    <div id="event-list-container" class="d-flex flex-column gap-4 px-6 pt-4">
        <p class="text-center text-muted">Veuillez sélectionner un jour pour voir les réservations.</p>
    </div>



    <!-- =============== Calendar Modal Start ==================== -->
    <!-- J'ai ajouté un style display:none pour la cacher par défaut -->
    <div class="position-fixed top-0 start-0 bottom-0 end-0 calendarModal overflow-auto flex-center"
        style="display: none; background-color: rgba(0,0,0,0.5);">
        <div class="container-calendar" style="background-color: white; padding: 20px; border-radius: 8px;">
            <div class="button-container-calendar d-flex justify-content-between align-items-center">
                <button class="flex-center" id="previous" onclick="previous()">
                    <i class="ph ph-caret-left"></i>
                </button>
                <h3 class="heading-2" id="monthAndYear"></h3>
                <button id="next" class="flex-center" onclick="next()">
                    <i class="ph ph-caret-right"></i>
                </button>
            </div>

            <div class="footer-container-calendar">
                <label for="month">Aller à : </label>
                <select id="month" onchange="jump()">
                    <option value="0">Jan</option>
                    <option value="1">Fév</option>
                    <option value="2">Mar</option>
                    <option value="3">Avr</option>
                    <option value="4">Mai</option>
                    <option value="5">Juin</option>
                    <option value="6">Juil</option>
                    <option value="7">Août</option>
                    <option value="8">Sep</option>
                    <option value="9">Oct</option>
                    <option value="10">Nov</option>
                    <option value="11">Déc</option>
                </select>
                <select id="year" onchange="jump()"></select>
            </div>

            <!-- J'ai mis la langue sur "fr" pour correspondre à votre code JS -->
            <table class="table-calendar" id="calendar" data-lang="fr">
                <thead id="thead-month"></thead>
                <tbody id="calendar-body"></tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center bottom-button gap-3 w-100 pt-6">
                <button class="cancel" id="calendarModalCloseButton">Annuler</button>
                <button class="set-date">Valider</button>
            </div>
        </div>
    </div>
    <!-- =============== Calendar Modal End ==================== -->


    <!-- ======================= SCRIPT MIS À JOUR ======================= -->
    <!-- SCRIPT CORRIGÉ -->
    <script>
        const eventsByDate = @json($informations_reserves ?? []);

        document.addEventListener('DOMContentLoaded', function() {

            // --- GESTION DU DOM ---
            const eventListContainer = document.getElementById('event-list-container');
            const monthDisplay = document.getElementById('currentMonthDisplay');
            const dayContainer = document.getElementById('schedule-day-container');
            const prevMonthBtn = document.getElementById('prev-month-btn');
            const nextMonthBtn = document.getElementById('next-month-btn');
            const showAllMonthBtn = document.getElementById('show-all-month-btn'); // Le nouveau bouton
            let currentDate = new Date();
            currentDate.setDate(1);

            const monthFormatter = new Intl.DateTimeFormat('fr-FR', {
                month: 'long',
                year: 'numeric'
            });
            const weekdayFormatter = new Intl.DateTimeFormat('fr-FR', {
                weekday: 'short'
            });

            function changeMonth(direction) {
                currentDate.setMonth(currentDate.getMonth() + direction);
                updateCalendarDisplay();
            }

            prevMonthBtn.onclick = () => changeMonth(-1);
            nextMonthBtn.onclick = () => changeMonth(1);

            // Action pour le bouton "Voir tout le mois"
            showAllMonthBtn.onclick = () => {
                dayContainer.querySelector('.active')?.classList.remove('active');
                showAllMonthBtn.classList.add('d-none');
                displayEventsForMonth(currentDate);
            };

            function updateCalendarDisplay() {
                monthDisplay.textContent = monthFormatter.format(currentDate).replace(/^\w/, c => c.toUpperCase());
                renderDayScroller();
                // CORRECTION: Affiche les événements du mois au lieu de cliquer sur un jour
                displayEventsForMonth(currentDate);
            }

            function renderDayScroller() {
                dayContainer.innerHTML = '';
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                for (let i = 1; i <= daysInMonth; i++) {
                    const dayDate = new Date(year, month, i);
                    const dateString = formatDate(dayDate);

                    const button = document.createElement('button');
                    button.className = 'flex-center flex-column scheduleButton';
                    button.dataset.date = dateString;

                    if (eventsByDate[dateString]) {
                        button.classList.add('has-events');
                    }

                    button.innerHTML =
                        `<span class="fw-semibold">${i}</span><span class="date">${weekdayFormatter.format(dayDate).replace('.', '')}</span>`;

                    // CORRECTION: Le clic affiche maintenant les événements du jour
                    button.onclick = () => {
                        dayContainer.querySelector('.active')?.classList.remove('active');
                        button.classList.add('active');
                        showAllMonthBtn.classList.remove('d-none'); // Affiche le bouton "Voir tout le mois"
                        displayEventsForDate(dateString);
                    };
                    dayContainer.appendChild(button);
                }
            }

            // --- NOUVELLE FONCTION: Affiche les événements pour le mois entier ---
            function displayEventsForMonth(date) {
                eventListContainer.innerHTML = '';
                let eventsOfMonth = [];
                const year = date.getFullYear();
                const month = date.getMonth();

                // On collecte tous les événements du mois
                for (const dateKey in eventsByDate) {
                    const eventDate = new Date(dateKey);
                    if (eventDate.getFullYear() === year && eventDate.getMonth() === month) {
                        eventsOfMonth = eventsOfMonth.concat(eventsByDate[dateKey]);
                    }
                }

                // On trie les événements par date pour un affichage chronologique
                eventsOfMonth.sort((a, b) => new Date(a.start_date_iso) - new Date(b.start_date_iso));

                if (eventsOfMonth.length === 0) {
                    eventListContainer.innerHTML =
                        '<p class="text-center text-muted mt-5">Aucune réservation pour ce mois.</p>';
                    return;
                }

                eventsOfMonth.forEach(event => {
                    const eventCard = createEventCard(event);
                    eventListContainer.appendChild(eventCard);
                });
            }

            // --- FONCTION D'AFFICHAGE POUR UN JOUR (inchangée) ---
            function displayEventsForDate(dateString) {
                const events = eventsByDate[dateString] || [];
                eventListContainer.innerHTML = '';

                if (events.length === 0) {
                    eventListContainer.innerHTML =
                        '<p class="text-center text-muted mt-5">Aucune réservation pour ce jour.</p>';
                    return;
                }

                events.forEach(event => {
                    const eventCard = createEventCard(event);
                    eventListContainer.appendChild(eventCard);
                });
            }

            // --- NOUVELLE FONCTION: Crée le HTML d'une carte (pour éviter la duplication de code) ---
            function createEventCard(event) {
                const eventCard = document.createElement('div');
                eventCard.innerHTML = `
                <a href="${event.details_url}" class="text-decoration-none text-dark d-block reservation-item">
                    <div class="cash-register-card shadow-sm border-0 rounded-3 mb-3">
                        <div class="card-body pb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <div class="d-flex justify-content-center align-items-center bg-success-subtle text-success rounded-circle" style="width: 50px; height: 50px;">
                                        <i class="ph-bold ph-calendar-check" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h3 class="fw-bold fs-6 mb-1 client-name">${event.client_nom}</h3>
                                    <p class="text-muted small mb-2">Salle: ${event.salle_nom}</p>
                                    <div class="d-flex align-items-center gap-2 small text-dark">
                                        <i class="ph-fill ph-clock"></i>
                                        <span class="fw-medium reservation-date">${event.start_date_formatted}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0">
                        <div class="card-footer bg-transparent border-0 pt-2">
                            <div class="row text-center">
                                <div class="col">
                                    <span class="text-muted small d-block">Total</span><strong class="fw-bold small">${event.montant_total}</strong>
                                </div>
                                <div class="col">
                                    <span class="text-muted small d-block">Versé</span><strong class="fw-bold small text-success">${event.montant_payer}</strong>
                                </div>
                                <div class="col">
                                    <span class="text-muted small d-block">Restant</span><strong class="fw-bold small text-danger">${event.montant_restant}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                `;
                return eventCard;
            }

            function formatDate(date) {
                const d = new Date(date);
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                const year = d.getFullYear();
                return `${year}-${month}-${day}`;
            }

            // --- MODIFICATION NÉCESSAIRE DANS LE CONTRÔLEUR ---
            // Pour que le tri fonctionne, assurez-vous que votre contrôleur ajoute la date au format ISO
            // 'start_date_iso' => \Carbon\Carbon::parse($reservation->start_date)->toIso8601String(),

            // Initialisation du calendrier
            updateCalendarDisplay();
        });
    </script>
@endsection
