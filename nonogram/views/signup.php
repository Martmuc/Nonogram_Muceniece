<?php
// Vérifie si l'utilisateur n'est PAS connecté
// Si aucun id_user n'est présent en session, on affiche la modale d'inscription
if (!isset($_SESSION['id_user'])) :
?>

    <!-- Début de la modale d'inscription (Bootstrap) -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">

        <!-- Conteneur centré de la modale -->
        <div class="modal-dialog modal-dialog-centered">

            <!-- Contenu principal de la modale -->
            <div class="modal-content">

                <!-- En-tête -->
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Inscription</h5>

                    <!-- Bouton de fermeture -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <!-- Corps de la modale -->
                <div class="modal-body">

                    <?php
                    // Vérifie s'il existe un message d'erreur d'inscription
                    if (isset($_SESSION['SIGNUP_ERROR_MESSAGE'])) :
                    ?>
                        <!-- Affichage du message d'erreur -->
                        <div class="alert alert-danger">
                            <?php
                            echo $_SESSION['SIGNUP_ERROR_MESSAGE'];
                            // Supprime le message après affichage
                            unset($_SESSION['SIGNUP_ERROR_MESSAGE']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire d'inscription -->
                    <form action="/nonogram/submit_signup.php" method="POST">

                        <!-- Champ identifiant -->
                        <div class="mb-3">
                            <label for="signup_username" class="form-label">Identifiant</label>
                            <input type="text" class="form-control" id="signup_username" name="username" required>
                        </div>

                        <!-- Champ email -->
                        <div class="mb-3">
                            <label for="signup_email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control" id="signup_email" name="email" placeholder="nom@exemple.com" required>
                        </div>

                        <!-- Champ mot de passe -->
                        <div class="mb-3">
                            <label for="signup_password" class="form-label">Mot de passe</label>

                            <!-- Groupe input + icône -->
                            <div class="input-group">

                                <!-- Champ mot de passe (minimum 12 caractères pour plus de sécurité) -->
                                <input type="password" class="form-control" id="signup_password" name="password" minlength="12" required>

                                <!-- Icône œil pour afficher/masquer le mot de passe -->
                                <span class="input-group-text bg-white" style="cursor: pointer;">
                                    <img src="/nonogram/extras/oeil_ferme_petit.png"
                                        class="toggle-password"
                                        data-target="signup_password"
                                        alt="Afficher"
                                        width="20">
                                </span>
                            </div>
                        </div>

                        <!-- Champ confirmation du mot de passe -->
                        <div class="mb-3">
                            <label for="signup_password_conf" class="form-label">Confirmer le mot de passe</label>

                            <!-- Groupe input + icône -->
                            <div class="input-group">

                                <!-- Champ de confirmation (doit correspondre au mot de passe) -->
                                <input type="password" class="form-control" id="signup_password_conf" name="password_conf" minlength="12" required>

                                <!-- Icône œil pour afficher/masquer -->
                                <span class="input-group-text bg-white" style="cursor: pointer;">
                                    <img src="/nonogram/extras/oeil_ferme_petit.png"
                                        class="toggle-password"
                                        data-target="signup_password_conf"
                                        alt="Afficher"
                                        width="20">
                                </span>
                            </div>
                        </div>
                        <!-- Mention RGPD -->
                        <p class="text-muted small mt-3">
                            En cliquant sur « S'inscrire », vous acceptez le traitement de vos données personnelles
                            conformément à la politique de confidentialité du site.
                        </p>

                        <!-- Bouton de soumission du formulaire -->
                        <button type="submit" class="btn btn-primary w-100">
                            S'inscrire
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

<?php
// Fin de la condition
endif;
?>