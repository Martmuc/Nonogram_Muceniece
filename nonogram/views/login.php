<?php
// Vérifie si l'utilisateur n'est PAS connecté (pas de session active)
if (!isset($_SESSION['id_user'])) :
?>

    <!-- Début de la modale de connexion (Bootstrap) -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">

        <!-- Conteneur de la modale centré à l'écran -->
        <div class="modal-dialog modal-dialog-centered">

            <!-- Contenu de la modale -->
            <div class="modal-content">

                <!-- En-tête de la modale -->
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Connexion</h5>

                    <!-- Bouton pour fermer la modale -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <!-- Corps de la modale -->
                <div class="modal-body">

                    <?php
                    // Vérifie s'il existe un message d'erreur de connexion en session
                    if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) :
                    ?>
                        <!-- Affiche le message d'erreur -->
                        <div class="alert alert-danger">
                            <?php
                            echo $_SESSION['LOGIN_ERROR_MESSAGE'];
                            // Supprime le message après affichage pour éviter qu'il persiste
                            unset($_SESSION['LOGIN_ERROR_MESSAGE']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire de connexion -->
                    <form action="/nonogram/submit_login.php" method="POST">

                        <!-- Champ identifiant -->
                        <div class="mb-3">
                            <label for="login_username" class="form-label">Identifiant</label>
                            <input type="text" class="form-control" id="login_username" name="username" required>
                        </div>

                        <!-- Champ mot de passe -->
                        <div class="mb-3">
                            <label for="login_password" class="form-label">Mot de passe</label>

                            <!-- Groupe input + icône -->
                            <div class="input-group">

                                <!-- Champ mot de passe masqué -->
                                <input type="password" class="form-control" id="login_password" name="password" required>

                                <!-- Icône "œil" pour afficher/masquer le mot de passe -->
                                <span class="input-group-text bg-white" style="cursor: pointer;">
                                    
                                <!-- Icône "œil" utilisée pour afficher ou masquer le mot de passe :
                                        - src : image affichée par défaut (œil fermé)
                                        - class "toggle-password" : utilisée en JavaScript pour détecter le clic
                                        - data-target : indique l’ID du champ mot de passe à contrôler
                                        - alt : description pour l’accessibilité-->
                                    <img src="/nonogram/extras/oeil_ferme_petit.png"
                                        class="toggle-password"
                                        data-target="login_password"
                                        alt="Afficher"
                                        width="20">
                                </span>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <button type="submit" class="btn btn-primary w-100">
                            Se connecter
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

<?php
// Fin de la condition (affiche la modale seulement si non connecté)
endif;
?>