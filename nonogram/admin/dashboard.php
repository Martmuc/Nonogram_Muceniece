<?php
session_start();

// Sécurité Admin
if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Picross</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once(__DIR__ . '/../views/header.php'); ?>

    <main class="container my-5">
        <div class="row mb-5">
            <div class="col-12 border-bottom pb-3">
                <h2 class="fw-bold">Tableau de Bord Admin</h2>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center py-4">
                        <div class="display-5 text-primary mb-3">👥</div>
                        <h5 class="card-title">Utilisateurs</h5>
                        <p class="card-text text-muted small">Gérer les comptes joueurs et administrateurs.</p>
                        <a href="/nonogram/admin/users.php" class="btn btn-outline-primary stretched-link">Gérer</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center py-4">
                        <div class="display-5 text-success mb-3">🎵</div>
                        <h5 class="card-title">Musique</h5>
                        <p class="card-text text-muted small">Ajouter ou modifier l'ambiance sonore du jeu.</p>
                        <a href="/nonogram/admin/music.php" class="btn btn-outline-success stretched-link">Gérer</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center py-4">
                        <div class="display-5 text-warning mb-3">🧩</div>
                        <h5 class="card-title">Grilles</h5>
                        <p class="card-text text-muted small">Créer et éditer les niveaux de Picross.</p>
                        <a href="/nonogram/admin/grids.php" class="btn btn-outline-warning stretched-link">Gérer</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once(__DIR__ . '/../views/footer.php'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>