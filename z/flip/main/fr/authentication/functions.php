<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../.configs.php');
include('../../../lib/include/php/Database/DatabaseManager.php');

//--------------------------- Functions ----------------------------------------

function createLoginCookie($userId, $username, $email, $databaseManager) {
    // Durée de vie du cookie (par exemple, 30 jours)
    $cookieLifetime = time() + 30 * 24 * 3600; // 30 jours

    // Générer une chaîne de cookie sécurisée aléatoire
    $cookieValue = generateSecureCookieValue();

    // Enregistrer le cookie
    setcookie('login_cookie', $cookieValue, $cookieLifetime, '/', '', true, true);

    // Enregistrer les informations d'identification dans la base de données ou dans un autre endroit sécurisé
    // Ceci est un exemple, assurez-vous de sécuriser correctement ces informations
    saveLoginCookieInfo($userId, $cookieValue, $databaseManager);

    // Définir les variables de session
    $_SESSION['email'] = $email;
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['userLoggedIn'] = true;

}

function generateSecureCookieValue() {
    // Générer une chaîne de caractères aléatoire pour sécuriser le cookie
    return bin2hex(random_bytes(32)); // 32 bytes pour une valeur sécurisée
}

function saveLoginCookieInfo($userId, $cookieValue, $databaseManager) {
    // Enregistrer les informations d'identification du cookie dans la base de données ou dans un autre endroit sécurisé
    // Assurez-vous de sécuriser correctement ces informations
    // C'est juste un exemple, vous devrez adapter cette fonction à votre base de données

    // Préparer la requête d'insertion
    $stmt = $databaseManager->getAdminConnection()->prepare("INSERT INTO login (user_id, cookie_value) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $cookieValue);

    // Exécuter la requête d'insertion
    $stmt->execute();

    // Fermer la requête
    $stmt->close();
}

// -------------------------------------------------------------
?>
