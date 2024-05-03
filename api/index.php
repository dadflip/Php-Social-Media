<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <!-- Inclure Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- CSS pour la mise en page -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .card:hover {
            background-color: #f4f4f4;
        }
        .card i {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .card h2 {
            margin: 0;
            font-size: 24px;
        }
        .card p {
            margin: 10px 0 0;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card" onclick="redirectTo('../')">
            <h2>Retour</h2>
            <p>Revenez à la page d'accueil</p>
        </div>
        <div class="card" onclick="redirectTo('@cuicui-posts.php')">
            <i class="fas fa-newspaper"></i>
            <h2>Publications</h2>
            <p>Accédez aux publications et effectuez des actions de filtrage.</p>
        </div>
        <div class="card" onclick="redirectTo('@cuicui-notifs.php')">
            <i class="fas fa-bell"></i>
            <h2>Notifications</h2>
            <p>Consultez et gérez les notifications.</p>
        </div>
        <!-- Ajoutez d'autres cartes pour d'autres fonctionnalités -->
    </div>

    <!-- JavaScript pour la redirection -->
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
