<?php
session_start();
$title = "Règles du jeu - Picross";
require_once(__DIR__ . '/header.php');
?>

<main class="container my-5">

    <h1 class="fw-bold mb-4 text-center">Règles du Picross</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h3 class="fw-bold">🎯 Objectif du jeu</h3>
            <p>
                Le Picross est un jeu de réflexion où vous devez remplir une grille pour révéler un dessin caché.
                Chaque case peut être soit remplie, soit vide.
            </p>

            <hr>

            <h3 class="fw-bold">Les indices</h3>
            <p>
                Des nombres sont affichés :
            </p>
            <ul>
                <li><strong>À gauche</strong> → pour chaque ligne</li>
                <li><strong>En haut</strong> → pour chaque colonne</li>
            </ul>

            <p>
                Ces nombres indiquent la taille des groupes de cases remplies.
            </p>

            <p><strong>Exemple :</strong></p>
            <ul>
                <li><code>3</code> → 3 cases remplies à la suite</li>
                <li><code>1 2</code> → 1 case remplie, puis plus loin 2 cases remplies</li>
            </ul>

            <hr>

            <h3 class="fw-bold">Règles importantes</h3>
            <ul>
                <li>Les groupes de cases remplies sont séparés par au moins <strong>une case vide</strong></li>
                <li>L'ordre des nombres doit être respecté</li>
                <li>Une case vide peut être marquée avec une croix ❌</li>
            </ul>

            <hr>

            <h3 class="fw-bold">Comment jouer</h3>
            <ul>
                <li>Cliquez sur une case pour la remplir</li>
                <li>Cliquez à nouveau pour mettre une croix</li>
                <li>Cliquez encore pour vider la case</li>
            </ul>

            <hr>

            <h3 class="fw-bold">Fin de partie</h3>
            <p>
                La partie est terminée lorsque toutes les cases correctes sont remplies
                et correspondent exactement à la solution.
            </p>

            <hr>

            <h3 class="fw-bold">Conseils</h3>
            <ul>
                <li>Commencez par les lignes ou colonnes avec de grands nombres</li>
                <li>Utilisez les croix pour éliminer les cases impossibles</li>
                <li>Avancez progressivement, ligne par ligne</li>
            </ul>

        </div>
    </div>

</main>

<?php require_once __DIR__ . '/login.php'; ?>
<?php require_once __DIR__ . '/signup.php'; ?>
<?php require_once __DIR__ . '/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/modals.js"></script>