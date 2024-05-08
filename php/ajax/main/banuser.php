<?php
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

$cuicui_manager = new CuicuiManager($database_configs, DATASET);

try {
    // Ban the user and send a notification
    $cuicui_manager->banUserAndSendNotification($_POST["userID"], $_POST["adminID"], $_POST["duration"], $_POST["reason"]);

    echo "ok";
} catch (NotAnAdminException $ex) {
    echo $ex->getMessage();
    return;
}
