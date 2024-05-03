<!DOCTYPE html>
<?php
    // phpfile -> php_files.json
    // back(0) = ../

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Afficher le chemin actuel du fichier pour déboguer
    //var_dump(__DIR__);

    include '../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

    try {
        $cuicui_sess = new CuicuiSession(new CuicuiManager($database_configs, DATASET));
    } catch (Exception $ex) {
        die("Error when starting the session: " . $ex->getMessage()); /*updated $ex->getMessage*/
    }
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuicui</title>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css"?> >
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php
    echo printHomePageComponent();
    ?>

    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>
</body>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>
</html>