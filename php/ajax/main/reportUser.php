<?php
// Inclure les fichiers nécessaires
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);


// Create CuicuiManager instance
$cuicui_manager = new CuicuiManager($database_configs, DATASET);

// Vérifier si la requête est effectuée via la méthode POST et si les données nécessaires sont présentes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reporterId']) && isset($_POST['targetId'])) {
    $reporterId = $_POST['reporterId'];
    $targetId = $_POST['targetId'];

    try {
        $notificationTitle = "Reported User";
        $notificationText = "User with ID " . $targetId . " have been reported.";
        $notificationType = "user_report";
        $cuicui_manager->sendNotificationToAdmins($notificationTitle, $notificationText, $notificationType);
    
        echo json_encode(['success' => true, 'message' => 'Utilisateur signalé avec succès !']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Une erreur s\'est produite lors du signalement de l\'utilisateur.']);
    }
    
} else {
    // Si les données nécessaires ne sont pas fournies, répondre avec un message d'erreur
    echo json_encode(['success' => false, 'message' => 'Données manquantes pour signaler l\'utilisateur.']);
}
