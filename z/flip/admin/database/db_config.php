<?php
// Inclure la classe DatabaseManager
include('../../lib/include/php/Database/DatabaseManager.php');

// Créer une instance de DatabaseManager pour gérer la connexion à la base de données principale
$admin_conn = new DatabaseManager($db_host, $db_user, $db_password);

// Créer des bases de données initiales à partir du tableau initial
$admin_conn->createDatabasesFromConfig($database_configs);

// Traiter les données du formulaire si elles sont soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $new_host = $_POST['new_host'];
    $new_name = $_POST['new_name'];
    $new_user = $_POST['new_user'];
    $new_password = $_POST['new_password'];

    // Ajouter la nouvelle configuration de base de données au tableau
    $new_database_config = array(
        'host' => $new_host,
        'name' => $new_name,
        'user' => $new_user,
        'password' => $new_password
    );
    $database_configs[] = $new_database_config;

    // Créer la nouvelle base de données à partir du tableau mis à jour
    $admin_conn->createDatabasesFromConfig(array($new_database_config));

    // Rediriger vers la page db_config.php après avoir traité les données
    header("Location: index.php");
    exit(); // Arrêter l'exécution du script après la redirection
}

// Fermer la connexion à la base de données principale
$admin_conn->close();
?>
