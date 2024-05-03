<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../.paths.php');
include('../../../../lib/include/php/Database/DatabaseManager.php');

// Instancier un objet de la classe DatabaseManager avec les informations de connexion
$databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

// Dossier de destination pour les vidéos traitées
$destinationFolder = 'media/' . $_SESSION['user_id'];

// Créer le dossier s'il n'existe pas
if (!file_exists($destinationFolder)) {
    mkdir($destinationFolder, 0777, true);
}

// Gérer le fichier vidéo
if (isset($_FILES['videoCapture']) && $_FILES['videoCapture']['error'] === UPLOAD_ERR_OK) {
    $videoFile = $_FILES['videoCapture'];

    // Générer un nom unique pour le fichier vidéo
    $videoFileName = uniqid('vid_') . '.' . pathinfo($videoFile['name'], PATHINFO_EXTENSION);

    // Chemin complet du fichier dans le dossier de destination
    $videoFilePath = $destinationFolder . '/' . $videoFileName;

    // Déplacer le fichier vers le dossier de destination
    move_uploaded_file($videoFile['tmp_name'], $videoFilePath);

    // Construire l'URL complète du fichier traité
    $processedVideoUrl = $videoFilePath; // Vous pouvez ajuster cela en fonction de votre structure d'URL

    // Retourner l'URL du fichier traité en format JSON
    echo json_encode(['url' => $processedVideoUrl]);
} else {
    // Si une erreur s'est produite lors du téléchargement
    echo json_encode(['error' => 'Erreur lors du téléchargement de la vidéo.']);
}
?>
