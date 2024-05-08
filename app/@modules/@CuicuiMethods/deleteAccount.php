<?php
    // Inclure le fichier contenant la définition de la fonction deleteAccount
    include '../../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
        // Créer une instance du gestionnaire CuicuiManager
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);

        // Supprimer le compte
        if ($cuicui_manager->deleteAccount($_SESSION["UID"])) {
            // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
            header('Location: ' . $appdir['PATH_MODULES'] . $phpfile['disconnect']);
            exit();
        } else {
            // Gérer les erreurs de suppression du compte, si nécessaire
            echo "Erreur lors de la suppression du compte.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de suppression de compte</title>
</head>
<body>
    <h1>Confirmation de suppression de compte</h1>
    <p>Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
    <form method="post" action="delete_account.php">
        <input type="hidden" name="confirm_delete" value="1">
        <input type="submit" value="Confirmer la suppression ?">
    </form>
    <a href="<?php echo $appdir['PATH_CUICUI_APP']; ?>">Annuler</a>
</body>
</html>
