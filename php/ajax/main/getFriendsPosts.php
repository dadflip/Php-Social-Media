<?php 
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

$cuicui_manager = new CuicuiManager($database_configs, DATASET);
$cuicui_sess = new CuicuiSession($cuicui_manager);

try {
    $responseData = array(); // Initialiser un tableau pour stocker les données de réponse

    if(isset($_POST['friendId'])){
        $user_info = $cuicui_manager->getUserInfo($_POST['friendId']);

        if(isset($user_info)) {
            $user_posts = $cuicui_manager->getUserPosts($user_info->getID());
        }

        if ($user_posts != NULL) {
            $responseData['success'] = true; // Indiquer que la récupération des publications a réussi
            $responseData['posts'] = $user_posts; // Stocker les publications dans les données de réponse
        } else {
            $responseData['success'] = true; // Indiquer que la récupération des publications a réussi, même si aucun message n'a été trouvé
            $responseData['message'] = "Cet utilisateur n'a pas encore publié de messages."; // Ajouter un message pour indiquer qu'aucune publication n'a été trouvée
        }
    } else {
        $responseData['success'] = false; // Indiquer que la requête ne contient pas d'ID utilisateur
        $responseData['message'] = "ID utilisateur non fourni."; // Ajouter un message d'erreur
    }

    // Retourner les données de réponse au format JSON
    echo json_encode($responseData);
} catch (Exception $e) {
    // Gérer les exceptions
    $responseData['success'] = false; // Indiquer que l'opération a échoué
    $responseData['message'] = "Erreur: " . $e->getMessage(); // Ajouter un message d'erreur avec le message d'exception
    echo json_encode($responseData); // Retourner les données de réponse au format JSON
}
