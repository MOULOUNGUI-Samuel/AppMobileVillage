<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="{{ asset('assets/img/fav-logo.png') }}" type="image/x-icon" />
  <title>Détails de la réservation</title>

  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <style>
    .header-section {
      background-color: #009281;
      color: #fff;
      border-bottom-left-radius: 2rem;
      border-bottom-right-radius: 2rem;
      padding: 1.5rem;
      text-align: center;
    }

    .profile-image {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      object-fit: cover;
      border: 5px solid #fff;
      margin-top: -70px;
    }

    .info-box {
      background-color: #f8f9fa;
      border-radius: 1rem;
      padding: 1rem 1.5rem;
      margin-top: 1rem;
    }

    .info-item {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      border-bottom: 1px solid #dee2e6;
    }

    .info-item:last-child {
      border-bottom: none;
    }

    .action-button {
      background-color: #4d90fe;
      border-color: #4d90fe;
      color: white;
      border-radius: 1.25rem;
    }

    .action-button:hover {
      background-color: #8DC7CC;
      border-color: #8DC7CC;
      color: white;
    }
  </style>
</head>
<body>
  <main class="pb-5">
    <!-- Header with photo and actions -->
    <div class="header-section position-relative">
      <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-circle">
          <i class="ph ph-caret-left"></i>
        </a>
      </div>

      <img src="{{ asset('assets/img/reservation ok.jpg') }}" alt="Réservation" class="profile-image mx-auto d-block" />

      <h4 class="mt-3 mb-1">JULIETTE LONGA</h4>
      <p class="mb-0">Réservation - Mariage</p>
    </div>

    <!-- Infos -->
    <div class="container">
      <div class="info-box shadow-sm">
        <div class="info-item"><strong>Référence :</strong> <span>FACT-2025-06-26 16:19:20</span></div>
        <div class="info-item"><strong>Salle :</strong> <span>Site</span></div>
        <div class="info-item"><strong>Date début :</strong> <span>samedi 27 décembre 2025</span></div>
        <div class="info-item"><strong>Date fin :</strong> <span>samedi 27 décembre 2025</span></div>
        <div class="info-item"><strong>Montant total :</strong> <span>4 400 000 FCFA</span></div>
        <div class="info-item"><strong>Statut :</strong> <span class="badge bg-success">Validée</span></div>
      </div>

      <!-- Dropdown actions -->
      <div class="mt-4 text-center">
        <div class="dropdown">
          <button class="btn dropdown-toggle action-button px-4" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Actions
          </button>
          <ul class="dropdown-menu mt-2" aria-labelledby="actionDropdown">
            <li><a class="dropdown-item" href="#">📄 Facture du client</a></li>
            <li><a class="dropdown-item" href="#">📊 Relevé du compte du client</a></li>
            <li><a class="dropdown-item" href="#">📎 Voir les détails de l’évènement</a></li>
          </ul>
        </div>
      </div>
    </div>
  </main>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
