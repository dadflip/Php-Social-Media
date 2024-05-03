<?php 
    include 'app/defs.functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        .error-code {
            font-size: 100px;
            color: #333;
            margin: 0;
        }
        .error-message {
            font-size: 24px;
            color: #555;
            margin: 10px 0 30px;
        }
        .button {
            display: inline-block;
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #45a049;
        }
        .illustration {
            max-width: 100%;
            height: auto;
            animation: bounce 2s infinite alternate;
        }
        @keyframes bounce {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="error-code">404</h1>
        <p class="error-message">Oups! La page que vous recherchez est introuvable.</p>
        <a href="<?php echo $GLOBALS["normalized_paths"]["PATH_CUICUI_APP"] ?>" class="button">Retourner à la page d'accueil</a>
        <div>
            <img src="<?php echo $GLOBALS["normalized_paths"]["PATH_IMG_DIR"] . "/error404_img.png" ?>" alt="Error 404 Illustration" class="illustration">
        </div>
    </div>
</body>
</html>
