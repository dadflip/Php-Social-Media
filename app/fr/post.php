<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../templates/databaseFunc.php";
require_once "../templates/create_error.php";
require_once "../templates/test.php";



$cuicui_sess = new CuicuiSession();

if(!isset($_SESSION["UID"])) {
    header("location: ./index.php");
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href=<?php echo "../../css/".$_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/options.css">
</head>
<body>
    <?php
    if(!isset($_GET["post_id"])) {
        echo create_error("fr", "Erreur: 404", "Aucun identifiant de post n'a été indiqué");
        return;
    }
    ?>
    <div class="main-content">
        <div class="options-container">

        </div>
    </div>
</body>
<footer>
    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>
</footer>
<?php require "../templates/scripts.php" ?>
</html>