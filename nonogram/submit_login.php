<?php
// Démarre la session pour pouvoir stocker des données utilisateur
session_start();

// Inclut le fichier de connexion à la base de données
require_once(__DIR__ . '/config/databaseconnect.php');

// Vérifie que la requête est bien envoyée en POST (sécurité)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: views/index.php'); // Redirige si accès direct
    exit;
}

// Récupère et nettoie les données du formulaire
$username = trim($_POST['username'] ?? ''); // Supprime les espaces inutiles
$password = $_POST['password'] ?? '';

// Vérifie que les champs ne sont pas vides
if ($username === '' || $password === '') {
    $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Veuillez remplir tous les champs.'; // Message d'erreur
    $_SESSION['SHOW_LOGIN_MODAL'] = true; // Permet de rouvrir la modale
    header('Location: views/index.php');
    exit;
}

try {
    // Prépare une requête pour récupérer l'utilisateur correspondant
    $stmt = $pdo->prepare("
        SELECT id_user, username, password, mail, monnaie, role, date_connexion
        FROM user_
        WHERE username = ?
    ");

    // Exécute la requête avec le username fourni
    $stmt->execute([$username]);

    // Récupère les données de l'utilisateur
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si l'utilisateur existe ET si le mot de passe est correct
    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Identifiants ou mot de passe incorrects.';
        $_SESSION['SHOW_LOGIN_MODAL'] = true;
        header('Location: views/index.php');
        exit;
    }

    // Stocke l'ancienne date de connexion avant mise à jour
    $_SESSION['dernier_login'] = $user['date_connexion'];

    // Génère la nouvelle date de connexion
    $dateConnexion = date('Y-m-d H:i:s');

    // Met à jour la date de connexion dans la base de données
    $updateStmt = $pdo->prepare("
        UPDATE user_
        SET date_connexion = ?
        WHERE id_user = ?
    ");
    $updateStmt->execute([$dateConnexion, $user['id_user']]);

    // Stocke les informations utilisateur en session (accès global)
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['mail'] = $user['mail'];
    $_SESSION['monnaie'] = $user['monnaie'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['date_connexion'] = $dateConnexion;

    // Tableau regroupant toutes les infos utilisateur
    $_SESSION['LOGGED_USER'] = [
        'id_user' => $user['id_user'],
        'username' => $user['username'],
        'mail' => $user['mail'],
        'monnaie' => $user['monnaie'],
        'role' => $user['role'],
        'date_connexion' => $dateConnexion
    ];

    // Redirection selon le rôle de l'utilisateur
    if ($user['role'] === 'admin') {
        header('Location: admin/dashboard.php'); // Accès admin
    } else {
        header('Location: views/index.php'); // Utilisateur classique
    }
    exit;

} catch (Exception $e) {
    // Gestion des erreurs techniques (ex : problème base de données)
    $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Une erreur technique est survenue.';
    $_SESSION['SHOW_LOGIN_MODAL'] = true;
    header('Location: views/index.php');
    exit;
}