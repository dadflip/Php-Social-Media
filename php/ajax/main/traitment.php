<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

try {
    session_start();
    if (!isset($_SESSION['UID'])) {
        throw new Exception("Session UID non définie.");
    }
    $userId = $_SESSION['UID'];

    // Récupérer les données de la requête Ajax
    $title = isset($_POST['title']) ? $_POST['title'] : null;
    $postId = isset($_POST['postId']) ? $_POST['postId'] : null;
    $tags = isset($_POST['tags']) ? $_POST['tags'] : null;
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $currentDate = isset($_POST['currentDate']) ? $_POST['currentDate'] : null;
    $currentTime = isset($_POST['currentTime']) ? $_POST['currentTime'] : null;
    $browserInfo = isset($_POST['browserInfo']) ? $_POST['browserInfo'] : null;

    // Extraire les champs de browser_info
    $browserName = $browserInfo['browser_name'];
    $browserVersion = $browserInfo['browser_version'];
    $userAgent = $browserInfo['user_agent'];
    // Ajoutez d'autres champs en fonction de votre structure

    // Création d'une nouvelle instance de CuicuiManager
    $cuicui_manager = new CuicuiManager($database_configs, DATASET);

    // Préparer la requête pour insérer ou mettre à jour les informations dans la table 'data'
    $stmt = $cuicui_manager->prepare("INSERT INTO data (users_uid, posts_id, browser_name, browser_version, user_agent) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE browser_name = VALUES(browser_name), browser_version = VALUES(browser_version), user_agent = VALUES(user_agent)");
    $stmt->bind_param("issss", $userId, $postId, $browserName, $browserVersion, $userAgent);
    $stmt->execute();

    // Vérifier le succès de l'opération
    if ($stmt->affected_rows > 0) {
        // Répondre à la requête Ajax
        echo json_encode(['success' => true, 'message' => 'Les informations ont été insérées ou mises à jour avec succès.']);
    } else {
        // En cas d'échec
        echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion ou de la mise à jour des informations.']);
    }

    // Fermer la requête
    $stmt->close();


    // Supprimer les enregistrements datant de plus d'une semaine pour l'utilisateur connecté
    $weekAgo = strtotime('-1 week');
    $deleteStmt = $cuicui_manager->prepare("DELETE FROM data WHERE users_uid = ? AND _datetime_ < ?");
    $deleteStmt->bind_param("is", $userId, $weekAgo);
    $deleteStmt->execute();

    // Vérifier le succès de l'opération de suppression
    if ($deleteStmt->affected_rows > 0) {
        // Répondre à la requête Ajax
        echo json_encode(['success' => true, 'message' => 'Les enregistrements datant de plus d\'une semaine ont été supprimés avec succès.']);
    } else {
        // En cas d'échec
        echo json_encode(['success' => false, 'error' => 'Aucun enregistrement datant de plus d\'une semaine trouvé pour cet utilisateur.']);
    }

    // Fermer la requête de suppression
    $deleteStmt->close();

    // Fermer la session
    // session_write_close();

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}

?>
