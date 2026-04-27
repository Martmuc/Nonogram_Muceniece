<?php

require_once(__DIR__ . '/../config/databaseconnect.php');


if (!isset($_SESSION["id_user"], $_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    exit("Accès non autorisé.");
}

// Liste des utilisateurs 
$sql = "SELECT id_user, username, password, mail, monnaie, role, DATE(date_connexion) AS date_connexion
        FROM user_
        ORDER BY id_user ASC";
$req = $pdo->query($sql);
$users = $req->fetchAll(PDO::FETCH_ASSOC);

// Liste des musiques
$sql2 = "SELECT id_music, prix, name 
        FROM music";
$req2 = $pdo->query($sql2);
$music = $req2->fetchAll(PDO::FETCH_ASSOC);

// Liste des grilles
$sql3 = "SELECT id_grille, longueur, largeur
        FROM grille";
$req3 = $pdo->query($sql3);
$grilles = $req3->fetchAll(PDO::FETCH_ASSOC);

