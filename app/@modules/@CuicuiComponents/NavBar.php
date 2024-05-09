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
            $contents .= "<li><a href='" . $_hom . "' id='home'>Accueil</a></li>";
            if(isset($_SESSION["username"])) {
                $contents .= "<li><a href='".$_mai."' id='mainpage'>Page principale</a></li>";
                $contents .= "<li><a href='".$_opt."' id='options'>Options</a></li>";
                $contents .= "<li><a href='".$_sta."' id='stats'>Statistiques</a></li>";
                $contents .= "<li><a href='".$_dis."' id='logout'>Déconnexion</a></li>";
            }else{
                $contents .= "<li><a href='". $_log ."' id='login'>Connexion</a></li>";
                $contents .= "<li><a href='". $_sig ."' id='register'>Créer un compte</a></li>";
            }
            break;
        case "en":
            $contents .= "<li><a href='" . $_hom . "' id='home'>Home</a></li>";
            if(isset($_SESSION["username"])) {
                $contents .= "<li><a href='".$_mai."' id='mainpage'>Main</a></li>";
                $contents .= "<li><a href='".$_opt."' id='options'>Settings</a></li>";
                $contents .= "<li><a href='".$_sta."' id='stats'>Statistics</a></li>";
                $contents .= "<li><a href='".$_dis."' id='logout'>Log Out</a></li>";
            }else{
                $contents .= "<li><a href='". $_log ."' id='login'>Log In</a></li>";
                $contents .= "<li><a href='". $_sig ."' id='register'>Sign In</a></li>";
            }
            break;
        default:
            $contents .= "<li><a href='#' id='home'>Home page</a></li>";
            if(isset($_SESSION["username"])) {
                $contents .= "<li><a href='".$_mai."' id='mainpage'>Main page</a></li>";
                $contents .= "<li><a href='".$_opt."' id='options'>Options</a></li>";
                $contents .= "<li><a href='".$_dis."' id='logout'>Log out</a></li>";
            }else{
                $contents .= "<li><a href='".$_log."' id='login'>Log in</a></li>";
                $contents .= "<li><a href='".$_sig."' id='register'>Sign in</a></li>";
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
    $_adm = $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/admin/main.php';

    $action = (isset($_SESSION["UID"])) ? "$_opt" : "$_log";
    
    $out = '<div class="titlebar">
            <button onclick="toHome()" class="logo" id="logo" aria-label="Shows the navigation bar"></button>
            <h1 class="title">'. $text .'</h1>
            <div class="title-user">
            <a class="user" href="'.$action.'">
            <h3>'.CuicuiSession::getUsername().'</h3>';
    if(isset($_SESSION["pfp_url"])) {
        // Chemin de l'image par défaut
        $default_image_path = $GLOBALS['normalized_paths']['PATH_IMG_DIR'] . '/placeholder.png';

        // Chemin de l'image utilisateur
        $user_image_path = $GLOBALS['normalized_paths']['PATH_IMG_DIR'] . $_SESSION["pfp_url"];

        $parent = realpath(__DIR__) . '../../../../img';

        // Vérifier si le fichier utilisateur existe
        if (file_exists($parent.$_SESSION["pfp_url"])) {
            // Le fichier utilisateur existe, utilisez l'image utilisateur
            $img = $user_image_path;
        } else {
            // Le fichier utilisateur n'existe pas, utilisez l'image par défaut
            $img = $default_image_path;
        }

        // Génération de la balise d'image
        $out .= '<img src="' . $img . '" class="profile-pfp">';
    }
    if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
        $out .= '</a><a class="user" href="'.$_adm.'"> @admin <i class="fas fa-external-link-alt"></i>';
    }
    $out .= '
            </a>
            </div>
        </div>';
    $out .= '
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Wow.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    ';
    return $out;
}
