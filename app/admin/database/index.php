<?php
    include '../../defs.functions.php';
    includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);
    session_start();

    if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"]) {
        $cuicui_manager = new CuicuiManager($database_configs, DATASET);
        $cuicui_sess = new CuicuiSession($cuicui_manager);
    }else{
        header('Location:' . $appdir['PATH_CUICUI_APP']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flip App | Database Config</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajouter une nouvelle configuration de base de données</h2>
        <form method="post" action="db_config.php">
            <label for="new_host">Hôte :</label>
            <input type="text" id="new_host" name="new_host" required>
            <label for="new_name">Nom de la base de données :</label>
            <input type="text" id="new_name" name="new_name" required>
            <label for="new_user">Nom de l'utilisateur :</label>
            <input type="text" id="new_user" name="new_user" required>
            <label for="new_password">Mot de passe :</label>
            <input type="password" id="new_password" name="new_password" required>
            <input type="submit" value="Ajouter">
        </form>

        <div class="container">
            <h2>Actions supplémentaires</h2>
            <ul>
                <li><a href="database.php">Se connecter au serveur de base de données</a></li>
                <li><a href="#">Enregistrer l'URL</a></li>
                <li><a href="#">Effectuer une recherche avec des filtres</a></li>
                <!-- Ajoutez d'autres liens au besoin -->
            </ul>
        </div>
    </div>
</body>
</html>
