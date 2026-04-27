<?php
session_start();

require_once __DIR__ . '/config/databaseconnect.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: views/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: views/shop.php');
    exit;
}

$idUser = (int) $_SESSION['id_user'];
$idMusic = (int) ($_POST['id_music'] ?? 0);

if ($idMusic <= 0) {
    $_SESSION['SHOP_ERROR_MESSAGE'] = 'Musique invalide.';
    header('Location: views/shop.php');
    exit;
}

try {
    $pdo->beginTransaction();

    // Récupérer la musique
    $stmtMusic = $pdo->prepare("SELECT id_music, prix FROM music WHERE id_music = ?");
    $stmtMusic->execute([$idMusic]);
    $music = $stmtMusic->fetch(PDO::FETCH_ASSOC);

    if (!$music) {
        throw new Exception("Musique introuvable.");
    }

    // Vérifier si déjà achetée
    $stmtCheck = $pdo->prepare("
        SELECT 1 
        FROM user_music 
        WHERE id_user = ? AND id_music = ?
    ");
    $stmtCheck->execute([$idUser, $idMusic]);

    if ($stmtCheck->fetch()) {
        throw new Exception("Vous possédez déjà cette musique.");
    }

    // Récupérer la monnaie utilisateur
    $stmtUser = $pdo->prepare("SELECT monnaie FROM user_ WHERE id_user = ?");
    $stmtUser->execute([$idUser]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("Utilisateur introuvable.");
    }

    $monnaieActuelle = (int) $user['monnaie'];
    $prix = (int) $music['prix'];

    if ($monnaieActuelle < $prix) {
        throw new Exception("Monnaie insuffisante.");
    }

    // Débiter la monnaie
    $stmtUpdate = $pdo->prepare("
        UPDATE user_
        SET monnaie = monnaie - ?
        WHERE id_user = ?
    ");
    $stmtUpdate->execute([$prix, $idUser]);

    // Enregistrer l'achat
    $stmtInsert = $pdo->prepare("
        INSERT INTO user_music (id_user, id_music)
        VALUES (?, ?)
    ");
    $stmtInsert->execute([$idUser, $idMusic]);

    $pdo->commit();

    $_SESSION['monnaie'] = $monnaieActuelle - $prix;
    $_SESSION['SHOP_SUCCESS_MESSAGE'] = 'Musique achetée avec succès.';

    header('Location: views/shop.php');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['SHOP_ERROR_MESSAGE'] = $e->getMessage();
    header('Location: views/shop.php');
    exit;
}