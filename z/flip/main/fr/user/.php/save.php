<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include('../../../../.paths.php');
include('../../../../lib/include/php/Database/DatabaseManager.php');

// Création d'une instance de DatabaseManager avec les informations de connexion
$databaseManager = new DatabaseManager($db_host, $db_user, $db_password);

// Sélection de la base de données appropriée
$databaseManager->selectDatabase('flipapp'); // Assurez-vous d'utiliser le bon nom de base de données

// Récupération de la connexion
$conn = $databaseManager->getConnection();

// Récupération des données envoyées par AJAX
$data = $_POST['switches'];

// Concaténation des valeurs en une chaîne de caractères
$switchesString = implode(',', $data);

// Insertion des données dans la base de données avec une valeur par défaut pour username
$stmt = $conn->prepare("INSERT INTO users (user_id, switches) VALUES (?, ?)");
$stmt->bind_param('is', $_SESSION['user_id'], $switchesString);
$stmt->execute();

echo "Données enregistrées avec succès dans la base de données.";
?>
