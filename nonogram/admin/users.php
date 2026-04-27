<?php
session_start();

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
    header('Location: ../views/index.php');
    exit();
}

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/recupdata.php';
?>

<main class="flex-grow-1">
    <div class="container my-5">
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="fw-bold text-dark">
                    <i class="bi bi-people-fill"></i> Gestion des Utilisateurs
                </h2>
            </div>
            <div class="col text-end">
                <span class="badge bg-secondary">Total : <?= count($users) ?></span>
            </div>
        </div>

        <?php if (!empty($_SESSION['ACCOUNT_SUCCESS_MESSAGE'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['ACCOUNT_SUCCESS_MESSAGE']) ?>
            </div>
            <?php unset($_SESSION['ACCOUNT_SUCCESS_MESSAGE']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['ACCOUNT_ERROR_MESSAGE'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['ACCOUNT_ERROR_MESSAGE']) ?>
            </div>
            <?php unset($_SESSION['ACCOUNT_ERROR_MESSAGE']); ?>
        <?php endif; ?>

        <?php if (empty($users)): ?>
            <div class="alert alert-info shadow-sm" role="alert">
                👥 Aucun utilisateur n'est inscrit pour le moment.
            </div>
        <?php else: ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Monnaie</th>
                                    <th>Dernière connexion</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr class="align-middle">
                                        <td class="ps-4 text-muted">
                                            #<?= htmlspecialchars($user['id_user']) ?>
                                        </td>

                                        <td class="fw-bold">
                                            <?= htmlspecialchars($user['username']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($user['mail']) ?>
                                        </td>

                                        <td>
                                            <?php if ($user['role'] === 'admin'): ?>
                                                <span class="badge bg-danger">Administrateur</span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark">Joueur</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <form action="update_monnaie.php" method="POST" class="d-flex align-items-center gap-2">
                                                <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['id_user']) ?>">

                                                <input
                                                    type="number"
                                                    name="monnaie"
                                                    class="form-control form-control-sm"
                                                    style="width: 90px;"
                                                    min="0"
                                                    value="<?= htmlspecialchars($user['monnaie']) ?>"
                                                    required>

                                                <button type="submit" class="btn btn-sm btn-outline-success px-2" title="Enregistrer">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            </form>
                                        </td>

                                        <td>
                                            <?= !empty($user['date_connexion'])
                                                ? htmlspecialchars($user['date_connexion'])
                                                : 'Jamais connecté' ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ((int)$user['id_user'] !== (int)$_SESSION['id_user']): ?>
                                                <form action="delete_user.php" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id_user']) ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted small">Compte actuel</span>
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
    </div>
</main>

<?php require_once __DIR__ . '/../views/footer.php'; ?>