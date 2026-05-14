<?php

session_start();

require_once(__DIR__ . '/config/databaseconnect.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: views/index.php');
    exit;
}

$userId = $_SESSION['LOGGED_USER']['id_user'];

$username = trim($_POST['full_name'] ?? '');
$mail = trim($_POST['email'] ?? '');

$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_conf'] ?? '';

/**
 * Vérification des champs obligatoires
 */
if ($username === '' || $mail === '') {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Veuillez remplir correctement tous les champs.';
    header('Location: views/account.php');
    exit;
}

/**
 * Vérification format email
 */
if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Veuillez saisir une adresse email valide.';
    header('Location: views/account.php');
    exit;
}

/**
 * Vérifier si l'email est déjà utilisé
 */
$emailCheckStatement = $pdo->prepare(
    'SELECT id_user 
     FROM user_ 
     WHERE mail = :mail 
     AND id_user != :id_user'
);

$emailCheckStatement->execute([
    'mail' => $mail,
    'id_user' => $userId,
]);

$emailAlreadyUsed = $emailCheckStatement->fetch();

if ($emailAlreadyUsed) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Cette adresse email est déjà utilisée.';
    header('Location: views/account.php');
    exit;
}

/**
 * Si un mot de passe est renseigné
 */
if ($password !== '' || $passwordConfirm !== '') {

    if ($password !== $passwordConfirm) {
        $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Les mots de passe ne correspondent pas.';
        header('Location: views/account.php');
        exit;
    }

    if (strlen($password) < 12) {
        $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Le mot de passe doit contenir au moins 12 caractères.';
        header('Location: views/account.php');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $updateStatement = $pdo->prepare(
        'UPDATE user_
         SET username = :username,
             mail = :mail,
             password = :password
         WHERE id_user = :id_user'
    );

    $updateStatement->execute([
        'username' => $username,
        'mail' => $mail,
        'password' => $hashedPassword,
        'id_user' => $userId,
    ]);

} else {

    /**
     * Mise à jour sans changement de mot de passe
     */
    $updateStatement = $pdo->prepare(
        'UPDATE user_
         SET username = :username,
             mail = :mail
         WHERE id_user = :id_user'
    );

    $updateStatement->execute([
        'username' => $username,
        'mail' => $mail,
        'id_user' => $userId,
    ]);
}

/**
 * Mise à jour session
 */
$_SESSION['LOGGED_USER']['username'] = $username;
$_SESSION['LOGGED_USER']['mail'] = $mail;

$_SESSION['ACCOUNT_SUCCESS_MESSAGE'] = 'Vos informations ont bien été mises à jour.';

header('Location: views/account.php');
exit;