<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Redirector</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 800px;
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
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 10px 20px;
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

        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 10px;
            text-align: center;
        }

        a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Redirection vers les Gestionnaires de Bases de Données</h2>
        
        <!-- Formulaire pour saisir l'URL de la base de données -->
        <form method="get" action="">
            <label for="db_url">URL de la base de données :</label>
            <input type="text" id="db_url" name="db_url" required>
            <input type="submit" value="Rediriger">
        </form>

        <?php
        // Tableau associatif des gestionnaires de bases de données préenregistrés avec leur URL
        $db_managers = [
            "phpMyAdmin" => "https://www.phpmyadmin.net/",
            "pgAdmin" => "https://www.pgadmin.org/"
            // Ajoutez d'autres gestionnaires de bases de données au besoin
        ];

        // Affichage des cadres cliquables pour les gestionnaires de bases de données préenregistrés
        echo "<h3>Gestionnaires de Bases de Données Préenregistrés</h3>";
        echo "<ul>";
        foreach ($db_managers as $manager => $url) {
            echo "<li><a href='$url' target='_blank'>$manager</a></li>";
        }
        echo "</ul>";

        // Vérification et ajout de "http://" à l'URL si nécessaire
        if (isset($_GET['db_url'])) {
            $db_url = filter_input(INPUT_GET, 'db_url', FILTER_SANITIZE_URL);
            if ($db_url) {
                if (!preg_match("~^(?:f|ht)tps?://~i", $db_url)) {
                    $db_url = "http://" . $db_url;
                }
                echo "<script>window.location.href = '$db_url';</script>";
            }
        }
        
        ?>

    </div>
</body>
</html>
