<?php
session_start();

require_once __DIR__ . '/../config/databaseconnect.php';

if (!isset($_SESSION['id_user'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../views/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

$currentUserId = (int) $_SESSION['id_user'];
$userIdToDelete = (int) ($_POST['id'] ?? 0);

if ($userIdToDelete <= 0) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Utilisateur invalide.';
    header('Location: users.php');
    exit;
}

if ($userIdToDelete === $currentUserId) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Vous ne pouvez pas supprimer votre propre compte depuis l’administration.';
    header('Location: users.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM user_ WHERE id_user = ?");
    $stmt->execute([$userIdToDelete]);

    $_SESSION['ACCOUNT_SUCCESS_MESSAGE'] = 'Utilisateur supprimé avec succès.';
    header('Location: users.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Erreur lors de la suppression de l’utilisateur.';
    header('Location: users.php');
    exit;
}