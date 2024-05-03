<!DOCTYPE html>
<?php

include '../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

$cuicui_manager = new CuicuiManager($database_configs, DATASET);
$cuicui_sess = new CuicuiSession($cuicui_manager);

if(!isset($_SESSION["UID"])) {
    header('Location:' . $appdir['PATH_CUICUI_APP']);
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . '/' . $_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/main.css"?> >
    <link rel="stylesheet" href=<?php echo $appdir['PATH_CSS_DIR'] . "/options.css"?> >
</head>
<body>
    <?php echo createTitleBar("Mes statistiques")?>
    <div class="main-content">
        <div class="options-container">

        </div>
    </div>
    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>
</body>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>
</html>