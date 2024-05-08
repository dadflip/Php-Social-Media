<?php
include '../../defs.functions.php';

// Démarrer la session
session_start();

// Effacer toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banni</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: black;
        }

        a {
            text-decoration: none;
        }

        .banned-message {
            padding: 20px;
            background-color: red;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <a href="<?php echo $appdir['PATH_CUICUI_APP']; ?>">
        <div class="banned-message">
            <h1>You've been banned !</h1>
        </div>
    </a>
</body>
</html>
