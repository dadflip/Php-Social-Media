<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté, le rediriger vers la page d'accueil s'il l'est
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: main.php");
    exit;
}

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier les identifiants de connexion (vous devrez implémenter votre propre logique de vérification des identifiants)
    $username = "admin"; // Exemple de nom d'utilisateur
    $password = "password"; // Exemple de mot de passe

    if ($_POST['username'] == $username && $_POST['password'] == $password) {
        // Authentification réussie, enregistrer l'état de connexion dans la session
        $_SESSION['admin_logged_in'] = true;

        // Rediriger vers la page d'accueil
        header("Location: main.php");
        exit;
    } else {
        // Identifiants incorrects, afficher un message d'erreur
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Flip App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
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

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion Administrateur</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Se connecter">
        </form>
        <?php if (isset($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>
    </div>
</body>
</html>
