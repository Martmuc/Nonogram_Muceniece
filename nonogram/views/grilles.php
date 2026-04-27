<?php
session_start();

require_once __DIR__ . '/../config/databaseconnect.php';

$title = "Grilles - Picross";
require_once __DIR__ . '/header.php';

$stmt = $pdo->query("
    SELECT id_grille, nom, longueur, largeur
    FROM grille
    ORDER BY id_grille ASC
");

$grilles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container my-5">
    <h2 class="fw-bold mb-4">Grilles prédéfinies</h2>

    <?php if (empty($grilles)): ?>
        <div class="alert alert-info">
            Aucune grille prédéfinie disponible.
        </div>
    <?php else: ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nom</th>
                            <th>Taille</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($grilles as $grille): ?>
                            <tr class="align-middle">
                                <td class="ps-4 fw-bold">
                                    <?= htmlspecialchars($grille['nom']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($grille['largeur']) ?>
                                    x
                                    <?= htmlspecialchars($grille['longueur']) ?>
                                </td>

                                <td class="text-center">
                                    <a href="/nonogram/views/index.php?grid=<?= (int)$grille['id_grille'] ?>"
                                       class="btn btn-primary btn-sm">
                                        Jouer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>