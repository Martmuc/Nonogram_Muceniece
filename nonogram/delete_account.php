<?php
session_start();

require_once __DIR__ . '/config/databaseconnect.php';

if (!isset($_SESSION['LOGGED_USER']['id_user'])) {
    header('Location: views/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: views/account.php');
    exit;
}

$currentUserId = (int) $_SESSION['LOGGED_USER']['id_user'];

try {
    $stmt = $pdo->prepare("DELETE FROM user_ WHERE id_user = ?");
    $stmt->execute([$currentUserId]);

    session_unset();
    session_destroy();

    header('Location: views/index.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Erreur lors de la suppression du compte.';
    header('Location: views/account.php');
    exit;
}