<?php
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

$cuicui_manager = new CuicuiManager($database_configs, DATASET);
$cuicui_sess = new CuicuiSession($cuicui_manager);

$userId = $_SESSION['UID'];
$userName = $_SESSION['username'];

// Initialiser les variables
$title = $content = $category = $keywords = '';
$imageFilePath = null;
$videoFilePath = null;

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $tags = $_POST['keywords'];

    // Créer un dossier pour l'utilisateur s'il n'existe pas
    $userFolder = $GLOBALS['img_upload'] . '@' . $userName . '-' . $userId . '.media';
    $parent = realpath(__DIR__) . '../../../../img';

    if (!file_exists($userFolder)) {
        mkdir($parent . $userFolder, 0777, true);
    }

    // Gérer le téléchargement de l'image
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $currentDate = date('YmdHis'); // Obtient la date actuelle au format YYYYMMDDHHmmss
        $imageFileName = '@' . $userName . '_img_' . $currentDate . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageFilePath = $userFolder . '/' . $imageFileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $parent . $imageFilePath);
    } elseif ($_FILES['video']['error'] === UPLOAD_ERR_OK) { // Gérer le téléchargement de la vidéo
        $currentDate = date('YmdHis'); // Obtient la date actuelle au format YYYYMMDDHHmmss
        $videoFileName = '@' . $userName . '_vid_' . $currentDate . '.' . pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
        $videoFilePath = $userFolder . '/' . $videoFileName;
        move_uploaded_file($_FILES['video']['tmp_name'], $parent . $videoFilePath);
    } else {
        // Récupérer les URLs des médias depuis les paramètres de l'URL
        $imageFilePath = isset($_GET['image_url']) ? $_GET['image_url'] : '';
        $videoFilePath = isset($_GET['video_url']) ? $_GET['video_url'] : '';
    }

    $postDate = date('Y-m-d H:i:s');
    $contentImgUrl = $imageFilePath;
    $contentVidUrl = $videoFilePath;
    
    // Générer un ID numérique aléatoire pour le média
    $mediaId = $cuicui_manager->generateRandomNumericId(9);
    
    // Ajouter des informations dans la table 'media'
    $mediaType = '';
    $mediaUrl = '';
    
    if (!empty($contentImgUrl)) {
        $mediaType = 'image';
        $mediaUrl = $contentImgUrl;
    } elseif (!empty($contentVidUrl)) {
        $mediaType = 'video';
        $mediaUrl = $contentVidUrl;
    }
    
    $createdAt = date('Y-m-d H:i:s');
    $postTextId = uniqid().'@'.$userId;
    
    // Requête pour insérer les données dans la table 'media'
    $mediaQuery = "INSERT INTO media (media_id, posts_text_id, type, url, creation_date) VALUES (?, ?, ?, ?, ?)";
    $cuicui_manager->executeRequest($mediaQuery, "issss", $mediaId, $postTextId, $mediaType, $mediaUrl, $createdAt);
    
    // Requête pour insérer les données dans la table 'posts'
    $postQuery = "INSERT INTO posts (users_uid, title, category, tags, post_date, content_img_url, content_vid_url, text_content, media_id, textId) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $cuicui_manager->executeRequest($postQuery, "isssssssis", $userId, $title, $category, $tags, $postDate, $contentImgUrl, $contentVidUrl, $content, $mediaId, $postTextId);

    // Insérer une notification dans la table 'notifications'
    $notificationType = "post";
    $notificationDate = date('Y-m-d H:i:s');
    $notificationTitle = "Nouveau post créé";
    $notificationText = "L'utilisateur " . $userName . " a créé un nouveau post.";

    $insertNotificationQuery = "INSERT INTO notifications (users_uid, c_datetime, title, text_content, notification_type) VALUES (?, ?, ?, ?, ?)";

    $cuicui_manager->executeRequest($insertNotificationQuery, "issss", $userId, $notificationDate, $notificationTitle, $notificationText, $notificationType);
    
    // Rediriger vers l'interface principale après l'ajout
    header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['mainpage']);
    exit();
}
?>
