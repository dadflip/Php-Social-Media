<?php
    // Inclure le fichier CuicuiManager
    include '../../../app/defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

    // Vérifier si l'ID du post est présent dans la requête
    if(isset($_POST['postId'])) {
        // Récupérer l'ID du post depuis la requête
        $postId = $_POST['postId'];

        // Initialiser CuicuiManager
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);

        // Marquer le post avec l'ID $postId comme non-sensible
        $cuicui_manager->markAsNonSensitive($postId);

        // Envoyer une réponse au client (peut être utile pour le débogage)
        echo "Post marqué comme non-sensible avec succès !";
    } else {
        // Si l'ID du post n'est pas présent dans la requête, renvoyer une réponse d'erreur
        echo "Erreur : ID du post manquant dans la requête !";
    }
?>
