@extends('layouts.app')
@section('content')
    <!-- ======================= CSS FINAL ET NETTOYÉ ======================= -->
    <style>
        /* Masque la barre de défilement tout en gardant la fonctionnalité */
        .horizontal-scroll-container {
            -ms-overflow-style: none;
            /* IE et Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .horizontal-scroll-container::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari et Opera */
        }

        /* Définit la taille de chaque élément dans le slider */
        .scroll-item {
            width: 90%;
            /* Chaque item prend 90% de la largeur de l'écran sur mobile */
            flex-shrink: 0;
            /* ESSENTIEL: Empêche les items de se compresser pour tenir à l'écran */
        }

        /* Style pour la carte active */
        .cash-box-card.active {
            border: 2px solid var(--bs-primary) !important;
            /* Ajout de !important pour assurer la priorité */
            box-shadow: 0 .5rem 1rem rgba(0, 123, 255, .25) !important;
        }

        /* Style pour la liste des mouvements */
        .movement-item .amount {
            font-weight: 700;
            min-width: 120px;
            text-align: right;
        }

        .movement-item .amount.positive {
            color: var(--bs-success);
        }

        .movement-item .amount.negative {
            color: var(--bs-danger);
        }

        /* Ajustements pour les écrans plus grands */
        @media (min-width: 768px) {

            /* Tablettes */
            .scroll-item {
                width: 45%;
            }
        }

        @media (min-width: 992px) {

            /* Ordinateurs de bureau */
            .scroll-item {
                width: 32%;
            }
        }
    </style>

    <!-- ======================= HTML CORRIGÉ ======================= -->
    <section class="notification-top-area px-3 pt-4">
        <div class="d-flex justify-content-between align-items-center gap-4 mb-3">
            <a href="{{ route('dashboard') }}" class="back-button flex-center p-2 btn btn-light rounded-circle lh-1">
                <i class="ph ph-caret-left fs-5"></i>
            </a>
            <h2 class="fs-5 fw-bold mb-0 text-center" style="color:#4b2317">Liste des caisses</h2>
            <span style="width: 40px;"></span> <!-- Espaceur pour bien centrer le titre -->
        </div>

        <!-- Le conteneur pour le défilement horizontal -->
        <div id="cash-boxes-slider" class="horizontal-scroll-container d-flex flex-nowrap overflow-x-auto gap-3 py-2 px-3">

            @forelse ($caisses as $caisse)
                <div class="scroll-item">
                    {{-- La balise <a> a été supprimée, le clic est géré par JS sur la div.card --}}
                    {{-- CORRECTION 1: Ajout de la classe 'cash-box-card' pour le ciblage JS --}}
                    <div class="card cash-box-card border-0 shadow-sm h-100" style="border-radius: 1rem; cursor: pointer;"
                        data-caisse-id="{{ $caisse->id }}">
                        <div class="card-body">
                            <h5 class="fw-bold fs-6 mb-2 text-dark" style="font-size: 18px">
                                <i class="ph-fill ph-wallet me-2 text-muted"></i>
                                {{ Str::limit($caisse->nom  , 15, '...') }}
                            </h5>
                            <small class="text-muted" style="font-size: 16px">Solde actuel</small>
                            <p class="fs-4 fw-bolder text-dark mb-0">
                                {{ number_format($caisse->solde ?? 0, 0, ',', ' ') }} <span
                                    class="fs-6 fw-normal">FCFA</span>
                            </p>
                            <div class="d-flex align-items-center gap-2" style="font-size: 16px">
                                <i class="ph ph-user-circle fs-5 text-muted"></i>
                                {{-- Donnée dynamique depuis la relation 'user' --}}
                                <span class="small text-muted">{{ Str::limit($caisse->user->nom  , 15, '...') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-100 text-center text-muted p-5">
                    <p>Aucune caisse n'a été créée pour le moment.</p>
                </div>
            @endforelse

        </div>
    </section>

    <!-- NOUVEAU: Conteneur où les mouvements seront affichés dynamiquement -->
    <section class="mouvements-section px-3 pt-4 pb-5">
        <h3 class="fs-6 fw-bold text-muted mb-3">Historique des Transactions</h3>
        <!-- Champ de recherche -->
        <div class="search-area d-flex justify-content-between align-items-center gap-2 w-100 shadow mb-3"
            style="border-radius:10px;background:rgb(253, 255, 255)">
            <div class="search-box d-flex justify-content-start align-items-center gap-2 p-2 w-100 rounded-pill">
                <div class="flex-center ps-2">
                    <i class="ph ph-magnifying-glass"></i>
                </div>
                <input type="text" id="searchEmploye" placeholder="Rechercher..." class="border-0 bg-transparent w-100"
                    style="outline: none;" />
            </div>
        </div>
        <!-- AJOUTER CET ÉLÉMENT JUSTE ICI -->
        <div id="noResultsMessage" class="text-center text-muted mt-5 d-none">
            <i class="ph-xl ph-user-magnifying-glass"></i>
            <h5 class="mt-3">Aucun employé trouvé</h5>
            <p>Veuillez essayer avec un autre nom ou un autre rôle.</p>
        </div>
        <div id="mouvements-container" class="list-group">
            <p class="text-center text-muted p-4">Veuillez sélectionner une caisse pour voir son historique.</p>
        </div>
    </section>


    <!-- Votre HTML pour le slider et le conteneur des mouvements reste identique -->

    <!-- ======================= SCRIPT JAVASCRIPT FINAL ET CORRIGÉ ======================= -->
    <script>
        // On récupère les données du contrôleur (cette partie est correcte)
        const mouvementsByCaisse = @json($mouvementsByCaisse ?? []);

        document.addEventListener('DOMContentLoaded', function() {
            const sliderContainer = document.getElementById('cash-boxes-slider');
            const mouvementsContainer = document.getElementById('mouvements-container');
            const allCashBoxCards = document.querySelectorAll('.cash-box-card');

            // ----- CORRECTION 1: On sélectionne les éléments de recherche ici -----
            const searchInput = document.getElementById('searchEmploye'); // Votre champ de recherche
            const noResultsMessage = document.getElementById('noResultsMessage');
            let currentCaisseId = null; // Variable pour garder en mémoire la caisse sélectionnée

            if (!sliderContainer || allCashBoxCards.length === 0) {
                return;
            }

            // Écouteur de clic sur le slider (inchangé)
            sliderContainer.addEventListener('click', function(e) {
                const clickedCard = e.target.closest('.cash-box-card');
                if (!clickedCard) return;

                currentCaisseId = clickedCard.dataset.caisseId; // On mémorise la caisse
                renderMouvements(currentCaisseId, searchInput.value); // On affiche les mouvements

                allCashBoxCards.forEach(card => card.classList.remove('active'));
                clickedCard.classList.add('active');
            });

            // ----- CORRECTION 2: On ajoute un écouteur sur le champ de recherche -----
            searchInput.addEventListener('input', function() {
                // Quand on tape, on ré-affiche les mouvements de la caisse actuelle, mais avec le filtre de recherche
                if (currentCaisseId) {
                    renderMouvements(currentCaisseId, this.value);
                }
            });


            // ----- CORRECTION 3: La fonction renderMouvements accepte maintenant un terme de recherche -----
            function renderMouvements(caisseId, searchTerm = '') {
                const allMouvements = mouvementsByCaisse[caisseId] || [];
                mouvementsContainer.innerHTML = ''; // Toujours vider le conteneur

                // On normalise le terme de recherche
                const normalizedSearchTerm = searchTerm.toLowerCase().trim();

                // On filtre les mouvements AVANT de les afficher
                const filteredMouvements = allMouvements.filter(mouvement => {
                    const motif = mouvement.motif.toLowerCase();
                    const date = mouvement.formatted_date.toLowerCase();
                    // La condition de recherche : le motif OU la date doit inclure le terme
                    return motif.includes(normalizedSearchTerm) || date.includes(normalizedSearchTerm);
                });

                let visibleCount = filteredMouvements.length;

                if (visibleCount === 0) {
                    mouvementsContainer.innerHTML =
                        '<div class="text-center p-5 text-muted"><p>Aucun mouvement trouvé pour cette recherche.</p></div>';
                    // On s'assure que le message "Aucun employé trouvé" est bien masqué s'il y a des mouvements mais pas de résultat de recherche
                    noResultsMessage.classList.add('d-none');
                    return;
                }

                filteredMouvements.forEach(mouvement => {
                    // On génère l'URL d'impression comme avant
                    let printUrl =
                        "{{ route('pdf.mouvement_caisse', ['id' => 'MOUVEMENT_ID_PLACEHOLDER']) }}";
                    printUrl = printUrl.replace('MOUVEMENT_ID_PLACEHOLDER', mouvement.id);

                    // On injecte le HTML
                    const mouvementHTML = `
                    <div class="list-group-item d-flex justify-content-between align-items-center movement-item mb-1 shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="p-2 ${mouvement.icon_bg_class} rounded-circle me-3">
                                <i class="${mouvement.icon}"></i>
                            </div>
                            <div>
                                <p class="fw-semibold mb-0">${mouvement.motif}</p>
                                <small class="text-muted">${mouvement.formatted_date}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="amount ${mouvement.css_class} me-3">${mouvement.formatted_amount}</div>
                            <a href="${printUrl}" target="_blank" class="btn btn-light btn-sm p-2 lh-1" title="Imprimer le reçu">
                                <i class="ph-bold ph-printer fs-5"></i>
                            </a>
                        </div>
                    </div>
                `;
                    mouvementsContainer.innerHTML += mouvementHTML;
                });

                // On s'assure de cacher le message "Aucun employé" car on a trouvé des résultats
                noResultsMessage.classList.add('d-none');
            }

            // On simule un clic sur la première caisse pour l'affichage par défaut (inchangé)
            if (allCashBoxCards.length > 0) {
                allCashBoxCards[0].click();
            }
        });
    </script>
@endsection
