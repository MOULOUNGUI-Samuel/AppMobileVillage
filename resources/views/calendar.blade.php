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
    <section class="px-6 pt-8 notification-top-area">
        <div class="d-flex justify-content-start align-items-center gap-4 py-3">
            <a href="{{ route('dashboard') }}" class="p1-color back-button flex-center">
                <i class="ph ph-caret-left"></i>
            </a>
            <h2>Mon calendrier</h2>
        </div>
    </section>

    <!-- La balise ouvrante est ici... -->
    <div class="schedule-section w-100 px-6 pt-8 overflow-hidden">
        <div class="d-flex justify-content-between align-items-center pb-5">
            <h6 class="">Mes réservations</h6>
            <div class="flex-center gap-3">
                <button id="prev-month-btn" class="month-navigation-button flex-center">
                    <i class="ph ph-caret-left"></i>
                </button>
                <div id="calendarModalOpenButton" style="cursor: pointer;">
                    <p id="currentMonthDisplay" class="fw-bold text-center">Juillet 2025</p>
                </div>
                <button id="next-month-btn" class="month-navigation-button flex-center">
                    <i class="ph ph-caret-right"></i>
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
    <div class="faq-accordion-area">
        <div class="faq-accordion theme-transition-3 d-flex justify-content-between align-items-center">
            <h6 class="text-14px">Recherche par période</h6>
            <div class="">
                <i class="ph-fill text-[24px] ph-caret-down ti-plus"> </i>
            </div>
        </div>
        <div class="theme-transition-6 h-0 overflow-hidden" style="">
            <div class="custom-border-area position-relative w-100 my-3">
                <div class="line-horizontal"></div>
            </div>
            <form id="filter-form" class="d-flex flex-wrap align-items-end gap-3">
                <div class="flex-grow-1">
                    <label for="startDate" class="form-label small">Date de début</label>
                    <input type="date" id="startDate" class="form-control" required>
                </div>
                <div class="flex-grow-1">
                    <label for="endDate" class="form-label small">Date de fin</label>
                    <input type="date" id="endDate" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Rechercher</button>
                <button type="button" id="reset-view-btn" class="btn btn-outline-secondary">Annuler la recherche</button>
            </form>
        </div>
    </div>

    <!-- Conteneur pour la barre des jours, affiché par défaut -->
    <div id="schedule-day-scroller" class="schedule-area">
        <div class="schedule-day d-flex justify-content-start align-items-center gap-3 overflow-auto pb-3">
            <!-- Les jours seront générés par JS ici -->
        </div>
    </div>
    </div>

    <!-- Conteneur pour la liste des événements (commun aux deux vues) -->
    <div id="event-list-container" class="d-flex flex-column gap-4 px-6 pt-4">
        <!-- Le contenu sera généré par JS ici -->
    </div>

    <!-- Le conteneur pour la liste des événements sera rempli ici -->
    <div id="event-list-container" class="d-flex flex-column gap-4 px-6 pt-4">
        <p class="text-center text-muted">Veuillez sélectionner un jour pour voir les réservations.</p>
    </div>


    <!-- =============== NOUVELLE MODALE POUR LES DÉTAILS DE LA RÉSERVATION ==================== -->
    <div id="reservationDetailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Détails de l'événement</h4>
                <button id="closeReservationDetailModal">×</button>
            </div>
            <div class="modal-body">
                <div class="info-box">
                    <div class="detail-row"><strong>Client :</strong> <span id="res-client"></span></div>
                    <div class="detail-row" style="border-bottom: none;"><strong>Salle :</strong> <span
                            id="res-salle"></span></div>
                </div>

                <h5>Informations supplémentaires</h5>
                <div class="detail-row"><span>Date début :</span> <span id="res-date-debut" class="fw-bold"></span></div>
                <div class="detail-row"><span>Date fin :</span> <span id="res-date-fin" class="fw-bold"></span></div>
                <div class="detail-row"><span>Montant salle :</span> <span id="res-montant-salle" class="fw-bold"></span>
                </div>
                <div class="detail-row"><span>Caution :</span> <span id="res-caution" class="fw-bold"></span></div>
                <div class="detail-row"><span>Montant total de services :</span> <span id="res-montant-services"
                        class="fw-bold"></span></div>
                <div class="detail-row"><span>Montant reduction :</span> <span id="res-reduction" class="fw-bold"></span>
                </div>
                <div class="detail-row fs-5" style="border-bottom: none;"><strong>Montant total à payer :</strong> <strong
                        id="res-total" class="text-primary"></strong></div>

                <hr>
                <h6><i class="ph ph-list-checks"></i> Liste des Services</h6>
                <div id="res-services-list" class="text-muted fst-italic">
                    <!-- Les services seront listés ici, ou "Aucun service inclus" -->
                </div>
            </div>
        </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // --- DONNÉES D'EXEMPLE BASÉES SUR VOTRE IMAGE ---
            const sampleEvents = {
                '2025-12-27': [
                    {
                        client: 'NZE MENDOME REBECCA',
                        salle: 'Site',
                        date_debut: 'samedi 27 décembre 2025',
                        date_fin: 'samedi 27 décembre 2025',
                        montant_salle: 4000000,
                        caution: 400000,
                        montant_services: 0,
                        reduction: 0,
                        total_a_payer: 4400000,
                        services_inclus: ['Aucun service inclus']
                    }
                ],
                '2025-07-25': [
                    {
                        client: 'John Doe',
                        salle: 'Salle de Conférence',
                        date_debut: 'vendredi 25 juillet 2025',
                        date_fin: 'vendredi 25 juillet 2025',
                        montant_salle: 500000,
                        caution: 50000,
                        montant_services: 150000,
                        reduction: 25000,
                        total_a_payer: 675000,
                        services_inclus: ['Projecteur Vidéo', 'Service café']
                    }
                ]
            };

            // --- GESTION DU DOM ---
            const eventListContainer = document.getElementById('event-list-container');

            // --- GESTION DE LA NOUVELLE MODALE ---
            const reservationModal = document.getElementById('reservationDetailModal');
            const closeReservationModalBtn = document.getElementById('closeReservationDetailModal');

            closeReservationModalBtn.onclick = () => reservationModal.style.display = 'none';
            window.onclick = (event) => {
                if (event.target == reservationModal) {
                    reservationModal.style.display = 'none';
                }
            };

            // Le reste du code du calendrier (navigation, etc.) reste le même
            // ... (collez ici le code du calendrier de la réponse précédente)
            const monthDisplay = document.getElementById('currentMonthDisplay');
            const dayContainer = document.getElementById('schedule-day-container');
            const prevMonthBtn = document.getElementById('prev-month-btn');
            const nextMonthBtn = document.getElementById('next-month-btn');

            let currentDate = new Date(); // La date est maintenant gérée dynamiquement
            currentDate.setDate(1);

            const monthFormatter = new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' });
            const weekdayFormatter = new Intl.DateTimeFormat('fr-FR', { weekday: 'short' });

            function changeMonth(direction) {
                currentDate.setMonth(currentDate.getMonth() + direction);
                updateCalendar();
            }

            prevMonthBtn.onclick = () => changeMonth(-1);
            nextMonthBtn.onclick = () => changeMonth(1);

            function updateCalendar() {
                monthDisplay.textContent = monthFormatter.format(currentDate).replace(/^\w/, c => c.toUpperCase());
                renderDayScroller();
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

                    if (sampleEvents[dateString]) {
                        button.classList.add('has-events');
                    }

                    button.innerHTML = `
                    <span class="fw-semibold">${i}</span>
                    <span class="date">${weekdayFormatter.format(dayDate).replace('.', '')}</span>
                `;

                    button.onclick = () => {
                        const currentActive = dayContainer.querySelector('.active');
                        if (currentActive) {
                            currentActive.classList.remove('active');
                        }
                        button.classList.add('active');
                        displayEventsForDate(dateString);
                    };

                    dayContainer.appendChild(button);
                }

                const today = new Date();
                const todayString = formatDate(today);
                const todayButton = dayContainer.querySelector(`[data-date='${todayString}']`);

                if (todayButton) {
                    todayButton.click();
                    todayButton.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                } else {
                    const firstButton = dayContainer.querySelector('.scheduleButton');
                    if (firstButton) {
                        firstButton.click();
                    } else {
                        displayEventsForDate(null); // Gère le cas d'un mois sans jours (ne devrait pas arriver)
                    }
                }
            }

            // --- FONCTION D'AFFICHAGE DE LA LISTE (MISE À JOUR) ---
            function displayEventsForDate(dateString) {
                const events = sampleEvents[dateString] || [];
                eventListContainer.innerHTML = '';

                if (events.length === 0) {
                    eventListContainer.innerHTML = '<p class="text-center text-muted">Aucune réservation pour ce jour.</p>';
                    return;
                }

                events.forEach((event, index) => {
                    const eventDiv = document.createElement('div');
                    // Utilise les classes de votre structure
                    eventDiv.className = 'w-100 event-list-item p-4';

                    // Formatage du prix
                    const formattedPrice = new Intl.NumberFormat('fr-FR').format(event.total_a_payer) + ' FCFA';

                    eventDiv.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start gap-4">
                        <div class="d-flex justify-content-start align-items-start gap-4">
                            <div class="icon-container flex-center">
                                <i class="ph ph-calendar-check"></i>
                            </div>
                            <div class="">
                                <p class="fw-bold event-name">${event.client}</p>
                                <p class="d-inline-flex justify-content-start align-items-center py-1 flex-wrap">
                                    <span class="event-category">Salle : ${event.salle}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-4">
                        <a href="#" class="event-details-link details-btn" data-date="${dateString}" data-index="${index}">
                            Voir les détails de la réservation
                        </a>
                        <div class="custom-border-area position-relative mx-3"></div>
                        <p class="fs-5 event-price">${formattedPrice}</p>
                    </div>
                `;
                    eventListContainer.appendChild(eventDiv);
                });

                // Attacher les écouteurs aux nouveaux boutons
                document.querySelectorAll('.details-btn').forEach(button => {
                    button.onclick = (e) => {
                        e.preventDefault(); // Empêche le lien de remonter la page
                        const date = button.dataset.date;
                        const index = button.dataset.index;
                        const event = sampleEvents[date][index];
                        showReservationDetails(event);
                    };
                });
            }

            // --- FONCTION POUR MONTRER LA NOUVELLE MODALE DE DÉTAILS ---
            function showReservationDetails(event) {
                const numberFormatter = new Intl.NumberFormat('fr-FR');

                document.getElementById('res-client').textContent = event.client;
                document.getElementById('res-salle').textContent = event.salle;
                document.getElementById('res-date-debut').textContent = event.date_debut;
                document.getElementById('res-date-fin').textContent = event.date_fin;
                document.getElementById('res-montant-salle').textContent = numberFormatter.format(event.montant_salle) + ' FCFA';
                document.getElementById('res-caution').textContent = numberFormatter.format(event.caution) + ' FCFA';
                document.getElementById('res-montant-services').textContent = numberFormatter.format(event.montant_services) + ' FCFA';
                document.getElementById('res-reduction').textContent = numberFormatter.format(event.reduction) + ' FCFA';
                document.getElementById('res-total').textContent = numberFormatter.format(event.total_a_payer) + ' FCFA';

                // Gérer la liste des services
                const servicesListEl = document.getElementById('res-services-list');
                servicesListEl.innerHTML = '';
                if (event.services_inclus && event.services_inclus.length > 0 && event.services_inclus[0] !== 'Aucun service inclus') {
                    const ul = document.createElement('ul');
                    ul.className = 'list-unstyled ps-3';
                    event.services_inclus.forEach(service => {
                        const li = document.createElement('li');
                        li.textContent = `- ${service}`;
                        ul.appendChild(li);
                    });
                    servicesListEl.appendChild(ul);
                } else {
                    servicesListEl.textContent = 'Aucun service inclus';
                }

                reservationModal.style.display = 'flex';
            }

            function formatDate(date) {
                const d = new Date(date);
                const month = ('0' + (d.getMonth() + 1)).slice(-2);
                const day = ('0' + d.getDate()).slice(-2);
                const year = d.getFullYear();
                return `${year}-${month}-${day}`;
            }

            // Initialisation
            updateCalendar();
        });
    </script>
@endsection