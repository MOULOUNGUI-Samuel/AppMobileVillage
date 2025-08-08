@extends('layouts.app')
@section('content')
    <!-- ======================= CODE CORRIGÉ ======================= -->

    <!-- On ouvre la balise principale -->
    <!-- ======================= CSS POUR LA NOUVELLE FONCTIONNALITÉ ======================= -->
    
    <section class="px-6 pt-8 notification-top-area mx-3" style="margin-top: -30px">
        <div class="d-flex justify-content-between align-items-center gap-4 py-3 mx-2">
            <a href="{{ route('dashboard') }}" class="back-button flex-center">
                <i class="ph ph-caret-left"></i>
            </a>
            <h2 style="color:#4b2317">Liste des utilisateurs</h2>
        </div>
    </section>

    <section class="invite-friends-area px-6 py-8"  style="margin-top: -25px">

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

        <!-- Liste des employés -->
        <div id="employeList">
            @foreach ($employers as $employe)
                <div class="d-flex flex-column gap-4 mb-3 employe-item">
                    <div class="d-flex justify-content-between align-items-center item shadow bg-light"
                        style="border-radius: 10px;border:none">
                        <div class="d-flex justify-content-start align-items-center gap-4">
                            <div>
                                <img src="{{ asset('assets/img/user.png') }}" alt="" class="shadow" width="50"
                                    style="border-radius: 10px" />
                            </div>
                            <div>
                                <p class="fw-semibold employe-nom">
                                    {{ Str::limit($employe->nom , 15, '...') }}</p>
                                <p class="p-sm employe-role">{{ $employe->role_user }}</p>
                            </div>
                        </div>

                        <div class="message-button shadow">
                            <a href="#"><i class="ph-light ph-eye"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- AJOUTER CET ÉLÉMENT JUSTE ICI -->
        <div id="noResultsMessage" class="text-center text-muted mt-5 d-none">
            <i class="ph-xl ph-user-magnifying-glass"></i>
            <h5 class="mt-3">Aucun employé trouvé</h5>
            <p>Veuillez essayer avec un autre nom ou un autre rôle.</p>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Sélectionner tous les éléments nécessaires une seule fois
            const searchInput = document.getElementById('searchEmploye');
            const employeItems = document.querySelectorAll('.employe-item');
            const noResultsMessage = document.getElementById('noResultsMessage');
    
            // 2. Ajouter l'écouteur d'événement sur le champ de recherche
            searchInput.addEventListener('input', function () {
                
                // On nettoie le terme de recherche (minuscules + sans espaces inutiles)
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0; // Un compteur pour savoir combien d'éléments sont visibles
    
                // 3. Boucler sur chaque employé
                employeItems.forEach(item => {
                    const nomElement = item.querySelector('.employe-nom');
                    const roleElement = item.querySelector('.employe-role');
    
                    // S'assurer que les éléments existent avant de lire leur contenu
                    const nom = nomElement ? nomElement.textContent.toLowerCase().trim() : '';
                    const role = roleElement ? roleElement.textContent.toLowerCase().trim() : '';
    
                    // 4. Condition de recherche (le terme doit être inclus dans le nom OU le rôle)
                    const isMatch = nom.includes(searchTerm) || role.includes(searchTerm);
    
                    // 5. Cacher ou afficher en utilisant les classes Bootstrap (plus fiable)
                    if (isMatch) {
                        item.classList.remove('d-none'); // On AFFICHE en retirant la classe d-none
                        visibleCount++; // On incrémente le compteur
                    } else {
                        item.classList.add('d-none');    // On CACHE en ajoutant la classe d-none
                    }
                });
    
                // 6. Afficher ou cacher le message "Aucun résultat" en fonction du compteur
                if (visibleCount === 0) {
                    noResultsMessage.classList.remove('d-none'); // Affiche le message
                } else {
                    noResultsMessage.classList.add('d-none');    // Cache le message
                }
            });
        });
    </script>
@endsection
