@extends('layouts.app') {{-- Assurez-vous que le nom de votre layout est correct --}}

@section('content')

{{-- MODALE DE CRÉATION DE CAISSE --}}
<div id="creationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Création d'une caisse</h2>
            <span class="close-btn">×</span>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="service-name">Nom du service</label>
                    <input type="text" id="service-name" placeholder="Nom du service">
                </div>
                <div class="form-group">
                    <label for="manager-select">Sélectionner un gérant</label>
                    <select id="manager-select">
                        <option value="" disabled selected>Sélectionner un gérant...</option>
                        <option value="imelda">IMELDA EYAMBA</option>
                        <option value="olivia">MBADINGA Olivia</option>
                        <option value="danielle">Boucka Moussirou Danielle</option>
                    </select>
                </div>
                {{-- Vous pouvez ajouter un bouton de soumission ici --}}
                <button type="submit">Créer</button>
            </form>
        </div>
    </div>
</div>


{{-- CONTENU PRINCIPAL DE LA PAGE --}}
<div class="main-container">
    <header class="main-header">
        <h1>Gestion des caisses</h1>
        <div class="header-actions">
            {{-- Le bouton "Créer une caisse" ouvrira la modale --}}
            <button id="openModalBtn"><i class="fas fa-plus-circle"></i> Créer une caisse</button>
            <button><i class="fas fa-list-ul"></i> Nature de mouvement</button>
        </div>
    </header>

    {{-- SYSTÈME D'ONGLETS COULISSANTS --}}
    <div class="tab-container">
        <ul class="tab-buttons">
            <div class="slider"></div>
            <li class="tab-button active" data-tab="caisse-content">Caisse</li>
            <li class="tab-button" data-tab="rapport-content">Dernières transaction</li>
        </ul>
    </div>
    
    <main class="tab-content-container">
        {{-- Contenu pour l'onglet "Caisse" --}}
        <div id="caisse-content" class="tab-content active">
            <div class="cards-container">
                
                {{-- Note pour Laravel : Utilisez une boucle Blade ici --}}
                {{-- @foreach ($caisses as $caisse) --}}
                <div class="cash-register-card">
                    <div class="card-header">
                        <i class="fas fa-cog settings-icon"></i>
                        <button class="actions-btn">Actions <i class="fas fa-chevron-down"></i></button>
                    </div>
                    <div class="card-body">
                        <h2>Caisse Principale</h2>
                        <p class="amount">60 600 000 Fcfa</p>
                        <p class="manager">Gestionnaire : IMELDA EYAMBA</p>
                    </div>
                    <div class="card-footer">
                        <button class="view-movements-btn"><i class="fas fa-chart-line"></i> Afficher les mouvements</button>
                    </div>
                </div>
                {{-- @endforeach --}}

                <div class="cash-register-card">
                    <div class="card-header">
                        <i class="fas fa-cog settings-icon"></i>
                         <button class="actions-btn">Actions</button>
                    </div>
                    <div class="card-body">
                        <h2>RACKY MBOUNOU FLURIONCIA FRANCIS</h2>
                        <p class="amount">0 Fcfa</p>
                        <p class="manager">Gestionnaire : MBADINGA Olivia</p>
                    </div>
                    <div class="card-footer">
                        <button class="view-movements-btn"><i class="fas fa-chart-line"></i> Afficher les mouvements</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenu pour l'onglet "Rapport financier" --}}
        <div id="rapport-content" class="tab-content">
            <div class="report-placeholder">
                 <i class="fas fa-chart-pie"></i>
                 <h2>Rapports Financiers</h2>
                 <p>Le contenu des rapports financiers s'affichera ici.</p>
                 {{-- Vous pouvez charger des données via une requête AJAX ici --}}
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Gestion de la modale de création de caisse
    document.addEventListener('DOMContentLoaded', function() {
        const openModalBtn = document.getElementById('openModalBtn');
        const modal = document.getElementById('creationModal');
        const closeBtn = modal.querySelector('.close-btn');

        if (openModalBtn && modal && closeBtn) {
            // Ouvrir la modale
            openModalBtn.addEventListener('click', function() {
                modal.style.display = 'block';
            });

            // Fermer la modale
            closeBtn.addEventListener('click', function() {
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
@endsection