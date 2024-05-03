<?php

// Connection à la base de données MySQL
$db_host = "localhost"; // Hôte de la base de données
$db_user = "admin"; // Utilisateur de la base de données
$db_password = "Dk2021lh!M1083"; // Mot de passe de l'utilisateur de la base de données

// Configuration de base des bases de données
$database_configs = array(
    array(
        'host' => 'localhost',
        'name' => 'flipapp',
        'user' => 'flip',
        'password' => 'abcd1234'
    ),
    array(
        'host' => 'localhost',
        'name' => 'flip_apps',
        'user' => 'flip_apps',
        'password' => 'abcd1234'
    ),
    array(
        'host' => 'localhost',
        'name' => 'algo',
        'user' => 'dataAccess',
        'password' => 'abcd1234'
    )
);

class DatabaseManager {
    protected $admin_conn;
    protected $conn;

    public function getAdminConnection() {
        return $this->admin_conn;
    }

    public function getConnection() {
        return $this->conn;
    }



    // Constructeur pour établir la connexion à la base de données
    public function __construct($host, $user, $password) {
        $this->admin_conn = new mysqli($host, $user, $password);

        // Vérification de la connexion
        if ($this->admin_conn->connect_error) {
            die("Connection to database failed: " . $this->admin_conn->connect_error);
        }
    }

    // Méthode pour sélectionner une base de données
    public function selectDatabase($name) {
        if (!$this->admin_conn->select_db($name)) {
            die("Database selection failed: " . $this->admin_conn->error);
        }
    }

    // Méthode pour exécuter une requête SQL
    public function query($sql) {
        return $this->admin_conn->query($sql);
    }

    // Méthode pour fermer la connexion à la base de données
    public function closeConnection() {
        $this->admin_conn->close();
    }

    // Méthode pour créer des bases de données et des utilisateurs associés à partir d'un tableau d'array
    public static function createDatabasesFromConfig($database_configs) {
        foreach ($database_configs as $config) {
            // Vérifier si la base de données existe déjà
            $check_database_query = "SHOW DATABASES LIKE '" . $config['name'] . "';";
            $result = $this->query($check_database_query);
            if ($result->num_rows == 0) {
                // Créer une nouvelle base de données
                $create_database_query = "CREATE DATABASE " . $config['name'] . ";";
                $this->query($create_database_query);
                // echo "Database " . $config['name'] . " created successfully.\n";
            } else {
                // echo "Database " . $config['name'] . " already exists.\n";
            }

            // Vérifier si l'utilisateur existe déjà
            $check_user_query = "SELECT 1 FROM mysql.user WHERE user='" . $config['user'] . "' AND host='" . $config['host'] . "';";
            $result = $this->query($check_user_query);
            if ($result->num_rows == 0) {
                // Créer un nouvel utilisateur
                $create_user_query = "CREATE USER '" . $config['user'] . "'@'" . $config['host'] . "' IDENTIFIED BY '" . $config['password'] . "';";
                $this->query($create_user_query);
                echo "User " . $config['user'] . " created successfully.\n";
            } else {
                echo "User " . $config['user'] . " already exists.\n";
            }

            // Accorder des privilèges à l'utilisateur sur la base de données
            $grant_privileges_query = "GRANT ALL PRIVILEGES ON " . $config['name'] . ".* TO '" . $config['user'] . "'@'" . $config['host'] . "';";
            $this->query($grant_privileges_query);
            echo "Privileges granted to user " . $config['user'] . " for database " . $config['name'] . ".\n";
        }
    }

    // Méthode pour initialiser toutes les connexions à la base de données
    public function initializeConnection($host, $user, $password, $name) {
        $this->conn = new mysqli($host, $user, $password, $name);

        // Vérification de la connexion
        if ($this->conn->connect_error) {
            die("Connection to admin database failed: " . $this->conn->connect_error);
        }
    }
}

?>