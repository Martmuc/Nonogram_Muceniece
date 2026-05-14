<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Picross') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            gap: 8px;
            margin-bottom: 0;
        }

        .navbar-brand i {
            font-size: 1.6rem;
        }

        .navbar-nav .nav-link,
        .navbar-text {
            display: flex;
            align-items: center;
            white-space: nowrap;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold" href="/nonogram/views/index.php">
            <i class="bi bi-grid-3x3"></i>
            <span>Picross</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse align-items-center" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-0 align-items-center">
                <li class="nav-item">
                    <a class="nav-link active" href="/nonogram/views/index.php">Accueil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="/nonogram/views/shop.php">Boutique</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="/nonogram/views/grilles.php">Grilles</a>
                </li>

                <?php if (isset($_SESSION['LOGGED_USER'], $_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="/nonogram/admin/dashboard.php">Admin</a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-lg-2">
                <?php if (isset($_SESSION['LOGGED_USER'])) : ?>

                    <li class="nav-item">
                        <span class="navbar-text text-light">
                            👋 Bienvenue,
                            <strong class="ms-1">
                                <?= htmlspecialchars($_SESSION['LOGGED_USER']['username']) ?>
                            </strong>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/nonogram/views/account.php">Mon compte</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="/nonogram/logout.php">Déconnexion</a>
                    </li>

                <?php else : ?>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-person-fill me-1"></i> Connexion
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#signupModal">
                            S'inscrire
                        </a>
                    </li>

                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>