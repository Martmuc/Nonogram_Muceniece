<?php
session_start();

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
    header('Location: ../views/index.php');
    exit();
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/recupdata.php';
?>

<main class="container my-5">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold">
                <i class="bi bi-music-note-beamed"></i> Bibliothèque Musicale
            </h2>
        </div>

        <div class="col text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmImportModal">
                <i class="bi bi-plus-lg"></i> Ajouter un titre
            </button>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Succès !</strong> <?= intval($_GET['success']) ?> nouveau(x) titre(s) ont été importés.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['MUSIC_SUCCESS_MESSAGE'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['MUSIC_SUCCESS_MESSAGE']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
        <?php unset($_SESSION['MUSIC_SUCCESS_MESSAGE']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['MUSIC_ERROR_MESSAGE'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['MUSIC_ERROR_MESSAGE']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
        <?php unset($_SESSION['MUSIC_ERROR_MESSAGE']); ?>
    <?php endif; ?>

    <?php if (empty($music)): ?>
        <div class="alert alert-info shadow-sm" role="alert">
            🎵 Aucune musique n'est disponible pour le moment.
        </div>
    <?php else: ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Titre de la musique</th>
                                <th>Prix (Pièces)</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($music as $m): ?>
                                <tr class="align-middle">
                                    <td class="ps-4 text-muted">
                                        #<?= htmlspecialchars($m['id_music']) ?>
                                    </td>

                                    <td class="fw-bold">
                                        <?= htmlspecialchars($m['name']) ?>
                                    </td>

                                    <td>
                                        <form action="music/update_music_price.php" method="POST" class="d-flex align-items-center gap-2">
                                            <input type="hidden" name="id_music" value="<?= htmlspecialchars($m['id_music']) ?>">

                                            <input
                                                type="number"
                                                name="prix"
                                                class="form-control form-control-sm"
                                                style="width: 90px;"
                                                min="0"
                                                value="<?= htmlspecialchars($m['prix']) ?>"
                                                required>

                                            <button type="submit" class="btn btn-sm btn-outline-success px-2" title="Enregistrer">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <td class="text-center">
                                        <form action="music/delete_music.php" method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer ce titre ?')">
                                            <input type="hidden" name="id_music" value="<?= htmlspecialchars($m['id_music']) ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
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

    <div class="modal fade" id="confirmImportModal" tabindex="-1" aria-labelledby="confirmImportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="music/add_music.php" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmImportModalLabel">Ajouter un titre MP3</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="musicFile" class="form-label">Choisir le fichier MP3</label>
                        <input type="file" name="musicFile" id="musicFile" class="form-control" accept=".mp3" required>
                    </div>

                    <div class="mb-3">
                        <label for="prix" class="form-label">Prix (Pièces)</label>
                        <input type="number" name="prix" id="prix" class="form-control" value="10" min="0" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" name="submit" class="btn btn-primary">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php require_once __DIR__ . '/../views/footer.php'; ?>