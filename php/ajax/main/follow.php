<?php
// Inclure vos fichiers nécessaires
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

// Créer une nouvelle instance de CuicuiManager et CuicuiSession
$cuicui_manager = new CuicuiManager($database_configs, DATASET);
$cuicui_sess = new CuicuiSession($cuicui_manager);

try {
    // Initialiser la session si elle n'est pas déjà démarrée
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['UID'])) {
        // Si l'utilisateur n'est pas connecté, renvoyer une réponse d'erreur
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        exit;
    }

    // Vérifier si l'action est définie dans la requête POST
    if (!isset($_POST['action'])) {
        echo json_encode(['success' => false, 'message' => 'Action manquante']);
        exit;
    }

    // Récupérer l'action depuis la requête POST
    $action = $_POST['action'];

    // Vérifier l'action et effectuer les opérations correspondantes
    switch ($action) {
        case 'follow':
            // Vérifier si les données de suivi sont présentes dans la requête POST
            if (!isset($_POST['followerId'], $_POST['targetUserId'])) {
                echo json_encode(['success' => false, 'message' => 'Données de suivi manquantes']);
                exit;
            }

            // Récupérer les données de suivi depuis la requête POST
            $followerId = $_POST['followerId'];
            $targetUserId = $_POST['targetUserId'];

            // Insérer une nouvelle entrée dans la table de suivi
            $success = $cuicui_manager->followUser($followerId, $targetUserId);

            // Renvoyer une réponse de succès ou d'erreur
            echo $success ? json_encode(['success' => true, 'message' => 'Utilisateur suivi avec succès']) : json_encode(['success' => false, 'message' => 'Erreur lors du suivi de l\'utilisateur']);
            break;

        case 'unfollow':
            // Vérifier si les données de suivi sont présentes dans la requête POST
            if (!isset($_POST['followerId'], $_POST['targetUserId'])) {
                echo json_encode(['success' => false, 'message' => 'Données de suivi manquantes']);
                exit;
            }

            // Récupérer les données de suivi depuis la requête POST
            $followerId = $_POST['followerId'];
            $targetUserId = $_POST['targetUserId'];

            // Supprimer l'entrée correspondante dans la table de suivi
            $success = $cuicui_manager->unfollowUser($followerId, $targetUserId);

            // Renvoyer une réponse de succès ou d'erreur
            echo $success ? json_encode(['success' => true, 'message' => 'Utilisateur non suivi avec succès']) : json_encode(['success' => false, 'message' => 'Erreur lors du retrait de l\'utilisateur']);
            break;

        case 'check':
            // Vérifier si les données de suivi sont présentes dans la requête POST
            if (!isset($_POST['followerId'], $_POST['targetUserId'])) {
                echo json_encode(['success' => false, 'message' => 'Données de suivi manquantes']);
                exit;
            }

            // Récupérer les données de suivi depuis la requête POST
            $followerId = $_POST['followerId'];
            $targetUserId = $_POST['targetUserId'];

            // Vérifier si l'utilisateur suit l'autre utilisateur
            $is_following = $cuicui_manager->getFollow($followerId, $targetUserId);

            // Renvoyer true ou false pour indiquer si l'utilisateur suit ou non l'autre utilisateur
            echo $is_following ? 'true' : 'false';
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Action non valide']);
            break;
    }
} catch (Exception $e) {
    // En cas d'erreur, renvoyer une réponse d'erreur avec un message explicatif
    echo json_encode(['success' => false, 'message' => 'Erreur lors du suivi de l\'utilisateur: ' . $e->getMessage()]);
}
?>
