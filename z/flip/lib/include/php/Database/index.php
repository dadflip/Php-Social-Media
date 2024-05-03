<?php
// Obtenez le chemin du répertoire racine de l'application
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('DatabaseInitializer');

// Appeler la méthode pour initialiser les bases de données
DatabaseManager::createDatabasesFromConfig($database_configs);
DatabaseInitializer::initializeDatabases($database_configs);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Config Databases</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
</head>

<body>
    
    <div class="loader" id="loader">
        <img src="_assets/.." alt="FLIP. APP" width="50%"><br>
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
                var path_app = <?php echo json_encode("$main_path"); ?>;
                window.location.href = path_app;
            }, 500);
        }, 200);
    </script>
</body>

</html>
