@extends('layouts.app')

@section('content')
    {{-- Préloader --}}
    
    {{-- Le titre de la page doit être dans la section <head>, gérée par le layout principal. --}}
    {{-- Il est préférable de ne pas mettre de balise <title> ici. --}}

    <section class="px-6 pt-8 notification-top-area">
        <div class="d-flex justify-content-between align-items-center gap-4 py-3">
            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('dashboard') }}" class="back-button flex-center">
                    <i class="ph ph-caret-left"></i>
                </a>
                <h2>Utilisateurs</h2>
            </div>
            <button id="openUserModalBtn" class="btn btn-primary"><i class="fas fa-plus-circle"></i></button>
        </div>
    </section>

    <!-- Footer Menu Start -->
    <div class="footer-menu-area">
        <div class="footer-menu flex justify-content-center align-items-center">
            <div class="footer-menu flex justify-content-center align-items-center">
                <div class="d-flex align-items-center h-100 w-100">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex-fill text-center">
                        <i class="ph ph-house-line link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"></i>
                    </a>

                    <!-- Calendrier -->
                    <a href="{{ route('calendar') }}" class="flex-fill text-center">
                        <i class="ph-fill ph-calendar link-item {{ request()->routeIs('calendar') ? 'active' : '' }}"></i>
                    </a>

                    <!-- Finances -->
                    <a href="{{ route('finances') }}" class="flex-fill text-center">
                        <i class="ph ph-wallet link-item {{ request()->routeIs('finances') ? 'active' : '' }}"></i>
                    </a>

                    <!-- Utilisateurs -->
                    <a href="{{ route('utilisateurs') }}" class="flex-fill text-center">
                        <i class="ph ph-users link-item {{ request()->routeIs('utilisateurs') ? 'active' : '' }}"></i>
                    </a>
                </div>
            </div>

            <!-- bouton ou icone -->
            <div class="plus-icon position-absolute">
                <div class="position-relative">
                    <button id="specialityModalOpenButton">
                        <img src="{{ asset('assets/img/plus-icon-bg.png') }}" class="" alt="" />
                        <i class="ph ph-plus-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Menu End -->

    {{-- MODALE DE CRÉATION D'UTILISATEUR --}}
    <div id="userCreationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Création d'un utilisateur</h2>
                <span class="close-btn">×</span>
            </div>
            <div class="modal-body">
                <form class="d-flex flex-column gap-3">
                    <div class="form-group">
                        <label for="user-name">Nom complet</label>
                        <input type="text" id="user-name" placeholder="Nom complet">
                    </div>
                    <div class="form-group">
                        <label for="user-email">Adresse mail</label>
                        <input type="email" id="user-email" placeholder="Adresse mail">
                    </div>
                    <div class="form-group">
                        <label for="user-role">Rôle</label>
                        <select id="user-role">
                            <option value="admin">Administrateur</option>
                            <option value="gestionnaire">Gestionnaire</option>
                            <option value="utilisateur">Utilisateur</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary" style="background-color: #009281; border: none; border-radius: 10px; cursor: pointer; padding: 12px; ">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODALE DE VISUALISATION D'UTILISATEUR --}}
    <div id="userViewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Informations de l'utilisateur</h2>
                <span class="close-btn">×</span>
            </div>
            <div class="modal-body">
                <div class="user-info">
                    <p><strong>Nom complet :</strong> <span id="view-user-name"></span></p>
                    <p><strong>Email :</strong> <span id="view-user-email"></span></p>
                    <p><strong>Rôle :</strong> <span id="view-user-role"></span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALE D'ÉDITION D'UTILISATEUR --}}
    <div id="userEditModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Édition de l'utilisateur</h2>
                <span class="close-btn">×</span>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="edit-user-name">Nom complet</label>
                        <input type="text" id="edit-user-name" placeholder="Nom complet">
                    </div>
                    <div class="form-group">
                        <label for="edit-user-email">Email</label>
                        <input type="email" id="edit-user-email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="edit-user-role">Rôle</label>
                        <select id="edit-user-role">
                            <option value="admin">Administrateur</option>
                            <option value="gestionnaire">Gestionnaire</option>
                            <option value="utilisateur">Utilisateur</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary" style="background-color: #009281; border: none; border-radius: 10px; cursor: pointer; padding: 12px; ">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODALE DE CONFIRMATION DE SUPPRESSION --}}
    <div id="userDeleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmation de suppression</h2>
                <span class="close-btn">×</span>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
                <div class="modal-actions">
                    <button id="confirmDeleteBtn" class="btn btn-danger">Supprimer</button>
                    <button id="cancelDeleteBtn" class="btn btn-secondary">Annuler</button>
                </div>
            </div>
        </div>
    </div>
                

    <!-- Liste des utilisateurs -->
    <div class="px-6 py-8 pb-45 w-100">
        <div class="d-flex flex-column gap-3">

            <!-- utilisateur Item 1 -->
            <div class="w-100 top-doctor-item p-5 message-item user-item" 
                 data-name="Madame MYBOTO" 
                 data-email="madame.myboto@example.com" 
                 data-role="Administrateur">
                <div class="d-flex justify-content-between align-items-center gap-4 text-decoration-none">
                    <div class="d-flex justify-content-start align-items-center gap-3">
                        <div class="doctor-img flex-center">
                            <img src="{{ asset('assets/img/myboto.jpeg') }}" class="h-100" alt="Avatar" />
                        </div>
                        <div class="">
                            <p class="fw-bold name mb-1">Mme MYBOTO</p>
                            <p class="message-preview mb-0">Administrateur 1</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="edit-user-btn"  style="background-color: #009281; border: none; border-radius: 10px; cursor: pointer; padding: 12px;"
                                data-name="Madame MYBOTO" 
                                data-email="madame.myboto@example.com" 
                                data-role="Administrateur">
                            <i class="fas fa-edit" style="color: white;"></i> 
                        </button>
                        <button class="delete-user-btn" style="background-color: #009281; border: none; border-radius: 10px; cursor: pointer; padding: 12px;"
                                data-name="Madame MYBOTO">
                            <i class="fas fa-trash-can" style="color: white;"></i> 
                        </button>
                    </div>
                </div>
            </div>

            <!-- utilisateur Item 2 -->
            <div class="w-100 top-doctor-item p-5 message-item user-item" 
                 data-name="M. Nguel" 
                 data-email="nguel@example.com" 
                 data-role="Administrateur">
                <div class="d-flex justify-content-between align-items-center gap-4 text-decoration-none">
                    <div class="d-flex justify-content-start align-items-center gap-3">
                        <div class="doctor-img flex-center">
                            <img src="{{ asset('assets/img/myboto.jpeg') }}" class="h-100" alt="Avatar" />
                        </div>
                        <div class="">
                            <p class="fw-bold name mb-1">M. Nguel</p>
                            <p class="message-preview mb-0">Administrateur 2</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="edit-user-btn"  style="background-color: #009281; border: none; border-radius: 10px; cursor: pointer; padding: 12px;"
                                data-name="M. Nguel" 
                                data-email="nguel@example.com" 
                                data-role="Administrateur">
                            <i class="fas fa-edit" style="color: white;"></i> 
                        </button>
                        <button class="delete-user-btn" style="background-color: #009281; border: none; border-radius: 10px; cursor: pointer; padding: 12px;"
                                data-name="M. Nguel">
                            <i class="fas fa-trash-can" style="color: white;"></i> 
                        </button>
                    </div>
                </div>
            </div>

            <!-- utilisateur Item 3 -->
            <div class="w-100 top-doctor-item p-5 message-item user-item" 
                 data-name="M. Rodrigue" 
                 data-email="rodrigue@example.com" 
                 data-role="Administrateur">
                <div class="d-flex justify-content-between align-items-center gap-4 text-decoration-none">
                    <div class="d-flex justify-content-start align-items-center gap-3">
                        <div class="doctor-img flex-center">
                            <img src="{{ asset('assets/img/myboto.jpeg') }}" class="h-100" alt="Avatar" />
                        </div>
                        <div class="">
                            <p class="fw-bold name mb-1">M. Rodrigue</p>
                            <p class="message-preview mb-0">Administrateur 3</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="edit-user-btn"  style="background-color: #009281; border: none; border-radius: 10px; cursor: pointer; padding: 12px;"
                                data-name="M. Rodrigue" 
                                data-email="rodrigue@example.com" 
                                data-role="Administrateur">
                            <i class="fas fa-edit" style="color: white;"></i> 
                        </button>
                        <button class="delete-user-btn" style="background-color: #009281; border: none; border-radius: 10px; cursor: pointer; padding: 12px;"
                                data-name="M. Rodrigue">
                            <i class="fas fa-trash-can" style="color: white;"></i> 
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    
@endsection

