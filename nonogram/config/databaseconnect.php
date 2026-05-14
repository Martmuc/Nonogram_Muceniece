<?php


// Définition des paramètres de connexion à la base de données
$host = "localhost"; 
// Adresse du serveur de base de données (ici en local)
// Exemple : mysql-pps2025.alwaysdata.net

$dbname = "nonogram"; 
// Nom de la base de données à laquelle on veut se connecter

$username = "test"; 
// Nom d'utilisateur utilisé pour se connecter à la base

$password = "test"; 
// Mot de passe associé à cet utilisateur


try {
    // Création d'une instance PDO (PHP Data Objects)
    // Cela permet de se connecter à la base de données de manière sécurisée

    // DSN (Data Source Name) :
    // - mysql : type de base (compatible aussi avec MariaDB)
    // - host : serveur
    // - dbname : nom de la base
    // - charset : encodage des caractères
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            // Active les exceptions en cas d'erreur SQL
            // Permet de mieux gérer les erreurs avec try/catch
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Définit le mode de récupération des résultats
            // FETCH_ASSOC = tableau associatif (colonne => valeur)
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Désactive l'émulation des requêtes préparées
            // => utilise de vraies requêtes préparées côté serveur
            // => améliore la sécurité contre les injections SQL
            PDO::ATTR_EMULATE_PREPARES => false,

            // Force l'encodage UTF-8 complet (utf8mb4)
            // Important pour supporter les emojis et caractères spéciaux
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    ); 

    // Si tout se passe bien, la connexion est établie ici

} catch(PDOException $e) {
    // Bloc exécuté si une erreur survient lors de la connexion

    // die() arrête immédiatement le script
    // getMessage() récupère le message d'erreur PDO
    die("Erreur de connexion à la base de données MariaDB : " . $e->getMessage());
}
?>