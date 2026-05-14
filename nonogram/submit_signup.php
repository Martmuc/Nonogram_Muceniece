<?php
// Démarre la session pour stocker les informations utilisateur et messages
session_start();

// Inclut la connexion à la base de données (doit contenir $pdo)
require_once(__DIR__ . '/config/databaseconnect.php');

// Récupération et nettoyage des données du formulaire
$username = trim($_POST['username'] ?? ''); // Supprime les espaces inutiles
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_conf'] ?? '';

// Définit le rôle par défaut (utilisateur classique)
$role = 'player'; 

// 1. Vérification que tous les champs sont remplis
if ($username === '' || $email === '' || $password === '' || $passwordConfirm === '') {
    $_SESSION['SIGNUP_ERROR_MESSAGE'] = 'Tous les champs sont obligatoires.';
    $_SESSION['SHOW_SIGNUP_MODAL'] = true; // Permet de rouvrir la modale
    header('Location: views/index.php');
    exit;
}

// Vérifie que les deux mots de passe correspondent
if ($password !== $passwordConfirm) {
    $_SESSION['SIGNUP_ERROR_MESSAGE'] = 'Les mots de passe ne correspondent pas.';
    $_SESSION['SHOW_SIGNUP_MODAL'] = true;
    header('Location: views/index.php');
    exit;
}

try {
    // 2. Vérifie si le username OU l'email existe déjà dans la base
    $checkUser = $pdo->prepare("SELECT id_user FROM user_ WHERE username = ? OR mail = ?");
    $checkUser->execute([$username, $email]);
    
    // Si un utilisateur est trouvé → doublon
    if ($checkUser->fetch()) {
        $_SESSION['SIGNUP_ERROR_MESSAGE'] = 'Cet identifiant ou cet email est déjà utilisé.';
        $_SESSION['SHOW_SIGNUP_MODAL'] = true;
        header('Location: views/index.php');
        exit;
    }

    // 3. Insertion sécurisée dans la base de données
    $pdo->beginTransaction(); // Démarre une transaction

    // Hash du mot de passe pour ne jamais stocker en clair
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Prépare la requête d'insertion
    $stmt = $pdo->prepare("
        INSERT INTO user_ (username, mail, password, role) 
        VALUES (?, ?, ?, ?)
    ");

    // Exécute la requête avec les données
    $stmt->execute([$username, $email, $passwordHash, $role]);

    // Récupère l'ID du nouvel utilisateur créé
    $id_user = $pdo->lastInsertId();

    // Valide la transaction
    $pdo->commit();

    // 4. Connexion automatique après inscription
    $_SESSION['LOGGED_USER'] = [
        'id_user' => $id_user,
        'username' => $username,
        'role' => $role,
    ];

    // Redirige vers la page principale
    header("Location: views/index.php");
    exit();

} catch (Exception $e) {

    // Si une erreur survient, annule la transaction en cours
    if ($pdo->inTransaction()) { 
        $pdo->rollBack(); 
    }

    // Message d'erreur générique
    $_SESSION['SIGNUP_ERROR_MESSAGE'] = 'Erreur technique lors de l\'inscription.';
    $_SESSION['SHOW_SIGNUP_MODAL'] = true;

    // Redirection vers l'accueil
    header('Location: views/index.php');
    exit;
}