@section('scripts')
<script>
    // Gestion des modales utilisateurs
    document.addEventListener('DOMContentLoaded', function() {
        // Modales
        const userModal = document.getElementById('userCreationModal');
        const viewModal = document.getElementById('userViewModal');
        const editModal = document.getElementById('userEditModal');
        const deleteModal = document.getElementById('userDeleteModal');

        // Boutons
        const openUserModalBtn = document.getElementById('openUserModalBtn');
        const userCloseBtn = userModal.querySelector('.close-btn');
        const viewCloseBtn = viewModal.querySelector('.close-btn');
        const editCloseBtn = editModal.querySelector('.close-btn');
        const deleteCloseBtn = deleteModal.querySelector('.close-btn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

        // Elements de formulaire
        const userNameInput = document.getElementById('user-name');
        const userEmailInput = document.getElementById('user-email');
        const userRoleSelect = document.getElementById('user-role');

        // Elements de visualisation
        const viewUserName = document.getElementById('view-user-name');
        const viewUserEmail = document.getElementById('view-user-email');
        const viewUserRole = document.getElementById('view-user-role');

        // Elements d'édition
        const editUserName = document.getElementById('edit-user-name');
        const editUserEmail = document.getElementById('edit-user-email');
        const editUserRole = document.getElementById('edit-user-role');

        // Fonction pour ouvrir une modale
        function openModal(modal) {
            if (modal) modal.style.display = 'block';
        }

        // Fonction pour fermer une modale
        function closeModal(modal) {
            if (modal) modal.style.display = 'none';
        }

        // Gestion de la création d'utilisateur
        if (openUserModalBtn && userModal && userCloseBtn) {
            openUserModalBtn.addEventListener('click', function() {
                openModal(userModal);
                // Réinitialiser le formulaire
                userNameInput.value = '';
                userEmailInput.value = '';
                userRoleSelect.value = 'utilisateur';
            });

            userCloseBtn.addEventListener('click', function() {
                closeModal(userModal);
            });
        }

        // Gestion de la visualisation d'utilisateur
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function() {
                const userData = {
                    name: this.getAttribute('data-name'),
                    email: this.getAttribute('data-email'),
                    role: this.getAttribute('data-role')
                };

                // Remplir les informations
                viewUserName.textContent = userData.name;
                viewUserEmail.textContent = userData.email;
                viewUserRole.textContent = userData.role;

                openModal(viewModal);
            });
        });

        // Gestion de la fermeture des modales
        [viewCloseBtn, editCloseBtn, deleteCloseBtn].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', function() {
                    closeModal(this.closest('.modal'));
                });
            }
        });

        // Fermeture en cliquant en dehors
        [userModal, viewModal, editModal, deleteModal].forEach(modal => {
            if (modal) {
                modal.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeModal(this);
                    }
                });
            }
        });

        // Gestion de la suppression
        if (confirmDeleteBtn && cancelDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                // Ici, vous pouvez ajouter la logique de suppression
                console.log('Suppression confirmée');
                closeModal(deleteModal);
            });

            cancelDeleteBtn.addEventListener('click', function() {
                closeModal(deleteModal);
            });
        }

        // Gestion de l'édition
        document.querySelectorAll('.edit-user-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userData = {
                    name: this.getAttribute('data-name'),
                    email: this.getAttribute('data-email'),
                    role: this.getAttribute('data-role')
                };

                // Remplir le formulaire d'édition
                editUserName.value = userData.name;
                editUserEmail.value = userData.email;
                editUserRole.value = userData.role;

                openModal(editModal);
            });
        });

        // Gestion de la suppression
        document.querySelectorAll('.delete-user-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userName = this.getAttribute('data-name');
                // Afficher un message de confirmation avec le nom de l'utilisateur
                console.log(`Suppression de ${userName}`);
                openModal(deleteModal);
            });
        });
    });
</script>
@endsection