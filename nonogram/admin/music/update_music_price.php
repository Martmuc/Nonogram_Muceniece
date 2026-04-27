<?php
session_start();

require_once __DIR__ . '../../../config/databaseconnect.php';

if (!isset($_SESSION['id_user'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../views/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../music.php');
    exit;
}

$id_music = (int) ($_POST['id_music'] ?? 0);
$prix = (int) ($_POST['prix'] ?? 0);

if ($id_music <= 0 || $prix < 0) {
    $_SESSION['MUSIC_ERROR_MESSAGE'] = 'Données invalides.';
    header('Location: ../music.php');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE music SET prix = ? WHERE id_music = ?");
    $stmt->execute([$prix, $id_music]);

    $_SESSION['MUSIC_SUCCESS_MESSAGE'] = 'Prix mis à jour.';
    header('Location: ../music.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['MUSIC_ERROR_MESSAGE'] = 'Erreur lors de la mise à jour du prix.';
    header('Location: ../music.php');
    exit;
}