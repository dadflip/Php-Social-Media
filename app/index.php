<?php
require_once __DIR__.'/defs.functions.php';

// Path to the folder of the corresponding language
$path = (determineLanguage() == 'fr') ? $GLOBALS['normalized_paths']['PATH_FR'] :  $GLOBALS['normalized_paths']['PATH_EN'];

// Redirect to the folder of the corresponding language
header('Location: ' . $path);
exit();