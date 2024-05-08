<?php
if (!isset($_POST["postID"]) || !isset($_POST["adminID"])) {
    echo "unset";
    return;
}

include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

// Create CuicuiManager instance
$cuicui_manager = new CuicuiManager($database_configs, DATASET);

try {
    // Mark the post as sensitive
    $cuicui_manager->markAsSensitive($_POST["postID"], $_POST["adminID"]);
    
    // Retrieve the user ID associated with the post
    $userId = $cuicui_manager->getPostUserId($_POST["postID"]);

    // Create NotificationsManager instance
    $notification_manager = new NotificationManager($cuicui_manager);
    
    // Prepare notification data
    $notificationType = "post_sensitivity";
    $notificationTitle = "Post Marked as Sensitive";
    $notificationText = "The post with ID " . $_POST["postID"] . " has been marked as sensitive.";

    // Insert notification
    $notification_manager->insertNotification($userId, date('Y-m-d H:i:s'), $notificationTitle, $notificationText, $notificationType);

    echo "ok";
} catch (NotAnAdminException $ex) {
    echo $ex->getMessage();
}
