<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Liste des pages disponibles
$pages = ['home', 'about', 'contact'];

// Vérifie si la page demandée est valide, sinon redirige vers la page d'accueil
if (!in_array($page, $pages)) {
    $page = 'home';
}

// Inclut la page demandée
include "pages/$page.php";
?>
