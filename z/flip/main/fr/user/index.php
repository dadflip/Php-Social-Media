<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include('../authentication/functions.php');

    // Instancier un objet de la classe DatabaseManager avec les informations de connexion
    $databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

    // Affecter le premier sous-tableau de $database_configs à $db_flipapp
    $db_flipapp = $database_configs[0];

    // Initialiser la connexion à la base de données flipapp
    $databaseManager->initializeConnection($db_flipapp['host'], $db_flipapp['user'], $db_flipapp['password'], $db_flipapp['name']);

    // Sélectionner la base de données
    $databaseManager->selectDatabase($db_flipapp['name']);

    if(isset($_SESSION['user_id'])){
        createLoginCookie($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email'], $databaseManager);
    }else{
        header('Location:' . path_FLIP_APP);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome on Dadflip App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../_assets/css/index.css">
</head>

<body>
    
    <div class="loader" id="loader">
        <img src="../_assets/img/logo/dadflip_green_white_rmbg.png" alt="FLIP. APP" width="50%"><br>
        <i class="fas fa-circle-notch fa-spin"></i>
        <p>
            🌐 <!-- Globe emoji -->
            🚀 <!-- Rocket emoji -->
            🔄 <!-- Refresh emoji -->
            🌟 <!-- Star emoji -->
            🎉 <!-- Celebration emoji -->
            🤖 <!-- Robot emoji -->
        </p>
    </div>

    <script>
        // Afficher le loader après 1 seconde
        setTimeout(function() {

            document.getElementById("loader").style.display = "block";
            // Rediriger vers une autre page après 1 seconde supplémentaire

            setTimeout(function() {
                window.location.href = "app.php";
            }, 500);
        }, 200);
    </script>
</body>

</html>
