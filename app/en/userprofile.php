<!DOCTYPE html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "../../@modules/@CuicuiDB/CuicuiErrors.php";
require "../../@modules/@CuicuiDB/CuicuiManager.php";
require "../../@modules/@CuicuiDB/test.php";


$cuicui_sess = new CuicuiSession(new CuicuiManager($database_configs, DATASET));

if($_GET["UID"] == $_SESSION["UID"]) {
    header("location: ./options.php");
}

$follow_res = $cuicui_sess->getFollow($_SESSION["UID"], $_GET["UID"]);
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
            if(!isset($_GET["UID"])) {
            ?>
            <p class="error-msg">L'identifiant d'utilisateur n'est pas défini</p>
            <?php
                return;
            }
            ?>
            <?php 
            $user_info = $cuicui_sess->getUserInfo($_GET["UID"]);
            if($user_info != NULL) {
            ?>
            <div class="user-info">
                <div class="uid-name">
                    <img src=<?php echo $user_info->getProfilePicture();?>  class="user-pfp" id="user-pfp">
                    <div class="usernames">
                        <h1 class="username"><?php echo $user_info->getUsername();?></h1>
                        <h2 class="uid"><?php echo $user_info->getHandle();?></h2>
                    </div>
                </div>
                <label for="biography">Biographie:</label>
                <p class="biography" rows="5" name="biography"><?php echo $user_info->bio?></p>
                <button id="followButton" class="followButton" onclick=<?php if($follow_res) echo 'unfollowUser()'; else echo 'followUser()';?>>
                <?php 
                if($follow_res) {
                    echo "arrêter de suivre";
                } else {
                    echo "suivre";
                }
                ?></button>
            </div>
            <?php } else {
                
            ?>
            <p class="error-msg">User with UID=<?php echo $_GET["UID"]?> doesn't exist</p>
            <?php } ?>
        </div>
    </div>
</body>
<footer>
    <nav class="navbar" id="sliding-menu">
        <?php echo getNavbarContents()?>
    </nav>
</footer>
<script src="../../javascript/userprofile.js"></script>
<?php require "../templates/scripts.php" ?>
</html>
