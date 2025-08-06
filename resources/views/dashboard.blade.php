@extends('layouts.app')
@section('content')

    <!-- Header Section Start -->
    <section class="d-flex justify-content-between align-items-center home-header-section w-100">
        <div class="d-flex justify-content-start align-items-center gap-4">
            <div class="profile-img">
                <img src="assets/img/myboto.jpeg" alt="Photo de profil" />
            </div>
            <div>
                <h3 class="heading-3 pb-2">Madame MYBOTO</h3>
                <p class="d-inline-flex gap-2 location justify-content-start align-items-center">
                    Administrateur<i class=""></i>
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
    <section class="search-section w-100 px-6 pt-8">
        <div class="">
            <p class="date">{{ \Carbon\Carbon::now()->translatedFormat('l, j F') }}</p>
        </div>
        <div class="search-area d-flex justify-content-between align-items-center gap-2 w-100">
            <div class="search-box d-flex justify-content-start align-items-center gap-2 p-3 w-100">
                <div class="flex-center">
                    <i class="ph ph-magnifying-glass"></i>
                </div>
                <input type="text" placeholder="Tapez votre recherche..." />
            </div>

            <!-- <div class="search-button">
                  <button class="flex-center" id="view plusModalOpenButton">
                    <i class="ph ph-sliders-horizontal"></i>
                  </button>
                </div> -->
        </div>
        </div>
    </section>

    <!-- Doctor Specialist End -->

    <!-- Top Doctor Start -->
    <section class="px-6 pt-6 top-doctor-area">
        <div class="d-flex justify-content-between align-items-center">
            <h3>A venir</h3>
            <button class="view-all" id="topDoctorModalOpenButton">
                Voir plus
            </button>
        </div>

        <div class="d-flex flex-column gap-4 pt-4">

            <!-- les reservations à venir -->
            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <div class="w-100 top-doctor-item p-4">
                <div class="d-flex justify-content-between align-items-start gap-4">
                    <div class="d-flex justify-content-start align-items-start gap-4">
                        <div class="doctor-img flex-center position-relative">
                            <img src="{{ asset('assets/img/reservation ok.jpg') }}" class="doctor-main-img"
                                alt="Réservation OK" />
                            <img src="{{ asset('assets/img/active.png') }}" class="active-badge" alt="Statut actif" />
                        </div>

                        <div>
                            <p class="fw-bold name">JULIETTE LONGA</p>
                            <p class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap">
                                <span class="category">Mariage</span>
                                <i class="ph ph-dot fs-4"></i>
                            </p>
                            <div class="d-flex justify-content-start align-items-center flex-wrap">
                                <div class="time">
                                    <i class="ph-fill ph-clock"></i> 12H00 - 02H00
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <!-- <a href="doctor-profile.html" class="appointment-link d-block p1-color">Reservation</a> -->
                    <a href="{{ route('doctor.profile') }}" class="appointment-link d-block p1-color">Reservation</a>

                    <div class="custom-border-area position-relative mx-3">
                        <div class="line-horizontal"></div>
                    </div>
                    <p class="fs-5 fw-bold"></p>
                </div>
            </div>

            <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
            <script src="{{ asset('assets/js/script.js') }}"></script>


        </div>

    </section>
    <!-- Top Doctor End -->

@endsection