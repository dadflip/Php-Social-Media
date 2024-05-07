<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Constants
const DATASET = 0; // Definition of the DATASET constant for the default dataset index
const DEFAULT_PROFILE_VISIBILITY = 'private';
const DEFAULT_THEME = "blue"; // Default theme constant

// -------------------------------------------------------------------------------------------------------------------------------------

/**
 * Enumeration of different possible error types
 */
enum ErrorTypes {
    case None; // No error
    case NoConnection; // No connection to the database
    case PasswordNeqConfirm; // Password and confirmation do not match
    case InvalidUsername; // Invalid username
    case InvalidInput; // Invalid input
    case UndefinedTheme; // Theme undefined
    case UndefinedUser; // User undefined
    case QueryError; // Error executing SQL query
    case SessionError; // Session error
    case DuplicateUser; // Duplicate username or email
    
    // Ajouter une méthode pour obtenir la représentation en chaîne de caractères de chaque type d'erreur
    public function toString(): string {
        switch ($this) {
            case ErrorTypes::None:
                return "";
            case ErrorTypes::NoConnection:
                return "No connection to the database";
            case ErrorTypes::PasswordNeqConfirm:
                return "Password and confirmation do not match";
            case ErrorTypes::InvalidUsername:
                return "Invalid username";
            case ErrorTypes::InvalidInput:
                return "Invalid input";
            case ErrorTypes::UndefinedTheme:
                return "Theme undefined";
            case ErrorTypes::UndefinedUser:
                return "User undefined";
            case ErrorTypes::QueryError:
                return "Error executing SQL query";
            case ErrorTypes::SessionError:
                return "Session error";
            case ErrorTypes::DuplicateUser:
                return "Duplicate username or email";
            default:
                return "";
        }
    }
}


/**
 * Enumeration of possible errors during SQL query preparation
 */
enum RequestErr {
    case BindingFail; // Binding failure
}

// -------------------------------------------------------------------------------------------------------------------------------------
class DatabaseSetUp {
    private $database_configs; // Database configurations
    private $currentDatasetIndex; // Index of the currently selected dataset

    // Constructor to create and setup a database
    public function __construct($database_configs, $dataset_idx) {
        $this->database_configs = $database_configs;
        $this->currentDatasetIndex = $dataset_idx;
        $this->createDB($dataset_idx);
    }

    // Method to establish a connection to a specific dataset
    public function createDB($index) {
        if ($index < 0 || $index >= count($this->database_configs['databases'])) {
            die("Invalid dataset index.");
        }
        $db_config = $this->database_configs['databases'][$index];

        // Connexion à la base de données
        $conn = new mysqli($db_config['host'], $db_config['user'], $db_config['password']);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        // Requête pour vérifier l'existence de la base de données
        $check_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
        
        // Préparation de la requête
        $statement = $conn->prepare($check_query);
        
        // Liaison des paramètres
        $statement->bind_param("s", $db_config['name']);
        
        // Exécution de la requête préparée
        $statement->execute();
        
        // Récupération du résultat
        $result = $statement->get_result();

        // Si la base de données n'existe pas, on la crée
        if ($result->num_rows === 0) {

            // Valider le nom de la base de données
            if (!preg_match("/^[a-zA-Z0-9_]+$/", $db_config['name'])) {
                die("Nom de base de données invalide.");
            }

            // Requête pour créer une base de données
            $create_query = "CREATE DATABASE " . $db_config['name'];

            // Exécution de la requête
            if ($conn->query($create_query) === TRUE) {
                //echo "Base de données créée avec succès.<br>";
            } else {
                echo "Erreur lors de la création de la base de données : " . $conn->error . "<br>";
            }
        } else {
            //echo "La base de données existe déjà.<br>";
        }

        // Fermer le statement
        $statement->close();

        // Fermer la connexion
        $conn->close();

        $this->currentDatasetIndex = $index;
    }

    // Méthode statique pour initialiser les bases de données et créer les tables correspondantes
    public static function initializeDatabases($database_configs) {
        // Parcourir le tableau $database_configs pour initialiser chaque base de données et créer les tables correspondantes
        foreach ($database_configs['databases'] as $index => $config) {
            // Chemin vers le fichier SQL
            $sql_file_path = __DIR__.$GLOBALS['__var_sql_path__']."/{$config['name']}.sql";

            // Vérifier si le fichier existe
            if (file_exists($sql_file_path)) {
                // Lire le contenu du fichier SQL
                $sql_script = file_get_contents($sql_file_path);

                // Connexion à la base de données
                $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['name']);

                // Vérifier la connexion
                if ($conn->connect_error) {
                    die("Erreur de connexion à la base de données : " . $conn->connect_error);
                }

                // Exécuter le script SQL
                if ($conn->multi_query($sql_script)) {
                    //echo "Script SQL pour {$config['name']} exécuté avec succès.";
                } else {
                    echo "Erreur lors de l'exécution du script SQL pour {$config['name']} : " . $conn->error; 
                }

                // Fermer la connexion à la base de données
                $conn->close();
            } else {
                echo $sql_file_path;
                // Afficher un message d'erreur en forme de popup HTML
                echo "<script>alert('Le fichier SQL pour {$config['name']} n\'existe pas. Veuillez vérifier le chemin du fichier.');</script>";
            }
        }
    }
}

