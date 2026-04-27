<?php
session_start();

require_once __DIR__ . '/../config/databaseconnect.php';

if (!isset($_SESSION['id_user'], $_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../views/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: grids.php');
    exit;
}

$id_grille = (int) ($_POST['id_grille'] ?? 0);

if ($id_grille <= 0) {
    $_SESSION['GRID_ERROR_MESSAGE'] = 'Grille invalide.';
    header('Location: grids.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM grille WHERE id_grille = ?");
    $stmt->execute([$id_grille]);

    $_SESSION['GRID_SUCCESS_MESSAGE'] = 'Grille supprimée avec succès.';
    header('Location: grids.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['GRID_ERROR_MESSAGE'] = 'Erreur lors de la suppression de la grille.';
    header('Location: grids.php');
    exit;
}