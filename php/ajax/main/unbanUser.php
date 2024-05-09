<?php
    // Inclure le fichier CuicuiManager
    include '../../../app/defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

    // Vérifier si l'ID de l'utilisateur est présent dans la requête
    if(isset($_POST['userId'])) {
        // Récupérer l'ID de l'utilisateur depuis la requête
        $userId = $_POST['userId'];

        // Initialiser CuicuiManager
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);

        // Débannir l'utilisateur avec l'ID $userId
        $cuicui_manager->unbanUser($userId);

        // Envoyer une réponse au client (peut être utile pour le débogage)
        echo "Utilisateur débanni avec succès !";
    } else {
        // Si l'ID de l'utilisateur n'est pas présent dans la requête, renvoyer une réponse d'erreur
        echo "Erreur : ID de l'utilisateur manquant dans la requête !";
    }
?>
