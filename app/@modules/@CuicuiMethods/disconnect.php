<?php
include '../../defs.functions.php';

// Démarrer la session
session_start();

// Effacer toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger vers la page d'accueil ou une autre page appropriée
header('Location: ' . $appdir['PATH_CUICUI_APP']);
exit(); // Assure que le script s'arrête après la redirection
?>
