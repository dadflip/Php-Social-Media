<?php
// Fonction pour imprimer le composant HTML de la page d'accueil
function printHomePageComponent()
{
    // Composant HTML
    $component = '
    <div class="header animate__animated animate__fadeInDown">
        <div class="container">
            <h1>CuiCui App</h1>
            <p>Chat, Explore and Discover</p>
            <a href="./mainpage.php" class="cta-button">Open</a>
        </div>
    </div>

    <div class="container services animate__animated animate__fadeInUp">
    <div class="service">
        <i class="icon fas fa-home"></i>
        <h2>Accueil</h2>
        <p>Rendez-vous sur la page d\'accueil</p>
    </div>
    <div class="service">
        <i class="icon fas fa-sign-in-alt"></i>
        <h2>Connexion</h2>
        <p>Connectez-vous pour profiter au maximum !</p>
    </div>
    <div class="service">
        <i class="icon fas fa-user-plus"></i>
        <h2>Inscription</h2>
        <p>Inscrivez-vous, c\'est le moment !</p>
    </div>
    </div>';

    // Imprimer le composant HTML
    echo $component;
}
