@extends('layouts.app')

@section('content')
    @php
        $montantPaye = $reservation->montant_payer;
        $montantTotal = $reservation->montant_total;
        $pourcentage = $montantTotal > 0 ? ($montantPaye / $montantTotal) * 100 : 0;
    @endphp
    {{-- HEADER --}}
    <section class="notification-top-area px-6 d-flex justify-content-between mx-3">
        <div class="d-flex justify-content-start align-items-center gap-4 py-3">
            <a href="{{ url()->previous() }}" class="p1-color back-button flex-center">
                <i class="ph ph-caret-left"></i>
            </a>
        </div>
        <div class="d-flex justify-content-end align-items-center gap-4 py-3">
            <h5>Détails de la reservation</h5>
        </div>
    </section>

    <div class="container py-4" style="margin-top: -20px">
        {{-- RÉSUMÉ FINANCIER --}}
        <div class="cash-register-card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{route('facture_directe',$reservation->id)}}" class="btn btn-outline-primary  px-4" type="button" style="font-size: 12px">
                        <i class="fas fa-invoice mr-2"></i>Facture
                    </a>
                    <a href="{{route('releve_client',$reservation->id)}}" class="btn btn-outline-primary  px-4" type="button" style="font-size: 12px">
                        <i class="fas fa-file-2 mr-2"></i>Relevé du compte
                    </a>

                </div>
                <div class="d-flex justify-content-start align-items-center gap-4 pt-3">
                    <div class="doctor-img">
                        <img src="{{ asset('assets/img/reservation ok.jpg') }}" alt="Réservation"
                            class="img-fluid rounded-circle border border-5 border-white shadow object-fit-cover"
                            width="76" height="76">
                    </div>

                    <div>
                        <h5 class="mb-0">{{ $reservation->client->nom }} {{ $reservation->client->prenom }}</h5>
                        <p class="paragraph-small pt-1 text-muted">{{ $reservation->description ?? 'Réservation' }}</p>
                    </div>
                </div>
                <div class="progress my-2" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pourcentage }}%;"
                        aria-valuenow="{{ $montantPaye }}" aria-valuemin="0" aria-valuemax="{{ $montantTotal }}">
                        {{ number_format($pourcentage, 0) }}%
                    </div>
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Total facturé :</span>
                        <strong class="fs-5">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-success">Montant versé :</span>
                        <strong class="text-success">{{ number_format($montantPaye, 0, ',', ' ') }} FCFA</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-danger">Solde restant :</span>
                        <strong class="text-danger fw-bold">
                            {{ number_format(max($montantTotal - $montantPaye, 0), 0, ',', ' ') }} FCFA
                        </strong>
                    </li>
                    @if ($caution)
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center px-0 border-top pt-2 mt-2 text-muted">
                            <span>Caution versée :</span>
                            <strong>{{ number_format($caution->montant_caution, 0, ',', ' ') }} FCFA</strong>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- INFORMATIONS --}}
        <div class="cash-register-card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold fs-6 mb-3">Informations</h5>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div><i class="ph ph-barcode me-2 text-muted"></i><strong>Réf</strong></div>
                        <div>{{ $reservation->ref_quitance }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div><i class="ph ph-door me-2 text-muted"></i><strong>Salle</strong></div>
                        <div>{{ $reservation->salle->nom ?? '—' }}</div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div><i class="ph ph-calendar-check me-2 text-muted"></i><strong>Début</strong></div>
                        <div>
                            {{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($reservation->start_date)) }}
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div><i class="ph ph-calendar-x me-2 text-muted"></i><strong>Fin</strong></div>
                        <div>
                            {{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($reservation->end_date)) }}
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div><i class="ph ph-info me-2 text-muted"></i><strong>Statut</strong></div>
                        <div>
                            @if ($reservation->statut === 'validee')
                                <span class="badge bg-success">Validée</span>
                            @elseif($reservation->statut === 'rejetee')
                                <span class="badge bg-danger">Rejetée</span>
                            @else
                                <span class="badge bg-warning text-dark">En attente</span>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>
{{-- SERVICES INCLUS --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title fw-bold fs-6 mb-0">Services inclus</h5>
            <span class="badge bg-secondary">
                {{ $reservationServices->count() }} service{{ $reservationServices->count() > 1 ? 's' : '' }}
            </span>
        </div>

        @forelse ($reservationServices as $srv)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <div class="me-3">
                    <div class="fw-semibold">
                        {{ $srv['service_nom'] ?? 'Service' }}
                    </div>
                    <div class="text-muted small">
                        {{ (int)$srv['quantite'] }} × {{ number_format((float)$srv['prix_unitaire'], 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div class="fw-bold">
                    {{ number_format((float)$srv['total'], 0, ',', ' ') }} FCFA
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                Aucun service ajouté à cette réservation.
            </div>
        @endforelse

        @if ($reservationServices->isNotEmpty())
            <div class="d-flex justify-content-between align-items-center pt-3">
                <span class="fw-semibold">Total services</span>
                <span class="fw-bold">
                    {{ number_format((float)$total_services, 0, ',', ' ') }} FCFA
                </span>
            </div>
        @endif
    </div>
</div>


    </div>
@endsection
