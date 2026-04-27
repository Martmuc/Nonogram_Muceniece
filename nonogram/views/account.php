<?php
session_start();
require_once(__DIR__ . '/../config/databaseconnect.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: index.php');
    exit;
}

$loggedUser = $_SESSION['LOGGED_USER'];
$userStatement = $pdo->prepare('SELECT * FROM user_ WHERE id_user = :id_user');
$userStatement->execute(['id_user' => $loggedUser['id_user']]);
$currentUser = $userStatement->fetch();

if (!$currentUser) {
    session_destroy();
    header('Location: index.php'); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon compte - Picross</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <?php require_once(__DIR__ . '/header.php'); ?>

    <main class="flex-grow-1">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-7">

                    <h1 class="mb-4 fw-bold">Mon compte</h1>

                    <?php if (isset($_SESSION['ACCOUNT_SUCCESS_MESSAGE'])) : ?>
                        <div class="alert alert-success shadow-sm">
                            <?= $_SESSION['ACCOUNT_SUCCESS_MESSAGE'];
                            unset($_SESSION['ACCOUNT_SUCCESS_MESSAGE']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['ACCOUNT_ERROR_MESSAGE'])) : ?>
                        <div class="alert alert-danger shadow-sm">
                            <?= $_SESSION['ACCOUNT_ERROR_MESSAGE'];
                            unset($_SESSION['ACCOUNT_ERROR_MESSAGE']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Modifier mes informations</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="/../submit_account.php" method="POST">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nom et prénom</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($currentUser['username']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($currentUser['mail']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="signup_password" class="form-label">Mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="signup_password" name="password" minlength="12" required>
                                        <span class="input-group-text bg-white" style="cursor: pointer;">
                                            <img src="../extras/oeil_ferme_petit.png"
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
                                            <img src="../extras/oeil_ferme_petit.png"
                                                class="toggle-password"
                                                data-target="signup_password_conf"
                                                alt="Afficher"
                                                width="20">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-text mb-4">Laisser vide pour conserver le mot de passe actuel.</div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4 border-danger shadow-sm">
                        <div class="card-header bg-white  fw-bold">
                            <h5 class="mb-0">Modifier mes informations</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3 text-muted">
                                Cette action est définitive et irréversible. Votre compte sera supprimé de nos serveurs.
                            </p>

                            <form action="../delete_account.php" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ? Cette action est irréversible.');">
                                <input type="hidden" name="id" value="<?php echo (int) $loggedUser['id_user']; ?>">

                                <button type="submit" class="btn btn-danger w-100">
                                    Supprimer définitivement mon compte
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php require_once(__DIR__ . '/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/modals.js"></script>
</body>

</html>