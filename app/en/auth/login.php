<!DOCTYPE html>
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Afficher le chemin actuel du fichier pour déboguer
    //var_dump(__DIR__);

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
        $lang = $_SESSION["lang"];
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' .$lang. $phpfile['mainpage']);
        exit();
    }
?>
<html lang="fr">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuicui - Connexion</title>
    <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/login.css"?> >
</head>
<body>
    <?php
    echo createTitleBar("Log In");
    ?>
    <div class="main-container">
        <form class="login-form" id="login" action="#" method="post">
            <div class="input-field">
                <label for="username">Username</label>
                <input class="login-input" type="text" name="username"  placeholder="Nom d'utilisateur" required minlength="4" maxlength="30" autocomplete="off">
            </div>
            <div class="input-field">
                <label for="password">Password</label>  
                <input class="login-input" type="password" name="password"  placeholder="Mot de passe" autocomplete="off">
            </div>
            <?php
            if($connection_success->getLoginStatus() == false && $connection_success->getText() != "") {
                echo "<p class='error-msg'>".$connection_success->getText()."</p>";
            }
            ?>
            <input type="submit" name="submit" value="Connexion"> 
            <a href=<?php echo $appdir['PATH_CUICUI_APP'] . '/' .$GLOBALS['LANG'].$phpfile['register']?>>Create Account</a>
        </form>  
    </div>
    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>
</body>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
</html>