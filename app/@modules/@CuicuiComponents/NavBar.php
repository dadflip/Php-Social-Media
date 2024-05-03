<?php
function getNavbarContents() {
    $contents = "";
    $_log = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['login'];
    $_sig = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['register'];
    $_hom = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'];
    $_mai = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['mainpage'];
    $_sta = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['stats'];
    $_dis = $GLOBALS['normalized_paths']['PATH_MODULES'] . $GLOBALS['php_files']['disconnect'];
    $_opt = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['options'];

    switch($GLOBALS['LANG']) {
        case "fr":
            $contents .= "<a href='" . $_hom . "' id='home'>Accueil</a>";
            if(isset($_SESSION["username"])) {
                $contents .= "<a href='".$_mai."' id='mainpage'>Page principale</a>";
                $contents .= "<a href='".$_opt."' id='options'>Options</a>";
                $contents .= "<a href='".$_sta."' id='stats'>Statistiques</a>";
                $contents .= "<a href='".$_dis."' id='logout'>Déconnexion</a>";
            }else{
                $contents .= "<a href='". $_log ."' id='login'>Connexion</a>";
                $contents .= "<a href='". $_sig ."' id='register'>Créer un compte</a>";
            }
            break;
        case "en":
            $contents .= "<a href='" . $_hom . "' id='home'>Home</a>";
            if(isset($_SESSION["username"])) {
                $contents .= "<a href='".$_mai."' id='mainpage'>Main</a>";
                $contents .= "<a href='".$_opt."' id='options'>Settings</a>";
                $contents .= "<a href='".$_sta."' id='stats'>Statistics</a>";
                $contents .= "<a href='".$_dis."' id='logout'>Log Out</a>";
            }else{
                $contents .= "<a href='". $_log ."' id='login'>Log In</a>";
                $contents .= "<a href='". $_sig ."' id='register'>Sign In</a>";
            }
            break;
        default:
            $contents .= "<a href='#' id='home'>Home page</a>";
            if(isset($_SESSION["username"])) {
                $contents .= "<a href='".$_mai."' id='mainpage'>Main page</a>";
                $contents .= "<a href='".$_opt."' id='options'>Options</a>";
                $contents .= "<a href='".$_dis."' id='logout'>Log out</a>";
            }else{
                $contents .= "<a href='".$_log."' id='login'>Log in</a>";
                $contents .= "<a href='".$_sig."' id='register'>Sign in</a>";
            }
    }
    return $contents;
}

function createTitleBar(string $text): string {
    $_log = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['login'];
    $_sig = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['register'];
    $_hom = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'];
    $_mai = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['mainpage'];
    $_sta = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['stats'];
    $_dis = $GLOBALS['normalized_paths']['PATH_MODULES'] . $GLOBALS['php_files']['disconnect'];
    $_opt = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS['LANG'] . $GLOBALS['php_files']['options'];

    $action = (isset($_SESSION["UID"])) ? "$_opt" : "$_log";
    
    $out = '<div class="titlebar">
            <button onclick="SlidingMenu.menu()" class="logo" id="logo" aria-label="Shows the navigation bar"></button>
            <h1 class="title">'. $text .'</h1>
            <div class="title-user">
            <a class="user" href="'.$action.'">
            <h3>'.CuicuiSession::getUsername().'</h3>';
    if(isset($_SESSION["pfp_url"])) {
        // Chemin de l'image par défaut
        $default_image_path = $GLOBALS['normalized_paths']['PATH_IMG_DIR'] . '/placeholder.png';

        // Chemin de l'image utilisateur
        $user_image_path = $GLOBALS['normalized_paths']['PATH_CUICUI_PROJECT'] . $_SESSION["pfp_url"];

        // Vérifier si le fichier utilisateur existe
        if (file_exists($user_image_path)) {
            // Le fichier utilisateur existe, utilisez l'image utilisateur
            $img = $user_image_path;
        } else {
            // Le fichier utilisateur n'existe pas, utilisez l'image par défaut
            $img = $default_image_path;
        }

        // Génération de la balise d'image
        $out .= '<img src="' . $img . '" class="profile-pfp">';
    }
    $out .= '
            </a>
            </div>
        </div><div id="toasts"></div>';
    $out .= '
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Wow.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    ';
    return $out;
}
?>
