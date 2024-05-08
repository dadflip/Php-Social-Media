<?php
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

// Vérifier si la requête POST contient l'ID de la notification à supprimer
if(isset($_POST['id'])) {
    // Récupérer l'ID de la notification depuis la requête POST
    $notificationId = $_POST['id'];

    echo "ID de la notification à supprimer : " . $notificationId . "<br>";

    // Create CuicuiManager instance
    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $notificationManager = new NotificationManager($cuicui_manager);

    // Supprimer la notification en utilisant la méthode deleteNotification
    $success = $notificationManager->deleteNotification($notificationId);

    // Vérifier si la suppression a réussi
    if($success) {
        // Retourner une réponse JSON indiquant le succès
        echo json_encode(array('success' => true));
    } else {
        // Retourner une réponse JSON indiquant l'échec
        echo json_encode(array('success' => false, 'message' => 'Échec de la suppression de la notification'));
    }
} else {
    // Retourner une réponse JSON indiquant une erreur si l'ID de la notification n'est pas fourni dans la requête POST
    echo json_encode(array('success' => false, 'message' => 'ID de notification non fourni dans la requête POST'));
}
