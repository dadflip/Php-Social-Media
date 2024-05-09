<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

    session_start();
    if (!isset($_SESSION['UID'])) {
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' .$GLOBALS['LANG']. $phpfile['mainpage']);
        exit();
    }

    if(isset($_POST['notification_id'])) {
        $notificationId = $_POST['notification_id'];
        
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        $cuicui_sess = new CuicuiSession($cuicui_manager);
        
        $deleteQuery = "DELETE FROM notifications WHERE notification_id = ? AND users_uid = ?";
        
        $deleteStmt = $cuicui_manager->prepare($deleteQuery);
        
        $deleteStmt->bind_param("ii", $notificationId, $_SESSION['UID']);
        if ($deleteStmt->execute()) {
            http_response_code(200);
            echo "Notification deleted succesfully";
        } else {
            http_response_code(500);
            echo "Error by deleting the notification";
        }
    } else {
        http_response_code(400);
        echo "No id specified";
    }
