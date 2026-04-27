<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Picross'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .navbar-text {
            padding-right: 1rem;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        /* 🔥 Logo */
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

        /* 🔥 Alignement menu */
        .navbar-nav .nav-link {
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container d-flex align-items-center">

        <!-- 🔹 LOGO -->
        <a class="navbar-brand fw-bold" href="/nonogram/views/index.php">
            <i class="bi bi-grid-3x3"></i>
            <span>Picross</span>
        </a>

        <!-- 🔹 MENU MOBILE -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- 🔹 CONTENU NAVBAR -->
        <div class="collapse navbar-collapse align-items-center" id="navbarSupportedContent">

            <!-- 🔹 MENU GAUCHE -->
            <ul class="navbar-nav me-auto mb-0 align-items-center">
                <li class="nav-item">
                    <a class="nav-link active text-nowrap" href="/nonogram/views/index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-nowrap" href="/nonogram/views/shop.php">Boutique</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-nowrap" href="/nonogram/views/grilles.php">Grilles</a>
                </li>

                <?php if (isset($_SESSION['LOGGED_USER']) && $_SESSION['role'] === 'admin') : ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning text-nowrap" href="/nonogram/admin/dashboard.php">Admin</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- 🔹 MENU DROITE -->
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['LOGGED_USER'])) : ?>

                    <li class="nav-item">
                        <span class="navbar-text me-3 text-light text-nowrap">
                            👋 Bienvenue, 
                            <strong><?php echo htmlspecialchars($_SESSION['LOGGED_USER']['username']); ?></strong>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-nowrap" href="/nonogram/views/account.php">Mon compte</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white text-nowrap" href="/nonogram/logout.php">Déconnexion</a>
                    </li>

                <?php else : ?>

                    <li class="nav-item">
                        <a class="nav-link text-nowrap" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-person-fill"></i> Connexion
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white text-nowrap" href="#" data-bs-toggle="modal" data-bs-target="#signupModal">
                            S'inscrire
                        </a>
                    </li>

                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>