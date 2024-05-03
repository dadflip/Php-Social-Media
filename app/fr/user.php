<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['IndexElement']);
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['NavBar']);

$cuicui_manager = new CuicuiManager($database_configs, DATASET);
$cuicui_sess = new CuicuiSession($cuicui_manager);

// Vérifier si le paramètre "@userid" est défini dans l'URL
if(isset($_GET["@userid"])) {
    // Récupérer la valeur du paramètre "@userid"
    $usernameWithAt = $_GET["@userid"];
    $username = substr($usernameWithAt, 1); // Supprimer le premier caractère "@" du nom d'utilisateur

    if($username == $_SESSION["username"]){
        header('Location:' . $appdir['PATH_CUICUI_APP'] . '/' .$GLOBALS['LANG']. $phpfile['options']);
        exit();
    }

    // Utiliser la valeur récupérée comme vous le souhaitez, par exemple l'afficher
    // echo "Nom d'utilisateur : " . $username;
} else {
    // Afficher un message d'erreur si le paramètre "@userid" n'est pas défini dans l'URL
    // echo "L'identifiant d'utilisateur n'est pas défini dans l'URL";
}

$follow_res = $cuicui_manager->getFollow($_SESSION["UID"], $username);
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuicui - Utilisateur</title>
    <link rel="stylesheet" href=<?php echo "../../css/".$_SESSION["theme"].".css"?> >
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/options.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php
echo createTitleBar("User profile");
?>
<div class="main-content">
    <div class="options-container">
        <?php
        if(!isset($username)) {
        ?>
        <p class="error-msg">L'identifiant d'utilisateur n'est pas défini</p>
        <?php
            return;
        }
        ?>
        <?php 
        $user_info = $cuicui_manager->getUserInfoByName($username);
        if($user_info != NULL) {
        ?>
        <div class="user-info">
            <div class="uid-name">
                <img src="<?php echo $user_info->getProfilePicture(); ?>" class="user-pfp" id="user-pfp">
                <div class="usernames">
                    <h1 class="username"><?php echo $user_info->getUsername(); ?></h1>
                    <h2 class="uid"><?php echo $user_info->getHandle(); ?></h2>
                </div>
            </div>
            <label for="biography">Biographie:</label>
            <p class="biography" rows="5" name="biography"><?php echo $user_info->bio; ?></p>
            <button id="followButton" class="followButton" onclick="<?php echo ($follow_res ? 'unfollowUser(' . $user_info->getID() . ')' : 'followUser(' . $user_info->getID() . ')'); ?>">
                <?php 
                if($follow_res) {
                    echo "arrêter de suivre";
                } else {
                    echo "suivre";
                }
                ?>
            </button>
        </div>
        <?php } else { ?>
        <p class="error-msg">Utilisateur avec l'ID=<?php echo $username; ?> n'existe pas</p>
        <?php } ?>
    </div>
</div>

<nav class="navbar" id="sliding-menu">
    <?php echo getNavbarContents()?>
</nav>
</body>

<script> window.__ajx__ = "<?php echo $appdir['PATH_PHP_DIR'] . '/ajax/main/'; ?>";</script>
<script> window.__u__ = <?php echo $_SESSION['UID']; ?></script>

<script src=<?php echo $appdir['PATH_JS_DIR'] . "/routes.js"?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/index.js"?>></script>
<script src=<?php echo $appdir['PATH_JS_DIR'] . "/flip.js"?>></script>
<?php includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['scripts']); ?>
</html>
