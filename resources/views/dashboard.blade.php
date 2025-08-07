@extends('layouts.app')
@section('content')
    <!-- Header Section Start -->
 <!-- =================================== -->
    <!--   DEBUT DE LA SECTION A FIGER       -->
    <!-- =================================== -->
    <div class="sticky-top bg-white shadow-sm">
        <!-- Header Section Start -->
        <section class="d-flex justify-content-between align-items-center home-header-section w-100 px-3 pt-3 ">
            <div class="d-flex justify-content-start align-items-center gap-3 mt-3">
                <div class="profile-img">
                    <img src="assets/img/myboto.jpeg" alt="Photo de profil" />
                </div>
                <div>
                    <h3 class="heading-3 pb-2">Madame MYBOTO</h3>
                    <p class="d-inline-flex gap-2 location justify-content-start align-items-center">
                        Administrateur
                    </p>
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center header-right gap-2 flex-wrap">
                <button class="p-2 flex-center" id="notificationModalOpenButton">
                    <i class="ph ph-bell fs-5"></i>
                    <span class="notification"></span>
                </button>
            </div>
        </section>
        <!-- Header Section End -->

        <!-- Search Section Start -->
        <section class="search-section w-100 px-3 pt-3 pb-3">
            <div class="search-area d-flex justify-content-between align-items-center gap-2 w-100">
                <div class="search-box d-flex justify-content-start align-items-center gap-2 p-2 w-100 rounded-pill">
                    <div class="flex-center ps-2">
                        <i class="ph ph-magnifying-glass"></i>
                    </div>
                    <input type="text" placeholder="Tapez votre recherche..." class="border-0 bg-transparent w-100" style="outline: none;" />
                </div>
            </div>
        </section>
    </div>
    <!-- =================================== -->
    <!--     FIN DE LA SECTION A FIGER       -->
    <!-- =================================== -->

    <!-- Doctor Specialist End -->

    <!-- Top Doctor Start -->
    <section class="px-6 pt-6 top-doctor-area">
        <div class="d-flex justify-content-between align-items-center">
            <h3>A venir</h3>
            {{-- <button class="view-all" id="topDoctorModalOpenButton">
                Voir plus
            </button> --}}
        </div>

        <div class="d-flex flex-column gap-4 pt-4">

            <!-- les reservations à venir -->
            <!-- Assurez-vous d'avoir inclus Bootstrap 5 et Phosphor Icons dans votre projet -->
            @foreach ($reservations as $reservation)
                <a href="{{ route('doctor.profile') }}" class="text-decoration-none text-dark d-block">
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
                                    <h3 class="fw-bold fs-6 mb-1">
                                        {{ Str::limit($reservation->client->nom . ' ' . $reservation->client->prenom, 20, '...') }}
                                    </h3>
                                    <p class="text-muted small mb-2">
                                        Salle: {{ $reservation->salle->nom }}
                                    </p>
                                    <div class="d-flex align-items-center gap-2 small text-dark">
                                        <i class="ph-fill ph-clock"></i>
                                        <span
                                            class="fw-medium">{{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($reservation->start_date)) }}</span>
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
        </div>

    </section>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <!-- Top Doctor End -->
@endsection
