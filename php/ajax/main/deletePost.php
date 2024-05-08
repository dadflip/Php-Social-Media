<?php
// Inclure les fichiers nécessaires
include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

// Créer une instance de CuicuiManager
$cuicui_manager = new CuicuiManager($database_configs, DATASET);

try {
    // Vérifier si l'utilisateur est un administrateur
    if (isset($_POST["adminID"]) && $_POST["adminID"] != $_SESSION["UID"]) {
        // Supprimer le post par l'administrateur
        $cuicui_manager->deletePost($_POST["postID"], $_POST["adminID"]);
    } else {
        // Supprimer le post par l'utilisateur
        $cuicui_manager->deletePostByUser($_POST["postID"]);
    }

    // Retourner un succès avec JSON encode
    echo json_encode(["success" => true]);
} catch (NotAnAdminException $ex) {
    // Retourner un message d'erreur si l'utilisateur n'est pas un administrateur
    echo json_encode(["success" => false, "message" => $ex->getMessage()]);
}
