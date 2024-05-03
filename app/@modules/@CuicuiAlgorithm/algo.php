<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../.paths.php');
include('../../../../lib/include/php/Database/DatabaseManager.php');

try {
    // Index des bases de données dans le tableau database_configs
    define("ALGO_DB_INDEX", 2);
    define("FLIPAPP_DB_INDEX", 0);

    // Connexion à la base de données algo
    $algoManager = new DatabaseManager($db_host, $db_user, $db_password);
    $algoManager->initializeConnection(
        $database_configs[ALGO_DB_INDEX]['host'],
        $database_configs[ALGO_DB_INDEX]['user'],
        $database_configs[ALGO_DB_INDEX]['password'],
        $database_configs[ALGO_DB_INDEX]['name']
    );

    // Connexion à la base de données flipapp
    $flipappManager = new DatabaseManager($db_host, $db_user, $db_password);
    $flipappManager->initializeConnection(
        $database_configs[FLIPAPP_DB_INDEX]['host'],
        $database_configs[FLIPAPP_DB_INDEX]['user'],
        $database_configs[FLIPAPP_DB_INDEX]['password'],
        $database_configs[FLIPAPP_DB_INDEX]['name']
    );

    // Récupération des connexions
    $conn_algo = $algoManager->getConnection();
    $conn_flipapp = $flipappManager->getConnection();

    // Vérification de l'existence de la session user_id et email
    session_start();
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
        throw new Exception("Les informations de session sont manquantes.");
    }

    $userId = $_SESSION['user_id'];
    $userEmail = $_SESSION['email'];

    // Récupération des mots clés et titres de l'utilisateur (données) depuis la base de données algo
    $stmtUserData = $conn_algo->prepare("SELECT keywords, title FROM data WHERE user_id = ?");
    $stmtUserData->bind_param("i", $userId);
    $stmtUserData->execute();
    $stmtUserData->bind_result($userKeywords, $userTitle);

    $userData = [];
    while ($stmtUserData->fetch()) {
        $userData[] = [
            'keywords' => explode(',', $userKeywords),
            'title' => $userTitle,
        ];
    }
    $stmtUserData->close();

    // Algorithme de recommandation en utilisant la connexion à la base de données flipapp
    $recommendedTextsMap = array();
    $otherTextsMap = array();

    // Utilisation des alias pour simplifier l'accès aux colonnes dans le résultat
    foreach ($userData as $userItem) {
        $userKeywords = $userItem['keywords'];
        $userTitle = $userItem['title'];

        // Recherche dans la table 'texts' pour des correspondances avec les mots clés et le titre
        $stmtTexts = $conn_flipapp->prepare("SELECT texts.*, media.media_type, media.media_url, users.username
            FROM texts
            LEFT JOIN users ON texts.user_id = users.user_id
            LEFT JOIN media ON texts.text_id = media.text_id
            WHERE LOWER(texts.title) LIKE ? 
            OR LOWER(texts.category) LIKE ? 
            OR LOWER(texts.content) LIKE ? 
            OR texts.keywords LIKE ?");

        $likeTerm = "%" . implode("%", array_map('strtolower', $userKeywords)) . "%";
        $stmtTexts->bind_param("ssss", $likeTerm, $likeTerm, $likeTerm, $likeTerm);
        $stmtTexts->execute();
        $result = $stmtTexts->get_result();

        while ($row = $result->fetch_assoc()) {
            // Utilisation du titre comme clé pour vérifier les doublons
            $textKey = $row['title'];

            if (!isset($recommendedTextsMap[$textKey])) {
                $recommendedTextsMap[$textKey] = $row;
            }
        }

        $stmtTexts->close();
    }

    // Requête SQL pour récupérer les autres publications (en excluant les recommandations)
    // Vous devez adapter cette requête en fonction de votre structure de base de données
    $stmtOtherTexts = $conn_flipapp->prepare("SELECT texts.*, media.media_type, media.media_url, users.username
        FROM texts
        LEFT JOIN users ON texts.user_id = users.user_id
        LEFT JOIN media ON texts.text_id = media.text_id");

    $stmtOtherTexts->execute();
    $resultOther = $stmtOtherTexts->get_result();

    while ($rowOther = $resultOther->fetch_assoc()) {
        $otherTextsMap[] = $rowOther;
    }

    $stmtOtherTexts->close();


    // Mélanger les résultats si nécessaire
    shuffle($recommendedTextsMap);
    shuffle($otherTextsMap);

    // Fusionner les recommandations et les autres publications dans un seul tableau
    $allTexts = array_merge($recommendedTextsMap, $otherTextsMap);

    // Conversion du tableau associatif en tableau numérique
    $allTexts = array_values($allTexts);

    // Renvoi des résultats recommandés au format JSON
    header('Content-Type: application/json');
    echo json_encode($allTexts);

    $conn_algo->close();
    $conn_flipapp->close();
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