class MultiDatasetDatabaseManager {
    protected $admin_conn; // Database connection object
    protected ?ErrorTypes $err; // Error type variable
    private $database_configs; // Database configurations
    private $currentDatasetIndex; // Index of the currently selected dataset

    // Constructor to establish a connection to the database
    public function __construct($database_configs, $dataset_idx) {

        $this->database_configs = $database_configs;
        $this->currentDatasetIndex = $dataset_idx;
        $this->connectToDataset($dataset_idx);
    }

    // Method to establish a connection to a specific dataset
    public function connectToDataset($index) {
        if ($index < 0 || $index >= count($this->database_configs['databases'])) {
            die("Invalid dataset index.");
        }
        $db_config = $this->database_configs['databases'][$index];
        $this->admin_conn = new mysqli($db_config['host'], $db_config['user'], $db_config['password'], $db_config['name']); // Sélectionnez la base de données
        // Vérifiez la connexion
        if ($this->admin_conn->connect_error) {
            die("Connection to database failed: " . $this->admin_conn->connect_error);
        }
        $this->currentDatasetIndex = $index;
    }

    public function getConn() {
        return $this->admin_conn;
    }

    // Method to select a specific dataset
    public function selectDataset($index) {
        $this->connectToDataset($index);
    }

    // Method to select the next dataset
    public function nextDataset() {
        $this->currentDatasetIndex = ($this->currentDatasetIndex + 1) % count($this->database_configs['databases']);
        $this->connectToDataset($this->currentDatasetIndex);
    }

    // Method to get the index of the currently selected dataset
    public function getCurrentDatasetIndex() {
        return $this->currentDatasetIndex;
    }

    // Method to prepare an SQL query
    public function prepare(string $query) {
        return $this->admin_conn->prepare($query);
    }

    // Method to execute an SQL query
    public function query($sql) {
        return $this->admin_conn->query($sql);
    }

    // Method to update the database
    public function update($update_query) {
        if (strncasecmp("UPDATE", $update_query, strlen("UPDATE")) == 0) {
            $stmt = $this->admin_conn->prepare($update_query);
            $failure = $stmt->execute();
            $this->err = ErrorTypes::InvalidInput;
            return $failure;
        }
        return false;
    }

    // Method to get MySQLi error
    public function getMysqliError() {
        return $this->admin_conn->error;
    }

    // Method to close the database connection
    public function closeConnection() {
        $this->admin_conn->close();
    }

    // Method to create databases and associated users from an array of configurations
    public static function createDatabasesFromConfig($database_configs) {
        foreach ($database_configs['databases'] as $config) {
            $admin_conn = new mysqli($config['host'], $config['user'], $config['password']);
            // Check connection
            if ($admin_conn->connect_error) {
                die("Connection to database failed: " . $admin_conn->connect_error);
            }
            // Create database
            $create_database_query = "CREATE DATABASE IF NOT EXISTS " . $config['name'];
            $admin_conn->query($create_database_query);
            // Create user
            $create_user_query = "CREATE USER IF NOT EXISTS '" . $config['user'] . "'@'" . $config['host'] . "' IDENTIFIED BY '" . $config['password'] . "'";
            $admin_conn->query($create_user_query);
            // Grant privileges to user
            $grant_privileges_query = "GRANT ALL PRIVILEGES ON " . $config['name'] . ".* TO '" . $config['user'] . "'@'" . $config['host'] . "'";
            $admin_conn->query($grant_privileges_query);
            $admin_conn->close();
        }
    }

