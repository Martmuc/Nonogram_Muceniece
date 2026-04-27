<?php
session_start();
require_once(__DIR__ . '/config/databaseconnect.php'); // Doit contenir $pdo

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_conf'] ?? '';
$role = 'player'; 

// 1. Validations de base
if ($username === '' || $email === '' || $password === '' || $passwordConfirm === '') {
    $_SESSION['SIGNUP_ERROR_MESSAGE'] = 'Tous les champs sont obligatoires.';
    $_SESSION['SHOW_SIGNUP_MODAL'] = true;
    header('Location: views/index.php');
    exit;
}

if ($password !== $passwordConfirm) {
    $_SESSION['SIGNUP_ERROR_MESSAGE'] = 'Les mots de passe ne correspondent pas.';
    $_SESSION['SHOW_SIGNUP_MODAL'] = true;
    header('Location: views/index.php');
    exit;
}

try {
    // 2. Vérifier si l'identifiant OU l'email existe déjà
    $checkUser = $pdo->prepare("SELECT id_user FROM user_ WHERE username = ? OR mail = ?");
    $checkUser->execute([$username, $email]);
    
    if ($checkUser->fetch()) {
        $_SESSION['SIGNUP_ERROR_MESSAGE'] = 'Cet identifiant ou cet email est déjà utilisé.';
        $_SESSION['SHOW_SIGNUP_MODAL'] = true;
        header('Location: views/index.php');
        exit;
    }

    // 3. Insertion sécurisée
    $pdo->beginTransaction();
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO user_ (username, mail, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $passwordHash, $role]);
    $id_user = $pdo->lastInsertId();
    $pdo->commit();

    // 4. Connexion automatique
    $_SESSION['LOGGED_USER'] = [
        'id_user' => $id_user,
        'username' => $username,
        'role' => $role,
    ];

    header("Location: views/index.php");
    exit();

} catch (Exception $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    $_SESSION['SIGNUP_ERROR_MESSAGE'] = 'Erreur technique lors de l\'inscription.';
    $_SESSION['SHOW_SIGNUP_MODAL'] = true;
    header('Location: views/index.php');
    exit;
}