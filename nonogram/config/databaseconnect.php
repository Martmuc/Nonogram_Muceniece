<?php
// Configuration de la connexion à la base de données MySQL
$host = "mysql-muceniece.alwaysdata.net"; // Hôte de la base de données mysql-pps2025.alwaysdata.net
$dbname = "muceniece_nonogram"; // Nom de la base de données
$username = "muceniece"; // Nom d'utilisateur
$password = "CukurvateZiemassvetkiJaunpils!3145"; // Mot de passe

try {
    // Création de la connexion PDO pour MariaDB
    // Note: Le DSN (mysql:) reste le même car PDO utilise le même pilote pour MySQL et MariaDB
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   //permet d'afficher les erreurs
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  //définit le format des resultats
        PDO::ATTR_EMULATE_PREPARES => false,   // false permet de mieux protèger contre les injections SQL en obligeant PDO à utiliser de "vraies" requêtes préparées au lieu de les simuler
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]); 

} catch(PDOException $e) {
    // En cas d'erreur, affichage du message
    die("Erreur de connexion à la base de données MariaDB : " . $e->getMessage());
}

// try {
//     $pdo = new PDO(
//         sprintf('mysql:host=%s;dbname=%s;port=%s;charset=utf8', MYSQL_HOST, MYSQL_NAME, MYSQL_PORT),
//         MYSQL_USER,
//         MYSQL_PASSWORD
//     );
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (Exception $exception) {
//     die('Erreur : ' . $exception->getMessage());
// }
