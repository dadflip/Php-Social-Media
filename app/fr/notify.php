<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['UID'])) {
    header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' .$GLOBALS['LANG']. $phpfile['mainpage']);
    exit();
}


// Créer une instance de CuicuiManager et CuicuiSession
$cuicui_manager = new CuicuiManager($database_configs, DATASET);
$cuicui_sess = new CuicuiSession($cuicui_manager);

// Marquer les notifications comme lues lors de l'accès à la page de notifications
if(isset($_SESSION['UID'])){
    // Marquer les notifications comme lues lors de l'accès à la page de notifications
    $updateQuery = "UPDATE notifications SET is_read = 1 WHERE users_uid = ? AND is_read = 0";
    $updateStmt = $cuicui_manager->prepare($updateQuery);
    $updateStmt->execute([$_SESSION['UID']]);
}

// Récupérer les notifications de l'utilisateur depuis les deux dernières semaines
$twoWeeksAgo = date('Y-m-d H:i:s', strtotime('-2 weeks'));
$selectQuery = "SELECT * FROM notifications WHERE /*users_uid = ? AND*/ c_datetime > ? ORDER BY c_datetime DESC";
$selectStmt = $cuicui_manager->prepare($selectQuery);
//$selectStmt->bind_param("ss", $_SESSION['UID'], $twoWeeksAgo);
$selectStmt->bind_param("s", $twoWeeksAgo);
$selectStmt->execute();
$result = $selectStmt->get_result();

// Vérifier si la requête a réussi
if ($result) {
    // Récupérer les résultats sous forme de tableau associatif
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Gérer l'erreur si la requête échoue
    echo "Erreur lors de la récupération des notifications";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>

    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css"?> >

    <style>
        /* Styles CSS pour les notifications */
        .notification {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        .notification.unread {
            font-weight: bold;
        }

        .notification p {
            margin: 0;
        }

        .delete-btn {
            color: red;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="content">
        <h1>Notifications</h1>
        <?php if (count($notifications) > 0) : ?>
            <?php foreach ($notifications as $notification) : ?>
                <div class="notification <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                    <p><?php echo $notification['text_content']; ?></p>
                    <?php if ($notification['notification_id']) : ?>
                        <p><?php echo $notification['title']; ?></p>
                        <p><?php echo $notification['c_datetime']; ?></p>
                        <p><?php echo $notification['notification_type']; ?></p>
                    <?php endif; ?>
                    <span><button class="delete-btn" data-id=<?php echo $notification['notification_id']; ?> >Supprimer</button></span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="no-notifs">
                <p>Aucune notification</p>
            </div>
        <?php endif; ?>
    </div>

    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>

    <script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
    <script src=<?php echo $appdir['PATH_JS_DIR'] . "/index.js"?>></script>
    <script src=<?php echo $appdir['PATH_JS_DIR'] . "/flip.js"?>></script>

    <script>

    </script>
</body>

</html>
