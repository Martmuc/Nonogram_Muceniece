<?php
session_start();

$title = "Politique de confidentialité - Picross";
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

<main class="container my-5 flex-grow-1">

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">

            <h1 class="mb-4 text-primary">Politique de confidentialité</h1>

            <p>
                Cette politique de confidentialité explique quelles données sont collectées
                sur le site Picross et comment elles sont utilisées.
            </p>

            <h2 class="h4 mt-4">Données collectées</h2>

            <p>
                Lors de l’inscription, les informations suivantes peuvent être collectées :
            </p>

            <ul>
                <li>Identifiant utilisateur</li>
                <li>Adresse e-mail</li>
            </ul>

            <h2 class="h4 mt-4">Utilisation des données</h2>

            <p>
                Ces données sont utilisées uniquement pour :
            </p>

            <ul>
                <li>Permettre la création et la gestion des comptes utilisateurs</li>
                <li>Assurer le bon fonctionnement du site</li>
                <li>Garantir la sécurité des comptes</li>
            </ul>

            <h2 class="h4 mt-4">Partage des données</h2>

            <p>
                Aucune donnée personnelle n’est vendue, échangée ou transmise à des tiers.
            </p>

            <h2 class="h4 mt-4">Conservation des données</h2>

            <p>
                Les données sont conservées tant que le compte utilisateur reste actif.
            </p>

            <h2 class="h4 mt-4">Vos droits</h2>

            <p>
                Conformément au RGPD, vous pouvez demander :
            </p>

            <ul>
                <li>L’accès à vos données</li>
                <li>La modification de vos données</li>
                <li>La suppression de votre compte et de vos données</li>
            </ul>

            <h2 class="h4 mt-4">Contact</h2>

            <p>
                Pour toute demande concernant vos données personnelles,
                vous pouvez contacter l’administrateur du site à l’adresse suivante :
            </p>

            <p class="fw-bold">
                muceniecem@gmail.com
            </p>

        </div>
    </div>

</main>

<?php require_once __DIR__ . '/login.php'; ?>
<?php require_once __DIR__ . '/signup.php'; ?>
<?php require_once __DIR__ . '/footer.php'; ?>


<script src="../js/modals.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>