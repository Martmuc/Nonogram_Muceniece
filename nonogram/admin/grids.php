<?php
session_start();

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
    header('Location: ../views/index.php');
    exit();
}

require_once __DIR__ . '/../config/databaseconnect.php';

$stmt = $pdo->query("
    SELECT id_grille, nom, longueur, largeur
    FROM grille
    ORDER BY id_grille ASC
");

$grilles = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../views/header.php';
?>

<main class="container my-5 flex-grow-1">

    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold">Liste des grilles</h2>
        </div>
    </div>

    <?php if (!empty($_SESSION['GRID_SUCCESS_MESSAGE'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['GRID_SUCCESS_MESSAGE']) ?>
        </div>
        <?php unset($_SESSION['GRID_SUCCESS_MESSAGE']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['GRID_ERROR_MESSAGE'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['GRID_ERROR_MESSAGE']) ?>
        </div>
        <?php unset($_SESSION['GRID_ERROR_MESSAGE']); ?>
    <?php endif; ?>

    <?php if (empty($grilles)): ?>
        <div class="alert alert-info">
            ℹ️ Aucune grille n'a été trouvée dans la base de données.
        </div>
    <?php else: ?>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Nom</th>
                                <th>Longueur</th>
                                <th>Largeur</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($grilles as $grille): ?>
                                <tr class="align-middle">
                                    <td class="ps-4 fw-bold">
                                        #<?= htmlspecialchars($grille['id_grille']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($grille['nom']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($grille['longueur']) ?> cases
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($grille['largeur']) ?> cases
                                    </td>

                                    <td class="text-center">
                                        <form action="delete_grid.php" method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer cette grille de la base de données ?')">
                                            <input type="hidden" name="id_grille" value="<?= htmlspecialchars($grille['id_grille']) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../views/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>