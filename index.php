<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #181818;
        }
        .container {
            display: grid;
            width: 500px;
            height: 500px;
            margin: 100px auto;
            background-color: #ffffff00;
            padding: 20px;
            border: 1px solid #ffffff42;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            align-items: stretch;
        }
        h1 {
            text-align: center;
        }
        .button {
            position: relative;
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            padding: 20px;
            background-color: #007bff;
        }
        .button.admin {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="./app" class="button">Accéder à l'application</a>
        <a href="./app/admin" class="button admin">Se connecter en tant qu'administrateur</a>
    </div>
</body>
</html>
