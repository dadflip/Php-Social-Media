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
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['getRegisterForm']);

    $cuicui_manager = new CuicuiManager($database_configs, DATASET);
    $cuicui_sess = new CuicuiSession($cuicui_manager);

    // Vérifier si le formulaire est soumis
    if(isset($_POST['submit'])) {
        //echo 'il y a un souci ici';
        // Exécuter la fonction createAccount uniquement si le bouton du formulaire est cliqué
        $res = $cuicui_manager->createAccount();
    }
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuicui - Créer un compte</title>
    <link rel="icon" type="image/png" href=<?php echo $appdir['PATH_IMG_DIR'] . "/icon.png" ?>>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/login.css"?> >
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php
        echo createTitleBar("Sign In");
    ?>

    <div class="main-container">
        <form id="login" action="#" method="post" class="register-form" enctype="multipart/form-data">
            <?php echo getRegisterForm_1();?>
            <?php 
            if(isset($res) && !$res && $cuicui_manager->getError() != NULL) {
                echo "<p class='error-msg'>".$cuicui_manager->getError()->toString()."</p>";
            }
            ?>
        </form>  
    </div>

    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>
</body>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>
</html>
