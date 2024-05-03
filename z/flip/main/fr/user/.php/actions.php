<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

try {

    // Créer une instance de CuicuiManager
    $cuicui_manager = new CuicuiManager($database_configs, DATASET);

    session_start();
    $userId = $_SESSION['user_id'];
    $userEmail = $_SESSION['email'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = isset($_POST['action']) ? $_POST['action'] : null;
        $data = isset($_POST['data']) ? $_POST['data'] : null;

        switch ($action) {
            case 'envoyer_commentaire':
                // Logique pour envoyer un commentaire
                envoyerCommentaire($data, $cuicui_manager, $userId);
                break;

            case 'like':
                // Logique pour liker
                liker($data, $cuicui_manager, $userId);
                break;

            case 'dislike':
                // Logique pour disliker
                disliker($data, $cuicui_manager, $userId);
                break;

            case 'mettre_en_favori':
                // Logique pour mettre en favori
                mettreEnFavori($data, $cuicui_manager);
                break;

            case 'faire_un_don':
                // Logique pour faire un don
                faireUnDon($data, $cuicui_manager);
                break;

            default:
                // Action non reconnue
                echo json_encode(['error' => 'Action non reconnue']);
        }
    }
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}

function envoyerCommentaire($data, $cuicui_manager, $userId)
{
    if ($_POST['action'] == 'envoyer_commentaire') {
        $textId = $_POST['data']['textId'];
        $commentaire = $_POST['data']['commentaire'];

        // Insérer le commentaire dans la base de données
        $cuicui_manager->insertComment($userId, $textId, $commentaire);

        echo json_encode(['success' => true]);
        exit();
    } else {
        echo json_encode(['error' => 'Données manquantes']);
    }
}

function liker($data, $cuicui_manager, $userId)
{
    if (isset($data['textId'])) {
        $textId = $data['textId'];

        // Mettre à jour le compteur de likes dans la base de données
        $cuicui_manager->likeText($textId);

        // Ajoutez le like à la table
        $cuicui_manager->addLike($userId, $textId, "like");

        echo json_encode(['success' => 'Like ajouté avec succès']);
    } else {
        echo json_encode(['error' => 'Données manquantes']);
    }
}

function disliker($data, $cuicui_manager, $userId)
{
    if (isset($data['textId'])) {
        $textId = $data['textId'];

        // Mettre à jour le compteur de dislikes dans la base de données
        $cuicui_manager->dislikeText($textId);

        // Ajoutez le dislike à la table
        $cuicui_manager->addLike($userId, $textId, "dislike");

        echo json_encode(['success' => 'Dislike ajouté avec succès']);
    } else {
        echo json_encode(['error' => 'Données manquantes']);
    }
}

function mettreEnFavori($data, $cuicui_manager)
{
    // Logique pour mettre en favori
}

function faireUnDon($data, $cuicui_manager)
{
    // Logique pour faire un don
}
