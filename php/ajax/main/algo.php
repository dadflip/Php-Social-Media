<?php
// Inclusion des fichiers nécessaires
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
$folder = $GLOBALS["normalized_paths"]["PATH_IMG_DIR"];

try {
    // Vérification de l'existence de la session et récupération de l'UID de l'utilisateur
    session_start();
    if (isset($_SESSION['UID'])) {

        $userId = $_SESSION['UID'];

        // Création d'une nouvelle instance de CuicuiManager et CuicuiSession
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        $cuicui_sess = new CuicuiSession($cuicui_manager);

        // Requête SQL pour sélectionner les tags et les titres des messages de l'utilisateur
        $query = "SELECT tags, title FROM posts WHERE users_uid = ?";
        $res = $cuicui_manager->createRequest($query, "i", $userId);

        // Vérification si la requête s'est exécutée avec succès
        if ($res === false) {
            throw new Exception("Erreur lors de l'exécution de la requête.");
        }

        // Récupération des données à partir du résultat de la requête
        $userData = [];
        while ($row = $res->fetch_assoc()) {
            $userKeywords = $row['tags'];
            $userTitle = $row['title'];
            $userData[] = [
                'keywords' => explode(',', $userKeywords),
                'title' => $userTitle,
            ];
        }
        $res->close(); // Fermer le résultat de la requête

        // Initialisation des tableaux pour stocker les résultats
        $recommendedTextsMap = [];
        $otherTextsMap = [];
        $recommendedPostIds = []; // Tableau pour stocker les identifiants des publications recommandées

        // Traitement des recommandations
        foreach ($userData as $userItem) {
            $userKeywords = $userItem['keywords'];
            $userTitle = $userItem['title'];

            // Requête SQL pour récupérer les publications recommandées en tenant compte des données de navigation de l'utilisateur
            $query = "SELECT posts.*, media.type AS media_type, media.url AS media_url, users.username, users.profile_pic_url
                FROM posts
                LEFT JOIN users ON posts.users_uid = users.UID
                LEFT JOIN media ON posts.media_id = media.media_id
                LEFT JOIN data ON data.posts_id = posts.textId
                WHERE LOWER(posts.title) LIKE ? 
                OR LOWER(posts.category) LIKE ? 
                OR LOWER(posts.text_content) LIKE ? 
                OR posts.tags LIKE ?
                AND data.users_uid = ?
                AND data.browser_name = ?"; // Ajoutez d'autres conditions en fonction des données de navigation que vous souhaitez utiliser
            $likeTerm = "%" . implode("%", array_map('strtolower', $userKeywords)) . "%";
            $res = $cuicui_manager->createRequest($query, "ssssis", $likeTerm, $likeTerm, $likeTerm, $likeTerm, $userId, $userItem['browser_name']);

            // Vérification si la requête s'est exécutée avec succès
            if ($res === false) {
                throw new Exception("Erreur lors de l'exécution de la requête.");
            }

            // Récupération des résultats
            while ($row = $res->fetch_assoc()) {
                $postKey = $row['textId'];
                if (!isset($recommendedTextsMap[$postKey])) {
                    // Ajouter le post à la liste des recommandations
                    $recommendedTextsMap[$postKey] = $row;
                    // Ajouter l'identifiant de la publication recommandée au tableau
                    $recommendedPostIds[] = $postKey;
                }
            }
            $res->close();
        }


        if(count($recommendedPostIds) > 0) {
            // Construction de la sous-requête pour sélectionner les identifiants des publications recommandées
            $subquery = "SELECT textId FROM posts WHERE textId IN (" . rtrim(str_repeat('?,', count($recommendedPostIds)), ',') . ")";
        
            // Requête principale pour récupérer les autres publications
            $query = "SELECT posts.*, media.type AS media_type, media.url AS media_url, users.username, users.profile_pic_url
                    FROM posts
                    LEFT JOIN users ON posts.users_uid = users.UID
                    LEFT JOIN media ON posts.textId = media.posts_text_id
                    WHERE posts.textId NOT IN ($subquery)";
        
            // Exécution de la requête avec les identifiants des publications recommandées comme arguments
            $res = $cuicui_manager->createRequest($query, str_repeat('s', count($recommendedPostIds)), ...$recommendedPostIds);
            if ($res === false) {
                throw new Exception("Erreur lors de l'exécution de la requête.");
            }
        
            // Récupération des autres publications
            $otherTextsMap = [];
            while ($rowOther = $res->fetch_assoc()) {
                $otherTextsMap[] = $rowOther;
            }
            $res->close();
        } else {
            // Requête pour sélectionner tous les posts
            $query = "SELECT posts.*, media.type AS media_type, media.url AS media_url, users.username, users.profile_pic_url
                    FROM posts
                    LEFT JOIN users ON posts.users_uid = users.UID
                    LEFT JOIN media ON posts.textId = media.posts_text_id";
        
            // Exécution de la requête pour récupérer tous les posts
            $res = $cuicui_manager->query($query);
            if ($res === false) {
                throw new Exception("Erreur lors de l'exécution de la requête.");
            }
        
            // Récupération des autres publications
            $otherTextsMap = [];
            while ($rowOther = $res->fetch_assoc()) {
                $otherTextsMap[] = $rowOther;
            }
            $res->close();
        }
        

        // Fusionner les recommandations et les autres publications dans un seul tableau
        $allTexts = array_merge($recommendedTextsMap, $otherTextsMap);

        // Parcourir tous les textes
        foreach ($allTexts as &$text) {
            $postId = $text['textId'];
            
            // Récupérer tous les commentaires associés à ce post
            $commentsQuery1 = "SELECT * FROM comments WHERE parent_id = ?";
            $commentsRes1 = $cuicui_manager->createRequest($commentsQuery1, "s", $postId);

            // Récupérer les commentaires qui n'ont pas été récupérés avec la requête précédente
            $commentsQuery2 = "SELECT * FROM comments WHERE parent_id != ?";
            $commentsRes2 = $cuicui_manager->createRequest($commentsQuery2, "s", $postId);

            if ($commentsRes1 === false || $commentsRes2 === false) {
                throw new Exception("Erreur lors de la récupération des commentaires.");
            }

            // Récupérer tous les commentaires en tant que tableau associatif
            $comments = [];
            while ($commentRow = $commentsRes1->fetch_assoc()) {
                $comments[] = $commentRow;
            }

            // Récupérer tous les commentaires en tant que tableau associatif
            $subcomments = [];
            while ($commentRow = $commentsRes2->fetch_assoc()) {
                $commentRow['replies'] = [];
                $subcomments[] = $commentRow;
            }

            // Construire la hiérarchie des commentaires
            buildCommentHierarchy($comments, $subcomments);

            // Ajouter les commentaires à la structure du post
            $text['comments'] = $comments;
        }

        // La variable $allTexts contient maintenant tous les textes avec leurs commentaires associés

        shuffle($allTexts);

        // Initialisation d'un tableau pour stocker les résultats avec les URL construites
        $allTextsWithURL = [];

        // Boucle à travers tous les textes pour construire les URLs
        foreach ($allTexts as &$text) {
            $textId = $text['textId'];
            $mediaUrl = $text['media_url'];
            $imgUrl = $mediaUrl;

            // Vérifier si le chemin $folder est déjà inclus dans l'URL
            if (strpos($mediaUrl, $folder) !== 0) {
                // Le chemin $folder n'est pas déjà inclus, le concaténer à l'URL
                $imgUrl = $folder . $mediaUrl;
            }
            
            // Récupérer l'URL de l'image de profil
            $profilePicUrl = $folder . $text['profile_pic_url'];

            // Vérifier si le fichier à cette URL existe
            //if (file_exists($profilePicUrl)) {
                // Le fichier existe, utilisez l'URL de l'image de profil
                $profileImageUrl = $profilePicUrl;
            //} else {
                // Le fichier n'existe pas, utilisez un placeholder
            //    $placeholderImageUrl = $folder . '/placeholder.png';
            //    $profileImageUrl = $placeholderImageUrl;
            //}

            // Mettre à jour les valeurs des URL dans le tableau $allTexts
            $text['media_url'] = $imgUrl;
            $text['profile_pic_url'] = $profileImageUrl;
            $allTextsWithURL[] = $text;
        }


        // Retourner les données au format JSON avec les URLs construites
        header('Content-Type: application/json');
        echo json_encode($allTextsWithURL);
    } else {
        // throw new Exception("Session UID non définie.");
        // Création d'une nouvelle instance de CuicuiManager
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);

        // Requête SQL pour sélectionner tous les textes avec leurs informations associées
        $query = "SELECT posts.*, media.type AS media_type, media.url AS media_url, users.username, users.profile_pic_url
                  FROM posts
                  LEFT JOIN users ON posts.users_uid = users.UID
                  LEFT JOIN media ON posts.textId = media.posts_text_id";

        // Exécution de la requête
        $res = $cuicui_manager->query($query);

        // Vérification si la requête s'est exécutée avec succès
        if ($res === false) {
            throw new Exception("Erreur lors de l'exécution de la requête.");
        }

        // Récupération des données à partir du résultat de la requête
        $allTextsWithURL = [];
        
        // Boucle à travers les résultats de la requête
        while ($row = $res->fetch_assoc()) {
            $mediaUrl = $row['media_url'];
            $imgUrl = $mediaUrl;

            // Vérifier si le chemin $folder est déjà inclus dans l'URL
            if (strpos($mediaUrl, $folder) !== 0) {
                // Le chemin $folder n'est pas déjà inclus, le concaténer à l'URL
                $imgUrl = $folder . $mediaUrl;
            }
            
            // Récupérer l'URL de l'image de profil
            $profilePicUrl = $folder . $row['profile_pic_url'];

            // Vérifier si le fichier à cette URL existe
            //if (file_exists($profilePicUrl)) {
                // Le fichier existe, utilisez l'URL de l'image de profil
                $profileImageUrl = $profilePicUrl;
            //} else {
                // Le fichier n'existe pas, utilisez un placeholder
            //    $placeholderImageUrl = $folder . '/placeholder.png'; // Remplacez cela par le chemin vers votre placeholder
            //    $profileImageUrl = $placeholderImageUrl;
            //}

            // Mettre à jour les valeurs des URL dans le tableau $allTextsWithURL
            $row['media_url'] = $imgUrl;
            $row['profile_pic_url'] = $profileImageUrl;
            $allTextsWithURL[] = $row;
        }

        $res->close(); // Fermer le résultat de la requête

        // Retourner les données au format JSON avec les URLs construites
        header('Content-Type: application/json');
        echo json_encode($allTextsWithURL);
    }

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}


// Fonction pour construire la hiérarchie des commentaires
function buildCommentHierarchy(&$comments, $subcomments) {
    foreach ($comments as &$parentComment) {
        // Trouver le parent dans les commentaires précédents
        $parentCommentId = $parentComment['comment_id'];
        
        foreach ($subcomments as $subcommentKey => $subcomment) {
            if ($subcomment['parent_id'] === $parentCommentId) {
                // Ajouter le commentaire comme sous-commentaire
                $parentComment['replies'][] = $subcomment;
                // Retirer le sous-commentaire du tableau $subcomments
                unset($subcomments[$subcommentKey]);
                // Appel récursif pour traiter les réponses aux réponses
                buildCommentHierarchy($parentComment['replies'], $subcomments);
            }
        }
    }
}