<?php
include(".configs.php");

define('___app', path_FLIP_APP);
define('___main', path_FLIP_MAIN);

// Fonction de redirection en fonction de la langue du navigateur et de l'adresse IP
function redirectBasedOnLanguageAndIP() {
    // Liste des langues supportées et leur mapping vers les dossiers correspondants
    $supportedLanguages = [
        'fr' => path_FR,
        'en' => path_EN,
        // Ajoutez d'autres langues si nécessaire
    ];

    // Récupération de la langue du navigateur
    //$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $language = 'fr';

    // Vérification de la langue du navigateur dans les langues supportées
    if (array_key_exists($language, $supportedLanguages)) {
        $redirectFolder = $supportedLanguages[$language];
        $LANGUAGE_CHOSEN = $language; // Enregistrement de la langue choisie
        header("Location: " . ___main . "$redirectFolder");
        exit();
    }

    // Récupération de l'adresse IP de l'utilisateur
    $userIP = $_SERVER['REMOTE_ADDR'];

    // Logique de redirection basée sur l'adresse IP
    // Exemple : Si l'adresse IP est dans une plage spécifique, rediriger vers un dossier spécifique
    // if (condition) {
    //     header("Location: /specific_folder/");
    //     exit();
    // }

    // Redirection par défaut si aucune condition n'est satisfaite
    $default = path_EN; // Langue par défaut
    header("Location: " . ___main . "$default");
    exit();
}

// Appel de la fonction de redirection lors de l'accès à la page index.php
redirectBasedOnLanguageAndIP();
?>
