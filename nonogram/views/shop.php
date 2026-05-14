<?php
session_start();

require_once __DIR__ . '/../config/databaseconnect.php';
require_once __DIR__ . '/header.php';

$isConnected = isset($_SESSION['id_user']);
$idUser = $isConnected ? (int) $_SESSION['id_user'] : null;

if ($isConnected) {
    $stmt = $pdo->prepare("
        SELECT 
            m.id_music,
            m.name,
            m.prix,
            CASE 
                WHEN um.id_music IS NOT NULL THEN 1 
                ELSE 0 
            END AS achetee
        FROM music m
        LEFT JOIN user_music um 
            ON m.id_music = um.id_music 
            AND um.id_user = ?
        ORDER BY m.id_music ASC
    ");
    $stmt->execute([$idUser]);
} else {
    $stmt = $pdo->query("
        SELECT 
            id_music,
            name,
            prix,
            0 AS achetee
        FROM music
        ORDER BY id_music ASC
    ");
}

$music = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container my-5">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-shop"></i> Boutique musicale
            </h2>
        </div>

        <div class="col text-end">
            <?php if ($isConnected): ?>
                <span class="badge bg-warning text-dark">
                    Monnaie : <?= htmlspecialchars($_SESSION['monnaie'] ?? 0) ?> 🪙
                </span>
            <?php else: ?>
                <span class="badge bg-secondary">
                    Connectez-vous pour acheter
                </span>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!$isConnected): ?>
        <div class="alert alert-info">
            Vous pouvez consulter la boutique, mais vous devez être connecté pour acheter une musique.
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['SHOP_SUCCESS_MESSAGE'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['SHOP_SUCCESS_MESSAGE']) ?>
        </div>
        <?php unset($_SESSION['SHOP_SUCCESS_MESSAGE']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['SHOP_ERROR_MESSAGE'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['SHOP_ERROR_MESSAGE']) ?>
        </div>
        <?php unset($_SESSION['SHOP_ERROR_MESSAGE']); ?>
    <?php endif; ?>

    <?php if (empty($music)): ?>
        <div class="alert alert-info">
            Aucune musique disponible pour le moment.
        </div>
    <?php else: ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Titre de la musique</th>
                                <th>Prix</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($music as $m): ?>
                                <tr class="align-middle">
                                    <td class="fw-bold">
                                        <?= htmlspecialchars($m['name']) ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <?= htmlspecialchars($m['prix']) ?> 🪙
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <?php if (!$isConnected): ?>
                                            <button class="btn btn-secondary btn-sm px-3" disabled title="Connectez-vous pour acheter">
                                                🔒
                                            </button>

                                        <?php elseif ((int)$m['achetee'] === 1): ?>
                                            <button class="btn btn-success btn-sm px-3" disabled title="Déjà achetée">
                                                ✔
                                            </button>

                                        <?php else: ?>
                                            <form action="../buy_music.php" method="POST"
                                                onsubmit="return confirm('Acheter cette musique ?')"
                                                class="d-inline">
                                                <input type="hidden" name="id_music" value="<?= htmlspecialchars($m['id_music']) ?>">
                                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                                    Acheter
                                                </button>
                                            </form>
                                        <?php endif; ?>
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

<?php require_once __DIR__ . '/login.php'; ?>
<?php require_once __DIR__ . '/signup.php'; ?>
<?php require_once __DIR__ . '/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/modals.js"></script>