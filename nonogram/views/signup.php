<?php if (!isset($_SESSION['id_user'])) : ?>
<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signupModalLabel">Inscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($_SESSION['SIGNUP_ERROR_MESSAGE'])) : ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['SIGNUP_ERROR_MESSAGE']; unset($_SESSION['SIGNUP_ERROR_MESSAGE']); ?>
                    </div>
                <?php endif; ?>

                <form action="/nonogram/submit_signup.php" method="POST">
                    <div class="mb-3">
                        <label for="signup_username" class="form-label">Identifiant</label>
                        <input type="text" class="form-control" id="signup_username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="signup_email" class="form-label">Adresse email</label>
                        <input type="email" class="form-control" id="signup_email" name="email" placeholder="nom@exemple.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="signup_password" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="signup_password" name="password" minlength="12" required>
                            <span class="input-group-text bg-white" style="cursor: pointer;">
                                <img src="/nonogram/extras/oeil_ferme_petit.png" 
                                     class="toggle-password" 
                                     data-target="signup_password" 
                                     alt="Afficher" 
                                     width="20">
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="signup_password_conf" class="form-label">Confirmer le mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="signup_password_conf" name="password_conf" minlength="12" required>
                            <span class="input-group-text bg-white" style="cursor: pointer;">
                                <img src="/nonogram/extras/oeil_ferme_petit.png" 
                                     class="toggle-password" 
                                     data-target="signup_password_conf" 
                                     alt="Afficher" 
                                     width="20">
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>