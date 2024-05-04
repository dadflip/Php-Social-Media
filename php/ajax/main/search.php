<?php
// Inclusion des fichiers nécessaires
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

try {
    // Vérification de l'existence de la session et récupération de l'UID de l'utilisateur
    session_start();
    if (isset($_SESSION['UID'])) {

        $userId = $_SESSION['UID'];

        // Création d'une nouvelle instance de CuicuiManager et CuicuiSession
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        $cuicui_sess = new CuicuiSession($cuicui_manager);

        // Récupérer le terme de recherche depuis la barre de recherche
        $searchTerm = isset($_GET['q']) ? strtolower($_GET['q']) : '';

        // Vérifier si des filtres sont sélectionnés
        $filterList = isset($_GET['filters']) ? $_GET['filters'] : [];

        // Préparation de la clause WHERE dynamique en fonction des filtres sélectionnés
        $whereClause = "";
        $likeTerm = "%$searchTerm%"; // Ajouter le caractère joker '%' au début et à la fin du terme de recherche

        // Initialisation de la variable $conditions
        $conditions = [];

        // Vérifier si des filtres ont été sélectionnés
        if (!empty($filterList)) {
            $whereClause .= " WHERE ";

            // Ajouter les conditions pour chaque filtre sélectionné
            if (in_array('user', $filterList)) {
                $conditions[] = "LOWER(users.username) LIKE ?";
            }

            if (in_array('post', $filterList)) {
                $conditions[] = "LOWER(posts.title) LIKE ?";
                $conditions[] = "LOWER(posts.category) LIKE ?";
                $conditions[] = "LOWER(posts.text_content) LIKE ?";
                $conditions[] = "posts.tags LIKE ?";
            }

            if (in_array('media', $filterList)) {
                $conditions[] = "media.type = ?";
            }

            if (in_array('date', $filterList)) {
                $conditions[] = "posts.date = ?";
            }

            if (in_array('titre', $filterList)) {
                $conditions[] = "LOWER(posts.title) LIKE ?";
            }

            if (in_array('populaires', $filterList)) {
                // Ajoutez votre condition pour le filtre populaires ici
            }

            if (in_array('categorie', $filterList)) {
                $conditions[] = "LOWER(posts.category) LIKE ?";
            }

            if (in_array('contenu', $filterList)) {
                $conditions[] = "LOWER(posts.text_content) LIKE ?";
            }

            // Concaténer les conditions avec l'opérateur OR
            $whereClause .= implode(" OR ", $conditions);
        } else {
            $whereClause .= "LOWER(posts.text_content) LIKE ?";
        }

        // Préparation de la requête SQL avec la clause WHERE dynamique
        $query = "SELECT posts.*, media.type AS media_type, media.url AS media_url, users.username, users.profile_pic_url
                  FROM posts
                  LEFT JOIN users ON posts.users_uid = users.UID
                  LEFT JOIN media ON posts.media_id = media.media_id";
        $query .= " " . $whereClause;

        // Initialisation d'un tableau pour stocker les valeurs des conditions
        $values = array_fill(0, count($conditions), $likeTerm);

        // Exécution de la requête SQL avec les valeurs des conditions
        $res = $cuicui_manager->createRequest($query, str_repeat('s', count($conditions)), ...$values);


        // Vérification si la requête s'est exécutée avec succès
        if ($res === false) {
            throw new Exception("Erreur lors de l'exécution de la requête.");
        }

        // Initialisation d'un tableau pour stocker les résultats de recherche
        $searchResults = [];

        // Boucle à travers les résultats
        while ($row = $res->fetch_assoc()) {
            // Récupérer l'URL de l'image de profil
            $profilePicUrl = $GLOBALS["normalized_paths"]["PATH_IMG_DIR"] . $row['profile_pic_url'];
            $imgUrl = $GLOBALS["normalized_paths"]["PATH_IMG_DIR"] . $row['media_url'];

            // Vérifier si le fichier à cette URL existe
            if (file_exists($profilePicUrl)) {
                // Le fichier existe, utilisez l'URL de l'image de profil
                $profileImageUrl = $profilePicUrl;
            } else {
                // Le fichier n'existe pas, utilisez un placeholder
                $placeholderImageUrl = $GLOBALS['normalized_paths']['PATH_IMG_DIR'] . '/placeholder.png';
                $profileImageUrl = $placeholderImageUrl;
            }

            $row['media_url'] = $imgUrl;
            $row['profile_pic_url'] = $profileImageUrl;

            // Ajouter le résultat au tableau des résultats de recherche
            $searchResults[] = $row;
        }

        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($searchResults);

    } else {
        // Si la session UID n'est pas définie, récupérer tous les posts

        // Création d'une nouvelle instance de CuicuiManager
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);

        // Requête SQL pour sélectionner tous les textes avec leurs informations associées
        $query = "SELECT posts.*, media.type AS media_type, media.url AS media_url, users.username, users.profile_pic_url
                  FROM posts
                  LEFT JOIN users ON posts.users_uid = users.UID
                  LEFT JOIN media ON posts.media_id = media.media_id";

        // Exécution de la requête
        $res = $cuicui_manager->query($query);

        // Vérification si la requête s'est exécutée avec succès
        if ($res === false) {
            throw new Exception("Erreur lors de l'exécution de la requête.");
        }

        // Initialisation d'un tableau pour stocker les résultats de recherche
        $searchResults = [];

        // Boucle à travers les résultats
        while ($row = $res->fetch_assoc()) {
            // Récupérer l'URL de l'image de profil
            $profilePicUrl = $GLOBALS["normalized_paths"]["PATH_IMG_DIR"] . $row['profile_pic_url'];
            $imgUrl = $GLOBALS["normalized_paths"]["PATH_IMG_DIR"] . $row['media_url'];

            // Vérifier si le fichier à cette URL existe
            if (file_exists($profilePicUrl)) {
                // Le fichier existe, utilisez l'URL de l'image de profil
                $profileImageUrl = $profilePicUrl;
            } else {
                // Le fichier n'existe pas, utilisez un placeholder
                $placeholderImageUrl = $GLOBALS['normalized_paths']['PATH_IMG_DIR'] . '/placeholder.png';
                $profileImageUrl = $placeholderImageUrl;
            }

            $row['media_url'] = $imgUrl;
            $row['profile_pic_url'] = $profileImageUrl;

            // Ajouter le résultat au tableau des résultats de recherche
            $searchResults[] = $row;
        }

        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($searchResults);
    }

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}

?>
