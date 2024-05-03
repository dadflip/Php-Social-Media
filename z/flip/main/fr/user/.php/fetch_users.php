<?php
include('../../../../.paths.php');
include('../../../../lib/include/php/Database/DatabaseManager.php');

// Instancier un objet de la classe DatabaseManager avec les informations de connexion
$databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

// Affecter le premier sous-tableau de $database_configs à $db_flipapp
$db_flipapp = $database_configs[0];

// Initialiser la connexion à la base de données flipapp
$databaseManager->initializeConnection($db_flipapp['host'], $db_flipapp['user'], $db_flipapp['password'], $db_flipapp['name']);

// Sélectionner la base de données
$databaseManager->selectDatabase($db_flipapp['name']);

// Requête SQL pour sélectionner les utilisateurs
$query = "SELECT user_id, username FROM users";
$result = $databaseManager->query($query);

if ($result) {
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode(array('error' => 'Error fetching users'));
}
?>
