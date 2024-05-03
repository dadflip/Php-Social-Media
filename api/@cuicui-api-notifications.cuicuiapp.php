<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers nécessaires
include '../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

try {
    // Création d'une nouvelle instance de CuicuiManager
    $cuicui_manager = new CuicuiManager($database_configs, DATASET);

    // Préparer la requête pour récupérer toutes les notifications
    $stmt = $cuicui_manager->prepare("SELECT * FROM notifications");
    $stmt->execute();

    // Récupérer les résultats de la requête
    $result = $stmt->get_result();

    // Créer un tableau pour stocker toutes les notifications
    $notifications = array();

    // Parcourir les résultats et les ajouter au tableau de notifications
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    // Fermer la requête
    $stmt->close();

    // Répondre à la requête avec les notifications au format JSON
    echo json_encode($notifications, JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}

?>
