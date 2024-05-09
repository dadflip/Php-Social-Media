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
        echo "<p class='error-message'>Erreur lors de la suppression du compte.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de suppression de compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        p {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #555;
        }

        form {
            text-align: center;
        }

        input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #c82333;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Confirmation de suppression de compte</h1>
        <p>Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
        <form method="post" action="#">
            <input type="hidden" name="confirm_delete" value="1">
            <input type="submit" value="Confirmer la suppression">
        </form>
        <a href="<?php echo $appdir['PATH_CUICUI_APP']; ?>">Annuler</a>
    </div>
</body>
</html>
