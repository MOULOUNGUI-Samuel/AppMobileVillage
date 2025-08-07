@extends('layouts.app')
@section('content')
    <!-- Header Section Start -->

    <!-- Doctor Specialist End -->

    <!-- Top Doctor Start -->
    <section class="px-6 pt-6 top-doctor-area">
        <div class="d-flex justify-content-between align-items-center">
            <h5>A venir</h3>
        </div>

        <div class="d-flex flex-column gap-4 pt-4">
            <div class="search-area d-flex justify-content-between align-items-center gap-2 w-100 shadow"
                style="border-radius:10px;background:rgb(253, 255, 255)">
                <div class="search-box d-flex justify-content-start align-items-center gap-2 p-2 w-100 rounded-pill">
                    <div class="flex-center ps-2">
                        <i class="ph ph-magnifying-glass"></i>
                    </div>
                    <input type="text" id="searchInput" placeholder="Recherche..." class="border-0 bg-transparent w-100"
                        style="outline: none;" />
                </div>
            </div>
            <!-- les reservations à venir -->
            <!-- Assurez-vous d'avoir inclus Bootstrap 5 et Phosphor Icons dans votre projet -->
            @foreach ($reservations as $reservation)
                <a href="{{ route('doctor.profile') }}" class="text-decoration-none text-dark d-block reservation-item">
                    <div class="cash-register-card shadow-sm border-0 rounded-3 mb-3">
                        <!-- Section 1: Informations sur la réservation (Client, Salle, Date) -->
                        <div class="card-body pb-2">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Icône -->
                                <div class="flex-shrink-0">
                                    <div class="d-flex justify-content-center align-items-center bg-success-subtle text-success rounded-circle"
                                        style="width: 50px; height: 50px;">
                                        <i class="ph-bold ph-calendar-check" style="font-size: 24px;"></i>
                                    </div>
                                </div>

                                <!-- Détails -->
                                <div class="flex-grow-1">
                                    <h3 class="fw-bold fs-6 mb-1 client-name">
                                        {{ Str::limit($reservation->client->nom . ' ' . $reservation->client->prenom, 20, '...') }}
                                    </h3>
                                    <p class="text-muted small mb-2">
                                        Salle: {{ $reservation->salle->nom }}
                                    </p>
                                    <div class="d-flex align-items-center gap-2 small text-dark">
                                        <i class="ph-fill ph-clock"></i>
                                        <span
                                            class="fw-medium reservation-date">{{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($reservation->start_date)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ligne de séparation -->
                        <hr class="my-0">

                        <!-- Section 2: Détails Financiers -->
                        <div class="card-footer bg-transparent border-0 pt-2">
                            <div class="row text-center">
                                <!-- Montant Total -->
                                <div class="col">
                                    <span class="text-muted small d-block">Total</span>
                                    <strong
                                        class="fw-bold small">{{ \App\Helpers\DateHelper::formatNumber($reservation->montant_total) }}</strong>
                                </div>
                                <!-- Montant Versé -->
                                <div class="col">
                                    <span class="text-muted small d-block">Versé</span>
                                    <strong
                                        class="fw-bold small text-success">{{ \App\Helpers\DateHelper::formatNumber($reservation->montant_payer) }}</strong>
                                </div>
                                <!-- Montant Restant -->
                                <div class="col">
                                    <span class="text-muted small d-block">Restant</span>
                                    <strong
                                        class="fw-bold small text-danger">{{ \App\Helpers\DateHelper::formatNumber($reservation->montant_total - $reservation->montant_payer) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
            <!-- AJOUTER CET ÉLÉMENT -->
            <div id="noResultsMessage" class="text-center text-muted mt-5 d-none">
                <i class="ph-xl ph-magnifying-glass"></i>
                <h5 class="mt-3">Aucune réservation trouvée</h5>
                <p>Essayez de rechercher avec un autre nom ou une autre date.</p>
            </div>
        </div>

    </section>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const reservationItems = document.querySelectorAll('.reservation-item');
        const noResultsMessage = document.getElementById('noResultsMessage');

        function normalize(str) {
            return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
        }

        searchInput.addEventListener('input', function () {
            const searchTerm = normalize(this.value);
            let found = false;

            reservationItems.forEach(function (item) {
                const nameEl = item.querySelector('.client-name');
                const dateEl = item.querySelector('.reservation-date');

                const name = nameEl ? normalize(nameEl.textContent) : '';
                const date = dateEl ? normalize(dateEl.textContent) : '';

                const match = name.includes(searchTerm) || date.includes(searchTerm);

                if (match) {
                    item.classList.remove('d-none');
                    found = true;
                } else {
                    item.classList.add('d-none');
                }
            });

            // Affiche ou masque le message
            noResultsMessage.classList.toggle('d-none', found);
        });
    });
</script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <!-- Top Doctor End -->
@endsection
