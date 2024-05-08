<?php
// Fonction pour imprimer le composant HTML de la page d'accueil
function printHomePageComponent()
{
    // Composant HTML
    $component = '
    <div class="header animate__animated animate__fadeInDown">
        <div class="container">
            <h1>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Application CuiCui';
            break;
        case "en":
        default:
            $component .= 'CuiCui App';
            break;
    }

    $component .= '</h1>
            <p>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Discutez, Explorez et Découvrez';
            break;
        case "en":
        default:
            $component .= 'Chat, Explore and Discover';
            break;
    }

    $component .= '</p>
            <a href="./mainpage.php" class="cta-button">';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Ouvrir';
            break;
        case "en":
        default:
            $component .= 'Open';
            break;
    }

    $component .= '</a>
        </div>
    </div>

    <div class="container services animate__animated animate__fadeInUp">
    <div class="service">
        <i class="icon fas fa-home"></i>
        <h2>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Accueil';
            break;
        case "en":
        default:
            $component .= 'Home';
            break;
    }

    $component .= '</h2>
        <p>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Rendez-vous sur la page d\'accueil';
            break;
        case "en":
        default:
            $component .= 'Go to the home page';
            break;
    }

    $component .= '</p>
    </div>
    <div class="service">
        <i class="icon fas fa-sign-in-alt"></i>
        <h2>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Connexion';
            break;
        case "en":
        default:
            $component .= 'Login';
            break;
    }

    $component .= '</h2>
        <p>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Connectez-vous pour profiter au maximum !';
            break;
        case "en":
        default:
            $component .= 'Sign in to get the most out of it!';
            break;
    }

    $component .= '</p>
    </div>
    <div class="service">
        <i class="icon fas fa-user-plus"></i>
        <h2>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Inscription';
            break;
        case "en":
        default:
            $component .= 'Registration';
            break;
    }

    $component .= '</h2>
        <p>';

    switch ($GLOBALS['LANG']) {
        case "fr":
            $component .= 'Inscrivez-vous, c\'est le moment !';
            break;
        case "en":
        default:
            $component .= 'Sign up, it\'s time!';
            break;
    }

    $component .= '</p>
    </div>
    </div>';

    // Imprimer le composant HTML
    echo $component;
}