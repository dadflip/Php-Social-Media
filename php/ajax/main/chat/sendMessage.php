<?php
// Vérifier si le message a été envoyé via une requête POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    // Inclure les fichiers nécessaires
    include '../../../../app/defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

    // Récupérer le message envoyé par l'utilisateur
    $message = $_POST['message'];
    $type = $_POST['type'];

    // Initialiser CuicuiManager
    $cuicui_manager = new CuicuiManager($database_configs, DATASET);

    // Initialiser un tableau pour stocker les ID des destinataires
    $destIds = $_POST['destId'];

    // Initialiser le statut d'insertion à true
    $insertStatus = true;

    // Insérer le message dans la base de données pour chaque destinataire
    foreach ($destIds as $destId) {
        // Insérer le message dans la base de données pour chaque destinataire
        $success = $cuicui_manager->insertChatMessage($message, $type, $_SESSION['UID'], $destId); // Assurez-vous d'avoir une méthode insertChatMessage dans CuicuiManager

        // Vérifier si l'insertion a échoué pour un destinataire
        if (!$success) {
            // Si l'insertion échoue, mettre à jour le statut d'insertion à false
            $insertStatus = false;
            // Arrêter la boucle et retourner une réponse d'échec
            break;
        }
    }

    // Retourner une réponse JSON indiquant le statut de l'insertion du message
    echo json_encode(['success' => $insertStatus]);
}
