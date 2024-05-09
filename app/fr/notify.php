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
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $phpfile['mainpage']);
        exit();
    }

    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $cuicui_sess = new CuicuiSession($cuicui_manager);

    if (isset($_SESSION['UID'])) {
        $notifs = new NotificationManager($cuicui_manager);
        $notifications = $notifs->getNotificationsByUserId($_SESSION['UID']);
        $countAllNotifications = count($notifications);
        $user_info = $cuicui_sess->getUserInfoAndSettings($_SESSION["UID"]);
        $settings = $user_info->getSettingsArray();
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Notifications | Cuicui App</title>
        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Link']); ?>

        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"] . ".css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/flip/flip.style.css" ?>>
    </head>

    <body>
        <?php echo createTitleBar("@Notifications"); ?>
        <main class="container">
            <div class="app">
                <div class="center-column">
                    <div class="center-main-panel">
                    <h1>Notifications <a class="user" href="notify.php"><i class="fas fa-external-link-alt"></i></a></h1>
                        <?php if($settings['notifications']['push']) {
                            if ($countAllNotifications > 0) : ?>
                            <?php foreach ($notifications as $notification) : ?>
                                <div class="notification <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                                    <p><?php echo $notification['text_content']; ?></p>
                                    <?php if ($notification['notification_id']) : ?>
                                        <p><?php echo $notification['title']; ?></p>
                                        <p><?php echo $notification['c_datetime']; ?></p>
                                        <p><?php echo $notification['notification_type']; ?></p>
                                    <?php endif; ?>
                                    <span>
                                        <?php if (!$notification['is_read']) : ?>
                                            <button class="mark-as-read" data-id="<?php echo $notification['notification_id']; ?>">Marquer comme lu</button>
                                        <?php endif; ?>
                                        <button class="delete-btn" data-id="<?php echo $notification['notification_id']; ?>">Supprimer</button>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="no-notifs">
                                <p>Aucune notification</p>
                            </div>
                        <?php endif; } else {
                        ?>
                            <div class="no-notifs">
                                <p>Veuillez réactiver les notifications dans les paramètres !</p>
                            </div>
                        <?php
                        } ?>
                    </div>
                </div>

                <button id="toggle-right-panel">☰</button>
                <div class="right-panel">
                    <section id="right-links">
                        <ul>
                            <?php echo getNavbarContents() ?>
                            <hr class="links-separator">
                            <li><a href="<?php echo $appdir['PATH_CUICUI_PROJECT'] . '/@/discover/starter.php' ?>"><i class="fas fa-search"></i> Découvrir</a></li>
                            <li><a href="<?php echo $appdir['PATH_CUICUI_PROJECT'] . '/@/cuicui/ourpolicy.php' ?>"><i class="fas fa-lock"></i> Politique de confidentialité</a></li>
                            <li><a href="<?php echo $appdir['PATH_API_DIR'] ?>"><i class="fas fa-cogs"></i> API</a></li>
                        </ul>
                        <p>&copy; Cuicui App 2024</p>
                    </section>
                </div>
            </div>
        </main>

        <?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['Script']); ?>
    </body>
</html>