    // Function to secure a string for use in an SQL query
    public static function SecurizeString_ForSQL($string) {
        $string = trim($string);
        $string = stripcslashes($string);
        $string = addslashes($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    // Fonction pour exécuter une requête SQL et retourner un résultat
    public function createRequest(string $query, string $format, &$var1, &...$_) {
        // Assurez-vous que la base de données est sélectionnée
        $this->connectToDataset($this->currentDatasetIndex);
        // TODO: traitement des erreurs
        $stmt = $this->admin_conn->prepare($query);
        if ($stmt == false) {
            return NULL;
        }

        if ($stmt->bind_param($format, $var1, ...$_) == false) {
            return NULL;
        }

        if ($stmt->execute() == false) {
            return NULL;
        }

        return $stmt->get_result();
    }

    // Fonction pour exécuter une requête SQL sans retourner de résultat
    public function executeRequest(string $query, string $format, &$var1, &...$_) {
        // Assurez-vous que la base de données est sélectionnée
        $this->connectToDataset($this->currentDatasetIndex);
        $stmt = $this->admin_conn->prepare($query);
        if ($stmt == false) {
            return NULL;
        }
        $stmt->bind_param($format, $var1, ...$_);
        $stmt->execute();
    }

    // Fonction pour exécuter une requête SQL et retourne un résultat
    public function executeRequestOnly(string $query) {
        // Assurez-vous que la base de données est sélectionnée
        $this->connectToDataset($this->currentDatasetIndex);
        
        // Préparation de la requête
        $stmt = $this->admin_conn->prepare($query);
        
        // Vérification de la préparation de la requête
        if ($stmt == false) {
            return NULL;
        }
        
        if ($stmt->execute() == false) {
            return NULL;
        }

        return $stmt->get_result();
    }

}

class CuicuiDB extends MultiDatasetDatabaseManager {
    protected $available_themes = ["dark", "blue", "light"]; // Available themes array

    // Constructor
    public function __construct($database_configs, $dataset_idx) { // TODO: dataset_idx must be =  to DATASET const
        parent::__construct($database_configs, $dataset_idx); // Call the parent class constructor
        $this->err = ErrorTypes::None; // Initialize error type variable
    }
}

class CuicuiManager extends CuicuiDB {
    protected ?ErrorTypes $err; // Error type variable
    protected ?bool $connected = false; // Variable to track the connection status
    protected ?string $defaultProfileVisibility;

    // Constructor
    public function __construct($database_configs, $dataset_idx) {
        parent::__construct($database_configs, $dataset_idx); // Call the parent class constructor
        
        $this->err = ErrorTypes::None; // Set error type to None
        $this->setConnected(true); // Set connected to true when the connection is established
        $this->setProfileVisibility(DEFAULT_PROFILE_VISIBILITY);

        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Set default theme if not already set in session
        if (!isset($_SESSION["theme"])) {
            $_SESSION["theme"] = DEFAULT_THEME; // Set default theme
        }

        // Set default language based on HTTP_ACCEPT_LANGUAGE if not already set in session
        if (!isset($_SESSION["lang"])) {
            // Check if user_lang is set in session
            if(isset($_SESSION['MAIN_LANG'])){
                $_SESSION["lang"] = $_SESSION['MAIN_LANG']; // Set user's preferred language
            } else {
                $_SESSION["lang"] = $GLOBALS['LANG']; // Set default language
            }
        }

    }

    public function isConnected():bool {
        return $this->connected;
    }

    public function setConnected($bool){
        $this->connected = $bool;
    }

    public function setProfileVisibility($str){
        $this->defaultProfileVisibility = $str;
    }

    // -- Login -- SignIn -- Management-----------------------------------------------------------------------------------
    // Method to create a user account
    public function createAccount(): bool {
        // Vérifier la connexion à la base de données
        if (!$this->connected) { 
            $this->err = ErrorTypes::NoConnection; 
            return false; 
        }
        
        // Vérifier si les champs requis sont définis dans la requête POST
        if (!(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirmpass"]) && isset($_POST["email"]))) {
            $this->err = ErrorTypes::InvalidInput; 
            return false; 
        } else {
            // Vérifier la longueur du nom d'utilisateur
            if (strlen($_POST["username"]) < 4){
                $this->err = ErrorTypes::InvalidUsername; 
                return false; 
            } elseif ($_POST["password"] != $_POST["confirmpass"]){
                $this->err = ErrorTypes::PasswordNeqConfirm; 
                return false; 
            }
        
            // Récupérer les données du formulaire
            $username = $_POST["username"]; 
            $email = $_POST["email"]; 
            $password = md5($_POST["password"]); 
            $biography = isset($_POST["bio"]) ? $_POST["bio"] : ''; // Récupérer la bio s'il est défini

            
            // Générer un nouvel ID utilisateur
            $userId = $this->generateUserId();
            $dataId = $userId;
            $chatId = $dataId;

            // Générer une paire de clés RSA pour l'utilisateur
            $rsaKeys = $this->generateRSAKeys();

            // Vérifier si la génération des clés RSA a réussi
            if($rsaKeys === false) {
                return false; // Retourner faux pour indiquer un échec
            }

            // Requête SELECT pour vérifier l'unicité de l'email et du nom d'utilisateur
            $selectQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
            $res = $this->createRequest($selectQuery, "ss", $username, $email);

            // Vérifier s'il existe déjà un utilisateur avec le même nom d'utilisateur ou email
            if($res->num_rows > 0) {
                $this->err = ErrorTypes::DuplicateUser; 
                return false; 
            }

            // Requête pour l'insertion de l'utilisateur
            $userInsertQuery = "INSERT INTO users (UID, username, email, biography, password, rsa_public_key, rsa_private_key, user_data_id, user_chat_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE UID = VALUES(UID), username = VALUES(username), email = VALUES(email), 
                                biography = VALUES(biography), password = VALUES(password), rsa_public_key = VALUES(rsa_public_key), 
                                rsa_private_key = VALUES(rsa_private_key), user_data_id = VALUES(user_data_id), user_chat_id = VALUES(user_chat_id)";
            
            // Exécuter la requête avec les paramètres
            $this->executeRequest($userInsertQuery, "issssssii", $userId, $username, $email, $biography, $password, $rsaKeys['publicKey'], 
                                  $rsaKeys['privateKey'], $dataId, $chatId);

            // Vérifier si des lignes sont affectées
            if($this->admin_conn->affected_rows == 0) {
                $this->err = ErrorTypes::InvalidInput; // Définir le type d'erreur
                return false; // Retourner faux pour indiquer un échec
            }

            // Enregistrer l'image téléchargée sur le serveur
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile-image"]) && $_FILES["profile-image"]["error"] == UPLOAD_ERR_OK) {

                // Dossier de destination pour les images téléchargées
                $destinationFolder = $GLOBALS['img_upload'] . '@' . $username . '-' . $userId . '.profile-image';

                // Créer le dossier s'il n'existe pas
                if (!file_exists($destinationFolder)) {
                    mkdir($destinationFolder, 0777, true);
                }

                $imageFile = $_FILES['profile-image'];

                // Générer un nom unique pour le fichier image
                $imageFileName = uniqid('img_') . '.' . pathinfo($imageFile['name'], PATHINFO_EXTENSION);

                // Chemin complet du fichier dans le dossier de destination
                $imageFilePath = $destinationFolder . '/' . $imageFileName;

                // Déplacer le fichier vers le dossier de destination
                move_uploaded_file($imageFile['tmp_name'], $imageFilePath);

                // Enregistrer le chemin de l'image dans la base de données
                // À adapter selon votre structure de base de données et votre requête d'insertion
                $imageInsertQuery = "UPDATE users SET profile_pic_url = ? WHERE UID = ?";
                $this->executeRequest($imageInsertQuery, "si", $imageFilePath, $userId);

                $_SESSION["pfp_url"] = $GLOBALS['normalized_paths']['PATH_IMG_DIR'].'/'.$imageFilePath;
            }else{
                echo 'no image';
            }


            // Requête pour obtenir l'UID de l'utilisateur
            $uid_query = "SELECT UID FROM `users` WHERE username=?";
            // Exécuter la requête
            $res = $this->createRequest($uid_query, "s", $username);
            // Récupérer la ligne de résultat
            $row = $res->fetch_assoc();
            // Vérifier si la ligne est vide
            if ($row == NULL) {
                die("Error executing query: " . $this->admin_conn->error); // Arrêter avec un message d'erreur
            }

            // Récupérer l'UID à partir de la ligne de résultat
            $uid = $row['UID'];
            $acceptedCond = 1;
            $profileVisibility = $this->defaultProfileVisibility;


            $settingsArray = array(
                'notifications' => array(
                    'email' => true, // Notifications par e-mail activées ou désactivées
                    'push' => true // Notifications push activées ou désactivées
                ),
                'privacy' => array(
                    'post_visibility' => 'friends' // Visibilité des publications (public, privé, amis seulement, etc.)
                ),
                'other_preferences' => array(
                    'autoplay_videos' => false, // Lecture automatique des vidéos activée ou désactivée
                    'show_online_status' => true // Afficher le statut en ligne activé ou désactivé
                ),
                'additional_info' => array(
                    'fullname' => 'John Doe', // Nom complet de l'utilisateur
                    'bio_extended' => 'hello', // Bio étendue de l'utilisateur
                    'location' => 'New York', // Localisation de l'utilisateur
                    'social_links' => 'https://example.com/social', // Liens sociaux de l'utilisateur
                    'occupation' => 'Web Developer', // Occupation de l'utilisateur
                    'interests' => 'Travel, Music', // Centres d'intérêt de l'utilisateur
                    'languages_spoken' => 'English, French', // Langues parlées par l'utilisateur
                    'relationship_status' => 'single', // Statut relationnel de l'utilisateur
                    'birthday' => '1990-01-01', // Anniversaire de l'utilisateur
                    'privacy_settings' => false // Paramètres de confidentialité de l'utilisateur
                )
            );                   

            // Convertir le tableau en une chaîne JSON
            $settingsJson = json_encode($settingsArray);

            // Insérer les paramètres utilisateur dans la base de données
            $settingsInsertQuery = "INSERT INTO user_settings (users_uid, theme, lang, hasAcceptedConditions, profileVisibility, settings_array) 
                                    VALUES (?, ?, ?, ?, ?, ?)";

            // Exécuter la requête avec les paramètres
            $this->executeRequest($settingsInsertQuery, "ississ", $uid, $_SESSION["theme"], $_SESSION["lang"], $acceptedCond, $profileVisibility, 
                                  $settingsJson);

            // Définir le nom d'utilisateur et l'UID de la session
            $_SESSION["username"] = $username;
            $_SESSION["UID"] = $uid; 

            $this->goToMainpage($_SESSION["lang"]);

            return true; 
        }
    }

    // Fonction pour générer une paire de clés RSA
    function generateRSAKeys() {
        $config = array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        // Générer la paire de clés RSA
        $rsaKey = openssl_pkey_new($config);

        // Vérifier les erreurs OpenSSL
        while ($msg = openssl_error_string()) {
            error_log("OpenSSL error: $msg"); // Enregistrer les erreurs OpenSSL
        }

        // Exporter la clé privée
        if ($rsaKey !== false && openssl_pkey_export($rsaKey, $privateKey)) {
            // Récupérer la clé publique
            $publicKey = openssl_pkey_get_details($rsaKey)['key'];
            return array('publicKey' => $publicKey, 'privateKey' => $privateKey);
        } else {
            error_log("Error generating RSA keys"); // Enregistrer les erreurs
            return false; // Retourner false en cas d'erreur
        }
    }

    // Fonction pour générer un ID utilisateur à 10 chiffres
    function generateUserId() {
        return mt_rand(10000000, 99999999);
    }

    public function generateRandomNumericId($length = 10) {
        $characters = '0123456789';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
    
        return intval($randomString);
    }
    
    // Method to connect a user
    function connectUser() : LoginStatus {
        // Check if username and password are set in POST request
        if(!isset($_POST["username"]) && !isset($_POST["password"])) {
            // echo "Username and password are not set in POST request";
            return new LoginStatus(false, ""); // Return LoginStatus indicating failure
        }
        
        // Check if either username or password is not set in POST request
        if(!isset($_POST["username"]) || !isset($_POST["password"])) {
            // echo "One of the fields is empty";
            return new LoginStatus(false, "One of the fields is empty"); // Return LoginStatus indicating failure
        }
        
        $username = $_POST["username"]; // Get username from POST request
        // Hasher le mot de passe soumis avec md5()
        $password = md5($_POST["password"]);

        // Debugging output
        //echo "Username: $username, Password: $password<br>";

        $query  =  "SELECT users.UID, users.username, usset.theme, usset.lang, users.profile_pic_url FROM `users` 
                    JOIN `user_settings` AS usset ON users.UID=usset.users_uid
                    WHERE users.username=? AND users.password=?"; // SQL query to retrieve user information
        $result = $this->createRequest($query, "ss", $username, $password); // Execute the query
        
        // Debugging output
        // var_dump($result);

        // Check for query execution failure
        if($result == false) {
            // echo "Query execution failed: " . $this->admin_conn->error;
            die($this->admin_conn->error); // Die with error message
        }
        
        // Check if query returned no results
        if($result == NULL){
            // echo "No results returned from the query";
            return new LoginStatus(false, "Error executing query: " . $this->admin_conn->error); // Return LoginStatus indicating failure
        }
        
        // Check if username and password don't exist
        if($result->num_rows == 0) {
            // echo "Username and/or password don't exist";
            return new LoginStatus(false, "The username and/or password don't exist"); // Return LoginStatus indicating failure
        }

        $row = $result->fetch_assoc(); // Fetch the result row

        // Debugging output
        // var_dump($row);

        $_SESSION["username"] = $row["username"]; // Set session username
        $_SESSION["UID"] = $row["UID"]; // Set session UID
        $_SESSION["theme"] = $row["theme"]; // Set session theme
        $_SESSION["lang"] = $row["lang"]; // Set session language
        $_SESSION["pfp_url"] = $GLOBALS['normalized_paths']['PATH_IMG_DIR'].'/'.$row["profile_pic_url"]; // Set session profile picture URL
        
        return new LoginStatus(true, ""); // Return LoginStatus indicating success
    }

    // Méthode pour rediriger vers la page principale
    public function goToMainpage($lang): void {
        // Debugging output
        //echo "Redirecting to main page";
        header('Location:' . $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] .'/'. $GLOBALS["LANG"] . $GLOBALS['php_files']['mainpage']); // Rediriger vers la page principale
        exit(); // Terminer le script après la redirection
    }
    
    // Method to disconnect
    public function disconnect(): void {
        // Set connected to false when connection is closed
        $this->connected = false;
        $this->admin_conn->close(); // Close database connection
        session_destroy(); // Destroy session
    }

    // -- Error Management-----------------------------------------------------------------------------------
    // Method to retrieve error
    public function getError(): ErrorTypes {
        return $this->err; // Return error
    }

    // Method to reset error
    public function resetError(): void {
        $this->err = ErrorTypes::None; // Reset error type
    }


    // -- User Management -----------------------------------------------------------------------------------
    // Method to change user theme
    public function changeUserTheme(): bool{
        if(!isset($_SESSION["UID"])) { // Check if UID is set in session
            $this->err = ErrorTypes::SessionError; // Set error type
            return false; // Return false indicating failure
        }

        // Check if theme is not set in GET request
        if(!isset($_GET["theme"])) {
            $this->err = ErrorTypes::None; // Set error type
            return true; // Return true indicating success
        }

        // Check if selected theme is the same as current theme
        if($_GET["theme"] == $_SESSION["theme"]) {
            $this->err = ErrorTypes::None; // Set error type
            return false; // Return false indicating failure
        }

        // Check if selected theme is undefined
        if(!in_array($_GET["theme"], $this->available_themes, true)) {
            $this->err = ErrorTypes::UndefinedTheme; // Set error type
            return false; // Return false indicating failure
        }
        
        $_SESSION["theme"] = $_GET["theme"]; // Set session theme
        $session_uid = $_SESSION["UID"]; // Get session UID

        $query = "UPDATE `user_settings`
                  SET theme=?
                  WHERE UID=?"; // SQL query to update user theme
        $this->executeRequest($query, "si", $_GET["theme"], $session_uid); // Execute the query

        if($this->admin_conn->affected_rows == 0) { // Check if any rows affected
            $this->err = ErrorTypes::QueryError; // Set error type
            return false; // Return false indicating failure
        }

        $this->resetError(); // Reset error
        return true; // Return true indicating success
    }

    // Method to get user info
    public function getUserInfo(string $UID): ?UserInfo {
        $query = "SELECT * FROM users WHERE UID=$UID"; // SQL query to retrieve user info
        $result = $this->admin_conn->query($query); // Execute the query

        // Check for query execution failure
        if(!$result) {
            die("Error executing query: " . $this->admin_conn->error); // Die with error message
        }

        // Check if no rows affected
        if(mysqli_affected_rows($this->admin_conn) == 0) {
            $this->err = ErrorTypes::UndefinedUser; // Set error type
            return NULL; // Return NULL indicating failure
        }

        $row = $result->fetch_assoc(); // Fetch the result row

        if($row == false) {
            $this->err = ErrorTypes::QueryError; // Set error type
            return NULL; // Return NULL indicating failure
        }

        return new UserInfo($row); // Return UserInfo object
    }

    // Method to get user settings
    public function getUserSettings(string $UID): ?UserInfo {
        $query = "SELECT * FROM user_settings WHERE users_uid=$UID"; // SQL query to retrieve user settings
        $result = $this->admin_conn->query($query); // Execute the query

        // Check for query execution failure
        if(!$result) {
            die("Error executing query: " . $this->admin_conn->error); // Die with error message
        }

        // Check if no rows affected
        if($result->num_rows == 0) {
            $this->err = ErrorTypes::UndefinedUser; // Set error type
            return NULL; // Return NULL indicating failure
        }

        $row = $result->fetch_assoc(); // Fetch the result row

        if($row == false) {
            $this->err = ErrorTypes::QueryError; // Set error type
            return NULL; // Return NULL indicating failure
        }

        return new UserInfo($row); // Return UserInfo object
    }

    // Method to get user info and settings
    public function getUserInfoAndSettings(string $UID): ?UserInfo {
        $query = "SELECT u.*, us.*
                FROM users u
                LEFT JOIN user_settings us ON u.UID = us.users_uid
                WHERE u.UID=$UID"; // SQL query to retrieve user info and settings
        $result = $this->admin_conn->query($query); // Execute the query

        // Check for query execution failure
        if(!$result) {
            die("Error executing query: " . $this->admin_conn->error); // Die with error message
        }

        // Check if no rows affected
        if($result->num_rows == 0) {
            $this->err = ErrorTypes::UndefinedUser; // Set error type
            return NULL; // Return NULL indicating failure
        }

        $row = $result->fetch_assoc(); // Fetch the result row

        if($row == false) {
            $this->err = ErrorTypes::QueryError; // Set error type
            return NULL; // Return NULL indicating failure
        }

        return new UserInfo($row); // Return UserInfo object
    }


    public function getUserInfoByName(string $username): ?UserInfo {
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->createRequest($query, "s", $username);
    
        if(!$stmt) {
            die("Error executing query: " . $this->admin_conn->error);
        }
    
        $row = $stmt->fetch_assoc();
    
        if(!$row) {
            $this->err = ErrorTypes::QueryError;
            return NULL;
        }
    
        return new UserInfo($row);
    }    
    

    // -- Follow Management -----------------------------------------------------------------------------------
    // Method to check if user is following another user
    public function getFollow(string $follower_id, string $target_id): bool {
        $query = "SELECT * FROM `follow` WHERE follower_id=? AND target_id=?"; // SQL query to check if user is following another user
        $res = $this->createRequest($query, "ss", $follower_id, $target_id); // Execute the query
        $row = $res->fetch_assoc(); // Fetch the result row

        return $row != NULL; // Return true if row is not empty, else false
    }

    // Method to insert a new follow relationship
    public function followUser($followerId, $targetUserId): bool {
        $query = "INSERT INTO follow (follower_id, target_id) VALUES (?, ?)"; // SQL query to insert a new follow relationship
        $success = $this->createRequest($query, "ii", $followerId, $targetUserId); // Execute the query
        
        return $success; // Return true if insertion was successful, else false
    }

        // Method to create follow relationship
    public function createFollow($follower, $target) {
        $query = "INSERT INTO `follow`(follower_id,target_id) VALUES (?,?)"; // SQL query to create follow relationship
        $this->executeRequest($query, "ii", $follower, $target); // Execute the query
    }

    // Method to delete follow relationship
    public function deleteFollow($follower, $target) {
        $query = "DELETE FROM `follow` WHERE follower_id=? AND target_id=?"; // SQL query to delete follow relationship
        $this->executeRequest($query, "ss", $follower, $target); // Execute the query
    }

    // Méthode pour ajouter un like
    public function addLike($userId, $textId, $action) {
        // Insérer le like dans la base de données
        $stmt = $this->prepare('INSERT INTO likes (users_uid, text_id, action) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $textId, $action]);
    }

    // Méthode pour incrémenter le compteur de likes d'un texte
    public function likeText($textId) {
        // Mettre à jour le compteur de likes dans la table des textes
        $stmt = $this->prepare('UPDATE posts SET likes = likes + 1 WHERE textId = ?');
        $stmt->execute([$textId]);
    }

    // Méthode pour incrémenter le compteur de dislikes d'un texte
    public function dislikeText($textId) {
        // Mettre à jour le compteur de dislikes dans la table des textes
        $stmt = $this->prepare('UPDATE posts SET dislikes = dislikes + 1 WHERE textId = ?');
        $stmt->execute([$textId]);
    }

    // Méthode pour insérer un commentaire
    public function insertComment($commentId, $parentId, $content) {
        $userId = $_SESSION['UID'];

        // Préparer la requête SQL pour insérer un commentaire
        $query = "INSERT INTO comments (content, users_uid, parent_id, comment_id) VALUES (?, ?, ?, ?)";

        // Exécuter la requête avec les valeurs fournies
        $stmt = $this->prepare($query);
        $stmt->bind_param("siss", $content, $userId, $parentId, $commentId);
        $stmt->execute();

        // Vérifier si l'insertion a réussi
        if ($stmt->affected_rows === 1) {
            return true; // Retourner true si l'insertion a réussi
        } else {
            return false; // Retourner false si l'insertion a échoué
        }
    }

    public function getUserPosts($userId) {
        $query = "SELECT * FROM `posts` WHERE users_uid=?";
        $res = $this->createRequest($query, "i", $userId);
        $posts = array(); // Initialiser un tableau pour stocker les publications de l'utilisateur
    
        // Vérifier si des résultats ont été retournés
        if ($res->num_rows > 0) {
            // Parcourir chaque ligne de résultat pour récupérer les publications
            while ($row = $res->fetch_assoc()) {
                // Ajouter la publication à la liste des publications de l'utilisateur
                $posts[] = $row;
            }
        }
    
        return $posts; // Retourner toutes les publications de l'utilisateur
    }
    

    public function getUserStatistics($userId) {
        // Initialisation des compteurs
        $likeCount = 0;
        $dislikeCount = 0;
        $followerCount = 0;
        $flipboxCount = 0;
        $commentCount = 0;
        $postCount = 0;

        // Compter les likes des posts
        $postQuery = "SELECT COUNT(*) AS post_count FROM posts WHERE users_uid = ?";
        $postResult = $this->createRequest($postQuery, "i", $userId);
        if ($postResult->num_rows > 0) {
            $postCount = $postResult->fetch_assoc()['post_count'];
        }
    
        // Compter les likes des posts
        $likeQuery = "SELECT COUNT(*) AS like_count FROM likes WHERE users_uid = ? AND action = 'like'";
        $likeResult = $this->createRequest($likeQuery, "i", $userId);
        if ($likeResult->num_rows > 0) {
            $likeCount = $likeResult->fetch_assoc()['like_count'];
        }

        // Compter les dislikes des posts
        $dislikeQuery = "SELECT COUNT(*) AS dislike_count FROM likes WHERE users_uid = ? AND action = 'dislike'";
        $dislikeResult = $this->createRequest($dislikeQuery, "i", $userId);
        if ($dislikeResult->num_rows > 0) {
            $dislikeCount = $dislikeResult->fetch_assoc()['dislike_count'];
        }
    
        // Compter le nombre de followers
        $followerQuery = "SELECT COUNT(*) AS follower_count FROM follow WHERE target_id = ?";
        $followerResult = $this->createRequest($followerQuery, "i", $userId);
        if ($followerResult->num_rows > 0) {
            $followerCount = $followerResult->fetch_assoc()['follower_count'];
        }
    
        // Compter le nombre de flipboxes découvertes
        $flipboxQuery = "SELECT COUNT(*) AS flipbox_count FROM data WHERE users_uid = ?";
        $flipboxResult = $this->createRequest($flipboxQuery, "i", $userId);
        if ($flipboxResult->num_rows > 0) {
            $flipboxCount = $flipboxResult->fetch_assoc()['flipbox_count'];
        }
    
        // Compter le nombre de commentaires écrits
        $commentQuery = "SELECT COUNT(*) AS comment_count FROM comments WHERE users_uid = ?";
        $commentResult = $this->createRequest($commentQuery, "i", $userId);
        if ($commentResult->num_rows > 0) {
            $commentCount = $commentResult->fetch_assoc()['comment_count'];
        }
    
        // Retourner les statistiques
        return array(
            'like_count' => $likeCount,
            'dislike_count' => $dislikeCount,
            'follower_count' => $followerCount,
            'flipbox_count' => $flipboxCount,
            'comment_count' => $commentCount,
            'post_count' => $postCount
        );
    }  
    
    public function getIdByUsername($username) {
        $query = "SELECT UID FROM `users` WHERE username=?"; // SQL query to check if user is following another user
        $res = $this->createRequest($query, "s", $username); // Execute the query
        $row = $res->fetch_assoc(); // Fetch the result row

        return $row;
    }
    
}

// -------------------------------------------------------------------------------------------------------------------------------------


class UserInfo {
    private int $uid = 0;
    private string $username = "";
    private string $password = "";
    private string $email = "";
    public string $bio = "";
    private string $profile_picture = ""; 
    private string $user_theme = "";
    private string $user_lang = "";

    // Ajouter les nouveaux champs
    private array $settingsArray = []; // Modifier le type pour être un tableau

    public function __construct(array $info) {
        $this->bio = isset($info["biography"]) ? $info["biography"] : "";
        $this->username = isset($info["username"]) ? $info["username"] : "";
        $this->email = isset($info["email"]) ? $info["email"] : "";
        $this->uid = isset($info["UID"]) ? $info["UID"] : 0;
        $this->profile_picture = isset($info["profile_pic_url"]) ? $info["profile_pic_url"] : "";
        $this->user_theme = isset($info["theme"]) ? $info["theme"] : "";
        $this->user_lang = isset($info["lang"]) ? $info["lang"] : "";
        $this->password= isset($info["password"]) ? $info["password"] : "";
        
        // Décoder le tableau des paramètres s'il existe
        $this->settingsArray = isset($info["settings_array"]) ? json_decode($info["settings_array"], true) : [];

    }

    // Setters
    public function setUsername(string $username): void {
        $this->username = $username;
        $_SESSION['username'] = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setTheme(string $theme): void {
        $this->user_theme = $theme;
        $_SESSION['theme'] = $theme;
    }

    public function setLang(string $lang): void {
        $this->user_lang = $lang;
        $GLOBALS['MAIN_LANG'] = $lang;
        $GLOBALS['LANG'] = $GLOBALS['MAIN_LANG'];
    }

    public function setPassword(string $password): void {
        if($password!=$this->password || $password!=''){
            $this->password = $password;
        }
    }

    public function setBio(string $bio): void {
        $this->bio = $bio;
    }

    public function setProfilePicture(string $profile_picture): void {
        $this->profile_picture = $profile_picture;
    }

    public function setSettingsArray(array $settingsArray): void {
        $this->settingsArray = $settingsArray;
    }


    // Méthode pour insérer les informations de l'utilisateur dans la base de données
    public function insertUserInfo(mysqli $conn): bool {
        $query = "INSERT INTO users (UID, username, password, email, biography, profile_pic_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssss", $this->uid, $this->username, $this->password, $this->email, $this->bio, $this->profile_picture);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Méthode pour insérer les paramètres de l'utilisateur dans la base de données
    public function insertUserSettings(mysqli $conn): bool {
        $query = "INSERT INTO user_settings (users_uid, theme, lang, settings_array) VALUES (?, ?, ?, ?)";
        $settings_json = json_encode($this->settingsArray);
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $this->uid, $this->user_lang, $this->user_theme, $settings_json);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Méthode pour insérer à la fois les informations et les paramètres de l'utilisateur dans la base de données
    public function insertUserInfoAndSettings(mysqli $conn): bool {
        $user_info_inserted = $this->insertUserInfo($conn);
        $user_settings_inserted = $this->insertUserSettings($conn);
        return $user_info_inserted && $user_settings_inserted;
    }

    // Méthode pour mettre à jour les informations de l'utilisateur dans la base de données
    public function updateUserInfo(mysqli $conn): bool {
        $query = "UPDATE users SET username=?, password=?, email=?, biography=?, profile_pic_url=? WHERE UID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $this->username, $this->password, $this->email, $this->bio, $this->profile_picture, $this->uid);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Méthode pour mettre à jour les paramètres de l'utilisateur dans la base de données
    public function updateUserSettings(mysqli $conn): bool {
        $query = "UPDATE user_settings SET settings_array=?, theme=?, lang=? WHERE users_uid=?";
        $settings_json = json_encode($this->settingsArray);
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $settings_json, $this->user_theme, $this->user_lang, $this->uid);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Méthode pour mettre à jour à la fois les informations et les paramètres de l'utilisateur dans la base de données
    public function updateUserInfoAndSettings(mysqli $conn): bool {
        $user_info_updated = $this->updateUserInfo($conn);
        $user_settings_updated = $this->updateUserSettings($conn);
        return $user_info_updated && $user_settings_updated;
    }



    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTheme() {
        return $this->user_theme;
    }

    public function getLang() {
        return $this->user_lang;
    }

    public function getProfilePicture() {
        return $this->profile_picture;
    }

    public function getHandle() {
        return "@".$this->username;
    }

    public function getID() {
        return $this->uid;
    }

    public function getBiography() {
        return $this->bio;
    }

    public function getAvatar() {
        return $this->profile_picture;
    }

    public function getSettingsArray() {
        return $this->settingsArray;
    }
}



// --

class CuicuiSession extends CuicuiManager {

    // CuicuiManager object, initialised flag, and error type variable
    public ?CuicuiManager $cuicui_manager = NULL;
    public bool $initialised = false;
    public ?ErrorTypes $err;

    // Constructor
    public function __construct(CuicuiManager $cuicui_manager) {
        $this->cuicui_manager = $cuicui_manager; // Set CuicuiManager object
        $this->initialised = true; // Set initialised flag to true
    }

    // Static method to get username
    public static function getUsername(): string {
        if(!isset($_SESSION["username"])) { // Check if username is not set in session
            $result = "Log in"; // Default result

            // Set result based on language
            switch($_SESSION["lang"]) {
                case "fr":
                    $result = "Connectez vous";
                    break;
            }
            return $result; // Return result
        } else {
            return $_SESSION["username"]; // Return username if set in session
        }
    }

    // Method to get session attribute by name
    public function getAttribute(string $name): mixed {
        return $_SESSION[$name]; // Return session attribute
    }

    // Method to get user info by UID
    public function getUserInfo(string $UID): ?UserInfo {
        if($this->cuicui_manager == NULL) { // Check if CuicuiManager object is not set
            die("No manager connection"); // Die with error message
        }
        return $this->cuicui_manager->getUserInfo($UID); // Get user info from CuicuiManager
    }

    public function getUserSettings(string $UID): ?UserInfo {
        if($this->cuicui_manager == NULL) { // Check if CuicuiManager object is not set
            die("No manager connection"); // Die with error message
        }
        return $this->cuicui_manager->getUserSettings($UID); // Get user info from CuicuiManager
    }

    public function getUserInfoAndSettings(string $UID): ?UserInfo {
        if($this->cuicui_manager == NULL) { // Check if CuicuiManager object is not set
            die("No manager connection"); // Die with error message
        }
        return $this->cuicui_manager->getUserInfoAndSettings($UID); // Get user info from CuicuiManager
    }

    // Method to update user bio
    public function updateBio(string $newBio, $UID) {
        if($this->cuicui_manager == NULL) { // Check if CuicuiManager object is not set
            die("No manager connection"); // Die with error message
        }
        $secureText = CuicuiDB::SecurizeString_ForSQL($newBio); // Secure the text for SQL
        $query = "UPDATE `user` SET biography=? WHERE UID=?"; // SQL query to update user bio
        $this->cuicui_manager->executeRequest($query, "si", $newBio, $UID); // Execute the query
    }
}

class LoginStatus {
    private bool $login_success = false;
    private ?string $text = NULL;

    public function __construct(bool $success, string $text) {
        $this->login_success = $success;
        $this->text = $text;
    }
     
    public function getLoginStatus(): bool {
        return $this->login_success;
    }

    public function getText() {
        return $this->text;
    }
}

?>