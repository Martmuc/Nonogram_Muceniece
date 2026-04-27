<?php
session_start();

require_once __DIR__ . '/../config/databaseconnect.php';

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
    header('Location: ../views/index.php');
    exit();
}

$id_user = isset($_POST['id_user']) ? (int) $_POST['id_user'] : 0;
$monnaie = isset($_POST['monnaie']) ? (int) $_POST['monnaie'] : 0;

if ($id_user <= 0 || $monnaie < 0) {
    header('Location: dashboard.php');
    exit();
}

$stmt = $pdo->prepare("UPDATE user_ SET monnaie = ? WHERE id_user = ?");
$stmt->execute([$monnaie, $id_user]);

header('Location: users.php');
exit();