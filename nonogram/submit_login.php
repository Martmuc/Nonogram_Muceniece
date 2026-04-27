<?php
session_start();

require_once(__DIR__ . '/config/databaseconnect.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: views/index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Veuillez remplir tous les champs.';
    $_SESSION['SHOW_LOGIN_MODAL'] = true;
    header('Location: views/index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT id_user, username, password, mail, monnaie, role, date_connexion
        FROM user_
        WHERE username = ?
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Identifiants ou mot de passe incorrects.';
        $_SESSION['SHOW_LOGIN_MODAL'] = true;
        header('Location: views/index.php');
        exit;
    }

    // Ancienne date de connexion, avant mise à jour
    $_SESSION['dernier_login'] = $user['date_connexion'];

    // Nouvelle date de connexion
    $dateConnexion = date('Y-m-d H:i:s');

    $updateStmt = $pdo->prepare("
        UPDATE user_
        SET date_connexion = ?
        WHERE id_user = ?
    ");
    $updateStmt->execute([$dateConnexion, $user['id_user']]);

    // Données utilisateur en session
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['mail'] = $user['mail'];
    $_SESSION['monnaie'] = $user['monnaie'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['date_connexion'] = $dateConnexion;

    $_SESSION['LOGGED_USER'] = [
        'id_user' => $user['id_user'],
        'username' => $user['username'],
        'mail' => $user['mail'],
        'monnaie' => $user['monnaie'],
        'role' => $user['role'],
        'date_connexion' => $dateConnexion
    ];

    if ($user['role'] === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: views/index.php');
    }
    exit;

} catch (Exception $e) {
    $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Une erreur technique est survenue.';
    $_SESSION['SHOW_LOGIN_MODAL'] = true;
    header('Location: views/index.php');
    exit;
}