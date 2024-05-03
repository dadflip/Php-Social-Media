<?php

// Inclusion des fichiers nécessaires
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

// Création d'une nouvelle instance de CuicuiManager et CuicuiSession
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

    // Vérifier si l'opération de suivi a réussi
    if ($success) {
        // Si l'opération de suivi réussit, renvoyer une réponse de succès
        echo json_encode(['success' => true, 'message' => 'Utilisateur suivi avec succès']);
    } else {
        // Si l'opération de suivi échoue, renvoyer une réponse d'erreur
        throw new Exception('Erreur lors du suivi de l\'utilisateur');
    }
} catch (Exception $e) {
    // En cas d'erreur, renvoyer une réponse d'erreur avec un message explicatif
    echo json_encode(['success' => false, 'message' => 'Erreur lors du suivi de l\'utilisateur: ' . $e->getMessage()]);
}
