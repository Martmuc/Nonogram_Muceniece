<?php if (!isset($_SESSION['id_user'])) : ?>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Connexion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['LOGIN_ERROR_MESSAGE']; unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                    </div>
                <?php endif; ?>

                <form action="/nonogram/submit_login.php" method="POST">
                    <div class="mb-3">
                        <label for="login_username" class="form-label">Identifiant</label>
                        <input type="text" class="form-control" id="login_username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="login_password" class="form-label">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="login_password" name="password" required>
                            <span class="input-group-text bg-white" style="cursor: pointer;">
                                <img src="/nonogram/extras/oeil_ferme_petit.png" 
                                     class="toggle-password" 
                                     data-target="login_password" 
                                     alt="Afficher" 
                                     width="20">
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>