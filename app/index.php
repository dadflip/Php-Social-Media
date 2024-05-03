<?php
require __DIR__.'/defs.functions.php';

// Chemin vers le dossier de la langue correspondante
$path = (determineLanguage() == 'fr') ? $GLOBALS['normalized_paths']['PATH_FR'] :  $GLOBALS['normalized_paths']['PATH_EN'];

// Rediriger vers le dossier de la langue correspondante
header('Location: ' . $path);
exit();

