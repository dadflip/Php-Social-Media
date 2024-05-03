<?php
// Inclure le fichier contenant la définition de la classe DatabaseManager
include('DatabaseManager.php');

class DatabaseInitializer {
    // Méthode statique pour initialiser les bases de données et créer les tables correspondantes
    public static function initializeDatabases($database_configs) {
        // Instancier un objet de la classe DatabaseManager
        $databaseManager = new DatabaseManager();

        // Parcourir le tableau $database_configs pour initialiser chaque base de données et créer les tables correspondantes
        foreach ($database_configs as $config) {
            // Initialiser la connexion à la base de données spécifique
            $databaseManager->initializeConnection($config['host'], $config['user'], $config['password'], $config['name']);

            // Chemin vers le fichier SQL
            $sql_file_path = "sql_tables/{$config['name']}.sql";

            // Lire le contenu du fichier SQL
            $sql_script = file_get_contents($sql_file_path);

            // Exécuter le script SQL
            if ($databaseManager->query($sql_script)) {
                echo "Script SQL pour {$config['name']} exécuté avec succès.";
            } else {
                echo "Erreur lors de l'exécution du script SQL pour {$config['name']} : " . $databaseManager->error;
            }

            // Fermer la connexion à la base de données
            $databaseManager->closeConnection();
        }
    }
}
?>
