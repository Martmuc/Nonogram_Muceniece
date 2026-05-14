<?php
session_start();

require_once(__DIR__ . '/../config/databaseconnect.php');

$title = "Accueil - Picross";

$musics = [];

if (isset($_SESSION['id_user'])) {
    $stmt = $pdo->prepare("
        SELECT m.name
        FROM music m
        INNER JOIN user_music um ON m.id_music = um.id_music
        WHERE um.id_user = ?
        ORDER BY m.id_music ASC
    ");
    $stmt->execute([$_SESSION['id_user']]);
    $musics = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/picross.css">
    
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

<?php require_once(__DIR__ . '/header.php'); ?>

<div id="header"></div>

<main class="container my-5 flex-grow-1" id="main">

    <section class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Picross</h1>
        <p class="lead text-muted">
            Résolvez des grilles de logique, gagnez de la monnaie et débloquez des musiques.
        </p>
    </section>

    <section class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4 p-md-5">

                    <h2 class="h4 fw-bold text-center mb-4">
                        Nouvelle partie aléatoire
                    </h2>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="height" class="form-label fw-semibold">Hauteur</label>
                            <input id="height"
                                   class="form-control form-control-lg text-center"
                                   type="number"
                                   min="5"
                                   max="25"
                                   value="10">
                        </div>

                        <div class="col-md-6">
                            <label for="width" class="form-label fw-semibold">Largeur</label>
                            <input id="width"
                                   class="form-control form-control-lg text-center"
                                   type="number"
                                   min="5"
                                   max="25"
                                   value="10">
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <p class="fw-semibold mb-2">Difficulté</p>

                        <div id="flexDifficulty" class="d-flex justify-content-center gap-2 flex-wrap">
                            <button type="button" class="difficulty btn btn-outline-success active" id="difficulty0">
                                Facile
                            </button>

                            <button type="button" class="difficulty btn btn-outline-warning" id="difficulty1">
                                Normal
                            </button>

                            <button type="button" class="difficulty btn btn-outline-danger" id="difficulty2">
                                Difficile
                            </button>
                        </div>
                    </div>

                    <button type="button" class="play btn btn-primary btn-lg w-100 rounded-3" id="play-1">
                        Jouer maintenant
                    </button>

                    <a href="/nonogram/views/grilles.php"
                       class="btn btn-outline-secondary btn-lg w-100 rounded-3 mt-3">
                        Grilles prédéfinies
                    </a>

                    <div class="text-center mt-4 text-muted small">
                        Les dimensions doivent être comprises entre 5 et 25 cases.
                    </div>

                </div>
            </div>

        </div>
    </section>

    <div id="musicControls" class="d-none mt-3 text-center">
        <button type="button" id="startMusic" class="btn btn-sm btn-outline-success" title="Lancer la musique">
            ▶
        </button>

        <button type="button" id="stopMusic" class="btn btn-sm btn-outline-danger" title="Arrêter la musique">
            ■
        </button>
    </div>
    <!-- Message musique -->
<div id="musicMessage"></div>

    <audio id="music2"></audio>
</main>

<?php require_once(__DIR__ . '/login.php'); ?>
<?php require_once(__DIR__ . '/signup.php'); ?>
<?php require_once(__DIR__ . '/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/modals.js"></script>

<script>
    window.musicsFromDatabase = <?= json_encode($musics, JSON_UNESCAPED_UNICODE); ?>;
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const startBtn = document.getElementById('startMusic');
        const stopBtn = document.getElementById('stopMusic');
        const music2 = document.getElementById('music2');

        const musics = window.musicsFromDatabase || [];

        startBtn.addEventListener('click', function() {
            if (musics.length === 0) {
                alert("Aucune musique disponible.");
                return;
            }

            let music = musics[Math.floor(Math.random() * musics.length)];

            music2.src = "/nonogram/extras/" + music;
            music2.loop = true;

            music2.play().catch(function(err) {
                console.error(err);
                alert("Erreur lecture musique");
            });
        });

        stopBtn.addEventListener('click', function() {
            music2.pause();
            music2.currentTime = 0;
        });
    });
</script>

<script>
    window.selectedGridFromUrl = <?= isset($_GET['grid']) ? (int) $_GET['grid'] : 'null' ?>;
</script>

<script src="../js/gridList.js"></script>
<script src="../js/picrossPlayGraphic.js" defer></script>

<?php if (isset($_SESSION['SHOW_LOGIN_MODAL'])) : ?>
    <script>
        showModalIfExist('loginModal');
    </script>
    <?php unset($_SESSION['SHOW_LOGIN_MODAL']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['SHOW_SIGNUP_MODAL'])) : ?>
    <script>
        showModalIfExist('signupModal');
    </script>
    <?php unset($_SESSION['SHOW_SIGNUP_MODAL']); ?>
<?php endif; ?>

</body>
</html>