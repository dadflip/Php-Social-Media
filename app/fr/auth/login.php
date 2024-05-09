<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include '../../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $cuicui_sess = new CuicuiSession($cuicui_manager);

    if(isset($_SESSION["UID"])) {
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' .$GLOBALS['LANG']. $phpfile['mainpage']);
    }

    $connection_success = $cuicui_manager->connectUser();

    if($connection_success->getLoginStatus()) {
        if($cuicui_manager->checkAndLiftBan($_SESSION['UID'])){
            $lang = $_SESSION["lang"];
            header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' .$lang. $phpfile['mainpage']);
            exit();
        } else {
            header('Location:' . $appdir['PATH_MODULES'] . $phpfile['ban_disconnect']);
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log In | Cuicui App</title>

        <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"].".css"?> >
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css"?> >
        <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/login.css"?> >

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body>
        <?php
        echo createTitleBar("#Connexion");
        ?>
        <div class="main-container">
            <form class="login-form" id="login" action="#" method="post">
                <div class="input-field">
                    <label for="username">Nom d'utilisateur</label>
                    <input class="login-input" type="text" name="username"  placeholder="Nom d'utilisateur" required minlength="4" maxlength="30" autocomplete="off">
                </div>
                <div class="input-field">
                    <label for="password">Mot de passe</label>  
                    <input class="login-input" type="password" name="password"  placeholder="Mot de passe" autocomplete="off">
                </div>
                <?php
                if($connection_success->getLoginStatus() == false && $connection_success->getText() != "") {
                    echo "<p class='error-msg'>".$connection_success->getText()."</p>";
                }
                ?>
                <input type="submit" name="submit" value="Connexion"> 
                <a href=<?php echo $appdir['PATH_CUICUI_APP'] . '/' .$GLOBALS['LANG'].$phpfile['register']?>>Créer un compte</a>
            </form>
        </div>
    </body>

    <script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
</html>