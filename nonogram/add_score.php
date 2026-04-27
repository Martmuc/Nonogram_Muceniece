<?php
session_start();

require_once __DIR__ . '/config/databaseconnect.php';

if (!isset($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$score = isset($_POST['score']) ? (int) $_POST['score'] : 0;

if ($score <= 0) {
    echo json_encode(['success' => false, 'message' => 'Score invalide.']);
    exit;
}

$idUser = (int) $_SESSION['id_user'];

try {
    $stmt = $pdo->prepare("
        UPDATE user_
        SET monnaie = monnaie + ?
        WHERE id_user = ?
    ");
    $stmt->execute([$score, $idUser]);

    $_SESSION['monnaie'] = ($_SESSION['monnaie'] ?? 0) + $score;

    echo json_encode([
        'success' => true,
        'message' => 'Score ajouté à la monnaie.',
        'nouvelle_monnaie' => $_SESSION['monnaie']
    ]);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
    exit;
}