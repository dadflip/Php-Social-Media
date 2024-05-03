<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include('../../.configs.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flip App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="_assets/css/index.css">
</head>

<body>
    
    <div class="loader" id="loader">
        <img src="_assets/img/logo/dadflip_green_white_rmbg.png" alt="FLIP. APP" width="50%"><br>
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
                var path_app = <?php echo json_encode('authentication/'); ?>;
                console.log(path_app);
                window.location.href = path_app;
            }, 1000);
        }, 200);
    </script>
</body>

</html>
