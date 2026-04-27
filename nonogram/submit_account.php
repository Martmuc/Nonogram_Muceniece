<?php

session_start();


require_once(__DIR__ . '/config/databaseconnect.php');


if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: views/index.php');
    exit;
}

$userId = $_SESSION['LOGGED_USER']['id_user'];

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$age = (int) ($_POST['age'] ?? 0);
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

if ($fullName === '' || $email === '' || $age < 15) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Veuillez remplir correctement tous les champs.';
    header('Location: views/account.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Veuillez saisir une adresse email valide.';
    header('Location: views/account.php');
    exit;
}

/**
 * Vérifier si l'email est déjà utilisé par un autre compte
 */
$emailCheckStatement = $pdo->prepare(
    'SELECT id_user FROM users WHERE email = :email AND id_user != :id_user'
);
$emailCheckStatement->execute([
    'email' => $email,
    'id_user' => $userId,
]);

$emailAlreadyUsed = $emailCheckStatement->fetch();

if ($emailAlreadyUsed) {
    $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Cette adresse email est déjà utilisée.';
    header('Location: views/account.php');
    exit;
}

/**
 * Si un nouveau mot de passe est saisi, on le valide
 */
if ($password !== '' || $passwordConfirm !== '') {
    if ($password !== $passwordConfirm) {
        $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Les mots de passe ne correspondent pas.';
        header('Location: views/account.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['ACCOUNT_ERROR_MESSAGE'] = 'Le mot de passe doit contenir au moins 6 caractères.';
        header('Location: views/account.php');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $updateStatement = $pdo->prepare(
        'UPDATE users
         SET full_name = :full_name, email = :email, age = :age, password = :password
         WHERE id_user = :id_user'
    );

    $updateStatement->execute([
        'full_name' => $fullName,
        'email' => $email,
        'age' => $age,
        'password' => $hashedPassword,
        'id_user' => $userId,
    ]);
} else {
    $updateStatement = $pdo->prepare(
        'UPDATE users
         SET full_name = :full_name, email = :email, age = :age
         WHERE id_user = :id_user'
    );

    $updateStatement->execute([
        'full_name' => $fullName,
        'email' => $email,
        'age' => $age,
        'id_user' => $userId,
    ]);
}

/**
 * Mettre à jour la session
 */
$_SESSION['LOGGED_USER']['email'] = $email;
$_SESSION['LOGGED_USER']['full_name'] = $fullName;

$_SESSION['ACCOUNT_SUCCESS_MESSAGE'] = 'Vos informations ont bien été mises à jour.';
header('Location: views/account.php');
exit;