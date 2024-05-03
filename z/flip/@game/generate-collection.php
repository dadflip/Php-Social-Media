<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure les fichiers nécessaires
include('../.paths.php'); // Assurez-vous de spécifier le chemin correct
include('../lib/include/php/Database/DatabaseManager.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données JSON envoyées depuis le formulaire
    $data = file_get_contents("php://input");

    // Dossier de destination pour la collection de cartes
    $destinationFolder = 'collections/';

    // Créer le dossier s'il n'existe pas
    if (!file_exists($destinationFolder)) {
        if (!mkdir($destinationFolder, 0777, true)) {
            // Si la création du dossier échoue, retourner une erreur
            echo json_encode(['error' => 'Impossible de créer le dossier.']);
            exit;
        }
    }

    // Générer un nom de fichier unique pour la collection
    $filename = 'collection_' . uniqid() . '.json';

    // Chemin complet du fichier JSON dans le dossier de destination
    $filePath = $destinationFolder . $filename;

    // Enregistrer les données JSON dans le fichier
    if (!file_put_contents($filePath, $data)) {
        // Si l'écriture dans le fichier échoue, retourner une erreur
        echo json_encode(['error' => 'Impossible d\'écrire dans le fichier.']);
        exit;
    }

    // Retourner le nom du fichier JSON généré
    echo json_encode(['filename' => $filename]);
} else {
    // Si la requête n'est pas de type POST, retourner une erreur
    echo json_encode(['error' => 'La requête doit être de type POST.']);
}
?>
