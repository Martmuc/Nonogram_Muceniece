<?php
// $dir = 'C:/MAMP/htdocs/nonogram/extras/';  // Utilisez / pour compatibilité
// $pdo = new PDO('mysql:host=localhost:3306;dbname=nonogram', 'test', 'test');  // MAMP par défaut
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// function importerMusique($dir, $pdo) {
//     $fichiers = scandir($dir);
//     foreach ($fichiers as $fichier) {
//         if ($fichier == '.' || $fichier == '..') continue;
//         $cheminComplet = $dir . $fichier;
//         if (is_dir($cheminComplet)) {
//             importerMusique($cheminComplet . '/', $pdo);  // Récursif
//         } elseif (strtolower(pathinfo($fichier, PATHINFO_EXTENSION)) == 'mp3') {
//             $nomCourt = substr($fichier, 0, 50);  // Limite varchar(50)
//             $stmt = $pdo->prepare("INSERT IGNORE INTO music (name, prix) VALUES (?, 10)");
//             $stmt->execute([$nomCourt]);
//             echo "Ajouté: $nomCourt\n";
//         }
//     }
// } 

// importerMusique($dir, $pdo);
// echo "Import terminé ! Vérifiez en phpMyAdmin.";


// session_start();

// // Sécurité Admin
// if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "admin") {
//     header('Location: ../index.php');
//     exit();
// }

// // Inclure ta connexion PDO (celle avec le try/catch que tu as fournie)
// require_once __DIR__ . '/../../databaseconnect.php'; 

// // --- Configuration ---
// $dir = 'C:/MAMP/htdocs/nonogram/extras/'; 

// // --- Fonction d'importation ---
// function importerMusique($dir, $pdo) {
//     $count = 0;
//     if (!is_dir($dir)) return 0;

//     $fichiers = scandir($dir);
//     foreach ($fichiers as $fichier) {
//         if ($fichier == '.' || $fichier == '..') continue;
        
//         $cheminComplet = $dir . $fichier;
        
//         if (is_dir($cheminComplet)) {
//             $count += importerMusique($cheminComplet . '/', $pdo); 
//         } elseif (strtolower(pathinfo($fichier, PATHINFO_EXTENSION)) == 'mp3') {
//             $nomCourt = substr($fichier, 0, 50); 
            
//             // On vérifie si elle existe déjà pour ne pas polluer l'affichage
//             $stmt = $pdo->prepare("INSERT IGNORE INTO music (name, prix) VALUES (?, 10)");
//             $stmt->execute([$nomCourt]);
            
//             if ($stmt->rowCount() > 0) {
//                 $count++;
//             }
//         }
//     }
//     return $count;
// }

// // --- Exécution ---
// try {
//     $nbAjouts = importerMusique($dir, $pdo);
//     // Redirection vers ta page principale avec un message
//     header("Location: ../music.php?success=" . $nbAjouts);
//     exit();
// } catch (Exception $e) {
//     die("Erreur lors de l'import : " . $e->getMessage());
// }

session_start();

require_once __DIR__ . '/../../config/databaseconnect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['musicFile'])) {
    header('Location: ../music.php');
    exit;
}

$file = $_FILES['musicFile'];
$prix = isset($_POST['prix']) ? (int) $_POST['prix'] : 0;

if ($prix < 0) {
    header('Location: ../music.php?error=prix');
    exit;
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../music.php?error=upload');
    exit;
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($extension !== 'mp3') {
    header('Location: ../music.php?error=format');
    exit;
}

$uploadDir = __DIR__ . '/../../extras/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$originalName = basename($file['name']);
$safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
$fileName = uniqid('music_', true) . '_' . $safeName;
$targetPath = $uploadDir . $fileName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    header('Location: ../music.php?error=move');
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO music (name, prix)
        VALUES (?, ?)
    ");
    $stmt->execute([$fileName, $prix]);

    header('Location: ../music.php?success=1');
    exit;

} catch (PDOException $e) {
    if (file_exists($targetPath)) {
        unlink($targetPath);
    }

    header('Location: ../music.php?error=database');
    exit;
}