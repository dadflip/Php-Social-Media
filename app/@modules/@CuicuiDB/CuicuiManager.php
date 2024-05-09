<?php 
require_once('NotificationManager.php');
require_once('ChatManager.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Constants
const DATASET = 0; // Definition of the DATASET constant for the default dataset index
const DEFAULT_PROFILE_VISIBILITY = 'private';
const DEFAULT_THEME = "blue"; // Default theme constant

// ------------------------------------------------------------------------------------------------------------------------------------- ENUM - ERRORTYPES
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
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - REQUEST_ERR
enum RequestErr {
    case BindingFail; // Binding failure
}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - DATABASE_SETUP
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

        // Connect to the database
        $conn = new mysqli($db_config['host'], $db_config['user'], $db_config['password']);

        // Check connection
        if ($conn->connect_error) {
            die("Database connection error: " . $conn->connect_error);
        }

        // Query to check database existence
        $check_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
        
        // Prepare the query
        $statement = $conn->prepare($check_query);
        
        // Bind parameters
        $statement->bind_param("s", $db_config['name']);
        
        // Execute the prepared query
        $statement->execute();
        
        // Get the result
        $result = $statement->get_result();

        // If the database doesn't exist, create it
        if ($result->num_rows === 0) {

            // Validate database name
            if (!preg_match("/^[a-zA-Z0-9_]+$/", $db_config['name'])) {
                die("Invalid database name.");
            }

            // Query to create a database
            $create_query = "CREATE DATABASE " . $db_config['name'];

            // Execute the query
            if ($conn->query($create_query) === TRUE) {
                //echo "Database created successfully.<br>";
            } else {
                echo "Error creating database: " . $conn->error . "<br>";
            }
        } else {
            //echo "Database already exists.<br>";
        }

        // Close the statement
        $statement->close();

        // Close the connection
        $conn->close();

        $this->currentDatasetIndex = $index;
    }

    // Static method to initialize databases and create corresponding tables
    public static function initializeDatabases($database_configs) {
        // Iterate through the $database_configs array to initialize each database and create corresponding tables
        foreach ($database_configs['databases'] as $index => $config) {
            // Path to the SQL file
            $sql_file_path = __DIR__.$GLOBALS['__var_sql_path__']."/{$config['name']}.sql";

            // Check if the file exists
            if (file_exists($sql_file_path)) {
                // Read the content of the SQL file
                $sql_script = file_get_contents($sql_file_path);

                // Connect to the database
                $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['name']);

                // Check connection
                if ($conn->connect_error) {
                    die("Database connection error: " . $conn->connect_error);
                }

                // Execute the SQL script
                if ($conn->multi_query($sql_script)) {
                    //echo "SQL script for {$config['name']} executed successfully.";
                } else {
                    echo "Error executing SQL script for {$config['name']}: " . $conn->error; 
                }

                // Close the database connection
                $conn->close();
            } else {
                echo $sql_file_path;
                // Display an error message as an HTML popup
                echo "<script>alert('The SQL file for {$config['name']} does not exist. Please check the file path.');</script>";
            }
        }
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - MULTI_DATASET_DATABASE_MANAGER
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
        $this->admin_conn = new mysqli($db_config['host'], $db_config['user'], $db_config['password'], $db_config['name']); // Select the database
        // Check connection
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

    public function truncateAllTables(): bool {
        // Disable foreign key checks
        $this->executeRequestOnly('SET FOREIGN_KEY_CHECKS = 0');
    
        // Liste des tables à vider
        $tables = array(
            'bans',
            'media',
            'notifications',
            'chat',
            'comments',
            'follow',
            'likes',
            'posts',
            'data',
            'user_settings',
            'users'
        );
    
        // Parcourir chaque table et exécuter la requête TRUNCATE
        foreach ($tables as $table) {
            $truncateQuery = "TRUNCATE TABLE $table";
            $this->executeRequestOnly($truncateQuery);
        }
    
        // Réactiver les contraintes de clé étrangère
        $this->executeRequestOnly('SET FOREIGN_KEY_CHECKS = 1');
    
        return true; // Retourne true pour indiquer le succès
    }    

    // Function to secure a string for use in an SQL query
    public static function SecurizeString_ForSQL($string) {
        $string = trim($string);
        $string = stripcslashes($string);
        $string = addslashes($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    // Function to execute an SQL query and return a result
    public function createRequest(string $query, string $format, &$var1, &...$_) {
        // Ensure the database is selected
        $this->connectToDataset($this->currentDatasetIndex);

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

    // Function to execute an SQL query without returning a result
    public function executeRequest(string $query, string $format, &$var1, &...$_) {
        // Ensure the database is selected
        $this->connectToDataset($this->currentDatasetIndex);
        $stmt = $this->admin_conn->prepare($query);
        if ($stmt == false) {
            return NULL;
        }
        $stmt->bind_param($format, $var1, ...$_);
        $stmt->execute();
    }

    // Function to execute an SQL query and return a result
    public function executeRequestOnly(string $query) {
        // Ensure the database is selected
        $this->connectToDataset($this->currentDatasetIndex);
        
        // Prepare the query
        $stmt = $this->admin_conn->prepare($query);
        
        // Check if the query preparation is successful
        if ($stmt == false) {
            return NULL;
        }
        
        if ($stmt->execute() == false) {
            return NULL;
        }

        return $stmt->get_result();
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - CUICUI_DB
class CuicuiDB extends MultiDatasetDatabaseManager {
    protected $available_themes = ["dark", "blue", "light"]; // Available themes array

    // Constructor
    public function __construct($database_configs, $dataset_idx) {
        parent::__construct($database_configs, $dataset_idx); // Call the parent class constructor
        $this->err = ErrorTypes::None; // Initialize error type variable
    }

    // ------------------------------------------------------------------------------------------- Utils
    // Function to generate an RSA key pair
    function generateRSAKeys() {
        $config = array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        // Generate RSA key pair
        $rsaKey = openssl_pkey_new($config);

        // Check OpenSSL errors
        while ($msg = openssl_error_string()) {
            error_log("OpenSSL error: $msg"); // Log OpenSSL errors
        }

        // Export private key
        if ($rsaKey !== false && openssl_pkey_export($rsaKey, $privateKey)) {
            // Get public key
            $publicKey = openssl_pkey_get_details($rsaKey)['key'];
            return array('publicKey' => $publicKey, 'privateKey' => $privateKey);
        } else {
            error_log("Error generating RSA keys"); // Log errors
            return false; // Return false in case of error
        }
    }

    // Function to generate a 10-digit user ID
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

}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - CUICUI_MANAGER
class CuicuiManager extends CuicuiDB {
    protected ?ErrorTypes $err; // Error type variable
    protected bool $connected = false; // Variable to track the connection status
    protected string $defaultProfileVisibility;

    // ------------------------------------------------------------------------------------------- Class
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

    // Setters, Getters
    public function isConnected(): bool {
        return $this->connected;
    }

    public function setConnected(bool $bool): void {
        $this->connected = $bool;
    }

    public function arrayToString($array) {
        // Utilisez implode pour convertir le tableau en chaîne avec une virgule comme séparateur
        $string = implode(', ', $array);
        return $string;
    }

    // ------------------------------------------------------------------------------------------- Login - SignIn
    // Method to create a user account
    public function createAccount(): bool {
        // Check database connection
        if (!$this->connected) { 
            $this->err = ErrorTypes::NoConnection; 
            return false; 
        }
        
        // Check if required fields are set in the POST request
        if (!(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirmpass"]) && isset($_POST["email"]))) {
            $this->err = ErrorTypes::InvalidInput; 
            return false; 
        } else {
            // Check username length
            if (strlen($_POST["username"]) < 4){
                $this->err = ErrorTypes::InvalidUsername; 
                return false; 
            } elseif ($_POST["password"] != $_POST["confirmpass"]){
                $this->err = ErrorTypes::PasswordNeqConfirm; 
                return false; 
            }
        
            // Retrieve form data
            $username = $_POST["username"]; 
            $email = $_POST["email"]; 
            $password = md5($_POST["password"]); 
            $biography = isset($_POST["bio"]) ? $_POST["bio"] : ''; // Retrieve bio if set

            // Generate a new user ID
            $userId = $this->generateUserId();
            $dataId = $userId;
            $chatId = $dataId;

            // Generate an RSA key pair for the user
            $rsaKeys = $this->generateRSAKeys();

            // Check if RSA key generation succeeded
            if($rsaKeys === false) {
                return false; // Return false to indicate failure
            }

            // SELECT query to check uniqueness of email and username
            $selectQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
            $res = $this->createRequest($selectQuery, "ss", $username, $email);

            // Check if there is already a user with the same username or email
            if($res->num_rows > 0) {
                $this->err = ErrorTypes::DuplicateUser; 
                return false; 
            }

            // INSERT query for user insertion
            $userInsertQuery = "INSERT INTO users (UID, username, email, biography, password, rsa_public_key, rsa_private_key, user_data_id, user_chat_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                                ON DUPLICATE KEY UPDATE UID = VALUES(UID), username = VALUES(username), email = VALUES(email), 
                                biography = VALUES(biography), password = VALUES(password), rsa_public_key = VALUES(rsa_public_key), 
                                rsa_private_key = VALUES(rsa_private_key), user_data_id = VALUES(user_data_id), user_chat_id = VALUES(user_chat_id)";
            
            // Execute the query with parameters
            $this->executeRequest($userInsertQuery, "issssssii", $userId, $username, $email, $biography, $password, $rsaKeys['publicKey'], 
                                  $rsaKeys['privateKey'], $dataId, $chatId);

            // Check if any rows are affected
            if($this->admin_conn->affected_rows == 0) {
                $this->err = ErrorTypes::InvalidInput; // Set error type
                return false; // Return false to indicate failure
            }

            // Save uploaded image on server
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile-image"]) && $_FILES["profile-image"]["error"] == UPLOAD_ERR_OK) {

                // Créer un dossier pour l'utilisateur s'il n'existe pas
                $userFolder = $GLOBALS['img_upload'] . '@' . $username . '-' . $userId . '.profile-image';
                $parent = realpath(__DIR__) . '../../../../img';

                if (!file_exists($userFolder)) {
                    mkdir($parent . $userFolder, 0777, true);
                }

                $imageFile = $_FILES['profile-image'];

                $imageFileName = uniqid('img_') . '.' . pathinfo($imageFile['name'], PATHINFO_EXTENSION);
                $imageFilePath = $userFolder . '/' . $imageFileName;

                move_uploaded_file($_FILES['profile-image']['tmp_name'], $parent . $imageFilePath);

                $imageInsertQuery = "UPDATE users SET profile_pic_url = ? WHERE UID = ?";
                $this->executeRequest($imageInsertQuery, "si", $imageFilePath, $userId);

                $_SESSION["pfp_url"] = $imageFilePath;
            } else {
                $parent = realpath(__DIR__) . '../../../../img';

                // Chemin vers le placeholder
                $placeholderPath = "../../../../img/placeholder.png";
                
                // Chemin complet du répertoire utilisateur
                $userFolder = $parent . '/' . $GLOBALS['img_upload'] . '@' . $username . '-' . $userId . '.profile-image';
            
                // S'assurer que le répertoire utilisateur existe, sinon le créer
                if (!file_exists($userFolder)) {
                    mkdir($userFolder, 0777, true);
                }
                
                // Chemin de destination pour l'image de profil
                $imageFilePath = $userFolder . '/placeholder.png';
                
                // Copier le placeholder vers le dossier de l'utilisateur
                if (copy($placeholderPath, $imageFilePath)) {
                    // Mettre à jour la base de données avec le chemin de l'image de profil
                    $imageInsertQuery = "UPDATE users SET profile_pic_url = ? WHERE UID = ?";
                    $this->executeRequest($imageInsertQuery, "si", $imageFilePath, $userId);
                    
                    // Mettre à jour la variable de session avec le chemin de l'image de profil
                    $_SESSION["pfp_url"] = $imageFilePath;
                } else {
                    // Gérer l'erreur si la copie échoue
                    echo "La copie du placeholder a échoué.";
                }
            }
                     

            // Query to get user's UID
            $uid_query = "SELECT UID FROM `users` WHERE username=?";
            // Execute the query
            $res = $this->createRequest($uid_query, "s", $username);
            // Fetch the result row
            $row = $res->fetch_assoc();
            // Check if row is empty
            if ($row == NULL) {
                die("Error executing query: " . $this->admin_conn->error); // Stop with an error message
            }

            // Get UID from the result row
            $uid = $row['UID'];
            $acceptedCond = 1;
            $profileVisibility = $this->defaultProfileVisibility;

            // Settings array
            $settingsArray = array(
                'notifications' => array(
                    'email' => true, // Email notifications enabled or disabled
                    'push' => true // Push notifications enabled or disabled
                ),
                'privacy' => array(
                    'post_visibility' => 'friends' // Post visibility (public, private, friends only, etc.)
                ),
                'other_preferences' => array(
                    'autoplay_videos' => false, // Autoplay videos enabled or disabled
                    'show_online_status' => true // Show online status enabled or disabled
                ),
                'additional_info' => array(
                    'fullname' => 'John Doe', // User's full name
                    'bio_extended' => 'hello', // User's extended bio
                    'location' => 'New York', // User's location
                    'social_links' => 'https://example.com/social', // User's social links
                    'occupation' => 'Web Developer', // User's occupation
                    'interests' => 'Travel, Music', // User's interests
                    'languages_spoken' => 'English, French', // Languages spoken by the user
                    'relationship_status' => 'single', // User's relationship status
                    'birthday' => '1990-01-01', // User's birthday
                    'privacy_settings' => true // User's privacy settings
                )
            );                   

            // Convert the array to a JSON string
            $settingsJson = json_encode($settingsArray);

            // Insert user settings into the database
            $settingsInsertQuery = "INSERT INTO user_settings (users_uid, theme, lang, hasAcceptedConditions, profileVisibility, settings_array) 
                                    VALUES (?, ?, ?, ?, ?, ?)";

            // Execute the query with parameters
            $this->executeRequest($settingsInsertQuery, "ississ", $uid, $_SESSION["theme"], $_SESSION["lang"], $acceptedCond, $profileVisibility, 
                                  $settingsJson);

            // Set username and UID in session
            $_SESSION["username"] = $username;
            $_SESSION["UID"] = $uid; 

            $this->goToMainpage($_SESSION["lang"]);

            return true; 
        }
    }

    public function deleteAccount($userId): bool {
        // Check database connection
        if (!$this->connected) { 
            $this->err = ErrorTypes::NoConnection; 
            return false; 
        }
    
        // Disable foreign key checks
        $this->executeRequestOnly('SET FOREIGN_KEY_CHECKS = 0');
    
        // DELETE query to remove comments associated with the user
        $deleteCommentsQuery = "DELETE FROM comments WHERE users_uid = ?";
        
        // Execute the query with parameters
        $this->executeRequest($deleteCommentsQuery, "i", $userId);
    
        // DELETE query to remove follow associations with the user
        $deleteFollowQuery = "DELETE FROM follow WHERE target_id = ? OR follower_id = ?";
        
        // Execute the query with parameters
        $this->executeRequest($deleteFollowQuery, "ii", $userId, $userId);
    
        // DELETE query to remove likes associated with the user
        $deleteLikesQuery = "DELETE FROM likes WHERE users_uid = ?";
        
        // Execute the query with parameters
        $this->executeRequest($deleteLikesQuery, "i", $userId);
    
        // DELETE query to remove posts associated with the user
        $deletePostsQuery = "DELETE FROM posts WHERE users_uid = ?";
        
        // Execute the query with parameters
        $this->executeRequest($deletePostsQuery, "i", $userId);
    
        // DELETE query to remove chat associations with the user
        $deleteChatQuery = "DELETE FROM chat WHERE chat_src_id = ? OR chat_dest_id = ?";
        
        // Execute the query with parameters
        $this->executeRequest($deleteChatQuery, "ii", $userId, $userId);

        // DELETE query to remove user settings from the database
        $deleteSettingsQuery = "DELETE FROM user_settings WHERE users_uid = ?";

        // Execute the query with parameters
        $this->executeRequest($deleteSettingsQuery, "i", $userId);


        // DELETE query to remove user from the database
        $deleteUserQuery = "DELETE FROM users WHERE UID = ?";

        // Execute the query with parameters
        $this->executeRequest($deleteUserQuery, "i", $userId);
    
        // Check if any rows are affected
        if($this->admin_conn->affected_rows == 0) {
            $this->err = ErrorTypes::InvalidInput; // Set error type
            return false; // Return false to indicate failure
        }
    
        // Re-enable foreign key checks
        $this->executeRequestOnly('SET FOREIGN_KEY_CHECKS = 1');
    
        return true; // Return true to indicate success
    }    
    

    // Method to connect a user
    function connectUser(): LoginStatus {
        // Check if username and password are set in POST request
        if (!isset($_POST["username"]) && !isset($_POST["password"])) {
            return new LoginStatus(false, ""); // Return LoginStatus indicating failure
        }
        
        // Check if either username or password is not set in POST request
        if (!isset($_POST["username"]) || !isset($_POST["password"])) {
            return new LoginStatus(false, "One of the fields is empty"); // Return LoginStatus indicating failure
        }
        
        $username = $_POST["username"]; // Get username from POST request
        // Hash the submitted password using md5()
        $password = md5($_POST["password"]);

        // SQL query to retrieve user information
        $query = "SELECT users.UID, users.username, usset.theme, usset.lang, users.profile_pic_url, users.isAdmin FROM `users` 
                JOIN `user_settings` AS usset ON users.UID=usset.users_uid
                WHERE users.username=? AND users.password=?";
        $result = $this->createRequest($query, "ss", $username, $password); // Execute the query

        // Check for query execution failure
        if ($result == false) {
            die($this->admin_conn->error); // Die with error message
        }
        
        // Check if query returned no results
        if ($result == NULL) {
            return new LoginStatus(false, "Error executing query: " . $this->admin_conn->error); // Return LoginStatus indicating failure
        }
        
        // Check if username and password don't exist
        if ($result->num_rows == 0) {
            return new LoginStatus(false, "The username and/or password don't exist"); // Return LoginStatus indicating failure
        }

        $row = $result->fetch_assoc(); // Fetch the result row

        // Set session variables
        $_SESSION["username"] = $row["username"]; // Set session username
        $_SESSION["UID"] = $row["UID"]; // Set session UID
        $_SESSION["theme"] = $row["theme"]; // Set session theme
        $_SESSION["lang"] = $row["lang"]; // Set session language
        $_SESSION["isAdmin"] = $row["isAdmin"];
        $_SESSION["pfp_url"] = $row["profile_pic_url"]; // Set session profile picture URL
        
        return new LoginStatus(true, ""); // Return LoginStatus indicating success
    }

    // Method to redirect to the main page
    public function goToMainpage($lang): void {
        header('Location:' . $GLOBALS['normalized_paths']['PATH_CUICUI_APP'] . '/' . $GLOBALS["LANG"] . $GLOBALS['php_files']['mainpage']); // Redirect to the main page
        exit(); // Terminate the script after redirection
    }

    // Method to disconnect
    public function disconnect(): void {
        // Set connected to false when the connection is closed
        $this->connected = false;
        $this->admin_conn->close(); // Close the database connection
        session_destroy(); // Destroy the session
    }

    // ------------------------------------------------------------------------------------------- Error
    // Method to retrieve error
    public function getError(): ErrorTypes {
        return $this->err; // Return error
    }

    // Method to reset error
    public function resetError(): void {
        $this->err = ErrorTypes::None; // Reset error type
    }


    // ------------------------------------------------------------------------------------------- Users - Settings
    // Setters, Getters
    public function setProfileVisibility(string $str): void {
        $this->defaultProfileVisibility = $str;
    }

    // Method to change user theme
    public function changeUserTheme(): bool {
        if (!isset($_SESSION["UID"])) {
            $this->err = ErrorTypes::SessionError;
            return false;
        }

        // Check if theme is set in GET request
        if (!isset($_GET["theme"])) {
            $this->err = ErrorTypes::None;
            return true;
        }

        // Check if selected theme is the same as current theme
        if ($_GET["theme"] == $_SESSION["theme"]) {
            $this->err = ErrorTypes::None;
            return false;
        }

        // Check if selected theme is undefined
        if (!in_array($_GET["theme"], $this->available_themes, true)) {
            $this->err = ErrorTypes::UndefinedTheme;
            return false;
        }

        $_SESSION["theme"] = $_GET["theme"];
        $session_uid = $_SESSION["UID"];

        $query = "UPDATE `user_settings`
                SET theme=?
                WHERE UID=?";
        $this->executeRequest($query, "si", $_GET["theme"], $session_uid);

        if ($this->admin_conn->affected_rows == 0) {
            $this->err = ErrorTypes::QueryError;
            return false;
        }

        $this->resetError();
        return true;
    }

    public function changeProfileImage($username, $userId, $file_img) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($file_img) && $file_img["error"] == UPLOAD_ERR_OK) {
            // Créer un dossier pour l'utilisateur s'il n'existe pas
            $userFolder = $GLOBALS['img_upload'] . '@' . $username . '-' . $userId . '.profile-image';
            $parent = realpath(__DIR__) . '../../../../img';

            /*if (!file_exists($userFolder)) {
                mkdir($parent . $userFolder, 0777, true);
            }*/

            $imageFile = $file_img;

            $imageFileName = uniqid('img_') . '.' . pathinfo($imageFile['name'], PATHINFO_EXTENSION);
            $imageFilePath = $userFolder . '/' . $imageFileName;

            move_uploaded_file($file_img['tmp_name'], $parent . $imageFilePath);

            $imageInsertQuery = "UPDATE users SET profile_pic_url = ? WHERE UID = ?";
            $this->executeRequest($imageInsertQuery, "si", $imageFilePath, $userId);

            $_SESSION["pfp_url"] = $imageFilePath;
        }
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

    public function getIdByUsername($username) {
        $query = "SELECT UID FROM `users` WHERE username=?";
        $res = $this->createRequest($query, "s", $username);
        $row = $res->fetch_assoc();
        return $row;
    }

    public function getIdByUsername_ToString($username) {
        $query = "SELECT UID FROM `users` WHERE username=?";
        $res = $this->createRequest($query, "s", $username);
        $row = $res->fetch_assoc();
        return $this->arrayToString($row);
    }

    public function getUsernameById($userId) {
        $query = "SELECT username FROM `users` WHERE UID=?";
        $res = $this->createRequest($query, "i", $userId);
        $row = $res->fetch_assoc();
        return $this->arrayToString($row);
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

    // Method to get user posts
    public function getUserPosts($userId) {
        $query = "SELECT * FROM `posts` WHERE users_uid=?";
        $res = $this->createRequest($query, "i", $userId);
        $posts = array(); // Initialize an array to store user posts

        // Check if results were returned
        if ($res->num_rows > 0) {
            // Iterate through each result row to retrieve user posts
            while ($row = $res->fetch_assoc()) {
                // Add the post to the user's posts list
                $posts[] = $row;
            }
        }

        return $posts; // Return all user posts
    }

    // Get the user ID associated with a post
    public function getPostUserId($postId) {
        $userId = 0;
        // Query to retrieve the user ID associated with the post
        $getPostUserIdQuery = "SELECT users_uid FROM posts WHERE textId = ?";
        
        // Prepare the statement
        $statement = $this->admin_conn->prepare($getPostUserIdQuery);
        
        // Bind the parameters
        $statement->bind_param("s", $postId);
        
        // Execute the statement
        $statement->execute();
        
        // Bind the result variables
        $statement->bind_result($userId);
        
        // Fetch the result
        $statement->fetch();
        
        // Close the statement
        $statement->close();
        
        // Return the user ID
        return $userId;
    }

    // Method to get user statistics
    public function getUserStatistics($userId) {
        // Initialize counters
        $likeCount = 0;
        $dislikeCount = 0;
        $followerCount = 0;
        $flipboxCount = 0;
        $commentCount = 0;
        $postCount = 0;

        // Count post likes
        $postQuery = "SELECT COUNT(*) AS post_count FROM posts WHERE users_uid = ?";
        $postResult = $this->createRequest($postQuery, "i", $userId);
        if ($postResult->num_rows > 0) {
            $postCount = $postResult->fetch_assoc()['post_count'];
        }

        // Count post likes
        $likeQuery = "SELECT COUNT(*) AS like_count FROM likes WHERE users_uid = ? AND action = 'like'";
        $likeResult = $this->createRequest($likeQuery, "i", $userId);
        if ($likeResult->num_rows > 0) {
            $likeCount = $likeResult->fetch_assoc()['like_count'];
        }

        // Count post dislikes
        $dislikeQuery = "SELECT COUNT(*) AS dislike_count FROM likes WHERE users_uid = ? AND action = 'dislike'";
        $dislikeResult = $this->createRequest($dislikeQuery, "i", $userId);
        if ($dislikeResult->num_rows > 0) {
            $dislikeCount = $dislikeResult->fetch_assoc()['dislike_count'];
        }

        // Count follower count
        $followerQuery = "SELECT COUNT(*) AS follower_count FROM follow WHERE target_id = ?";
        $followerResult = $this->createRequest($followerQuery, "i", $userId);
        if ($followerResult->num_rows > 0) {
            $followerCount = $followerResult->fetch_assoc()['follower_count'];
        }

        // Count flipbox discovered count
        $flipboxQuery = "SELECT COUNT(*) AS flipbox_count FROM data WHERE users_uid = ?";
        $flipboxResult = $this->createRequest($flipboxQuery, "i", $userId);
        if ($flipboxResult->num_rows > 0) {
            $flipboxCount = $flipboxResult->fetch_assoc()['flipbox_count'];
        }

        // Count written comments count
        $commentQuery = "SELECT COUNT(*) AS comment_count FROM comments WHERE users_uid = ?";
        $commentResult = $this->createRequest($commentQuery, "i", $userId);
        if ($commentResult->num_rows > 0) {
            $commentCount = $commentResult->fetch_assoc()['comment_count'];
        }

        // Return statistics
        return array(
            'like_count' => $likeCount,
            'dislike_count' => $dislikeCount,
            'follower_count' => $followerCount,
            'flipbox_count' => $flipboxCount,
            'comment_count' => $commentCount,
            'post_count' => $postCount
        );
    }

    public function getPostDetails($postId) {
        // Préparez votre requête pour récupérer les détails du post avec l'ID donné
        $query = "SELECT * FROM posts WHERE textId = ?";
        $stmt = $this->prepare($query);
        $stmt->bind_param("s", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Vérifiez s'il y a des résultats
        if ($result->num_rows > 0) {
            // Récupérez les détails du post
            $postDetails = $result->fetch_assoc();
            return $postDetails;
        } else {
            // Si aucun résultat n'est trouvé, retournez NULL ou une valeur par défaut
            return null;
        }
    }

    public function updatePostContent($postId, $newContent) {
        // Préparez votre requête pour mettre à jour le contenu du post avec l'ID donné
        $query = "UPDATE posts SET text_content = ? WHERE textId = ?";
        $stmt = $this->prepare($query);
        $stmt->bind_param("ss", $newContent, $postId);
        
        // Exécutez la requête
        $stmt->execute();
        
        // Vérifiez si la mise à jour a réussi
        if ($stmt->affected_rows > 0) {
            // La mise à jour a réussi
            return true;
        } else {
            // La mise à jour a échoué
            return false;
        }
    }

    // ------------------------------------------------------------------------------------------- Admin
    public function deletePost($postID, $adminID): void {
        if (!$this->isUserAdmin($adminID)) {
            throw new NotAnAdminException();
        }
        $delete_query = "DELETE FROM `posts` WHERE textID=?";
        $stmt = $this->prepare($delete_query);
        $stmt->bind_param("s", $postID);
        $stmt->execute();

        // Send notification to the user
        $this->sendPostDeletionNotification($this->getPostUserId($postID), $postID, $adminID);
    }

    public function deletePostByUser($postID): void {
        $delete_query = "DELETE FROM `posts` WHERE textID=?";
        $stmt = $this->prepare($delete_query);
        $stmt->bind_param("s", $postID);
        $stmt->execute();
    }

    // Add a new method to send post deletion notification
    private function sendPostDeletionNotification($userId, $postId, $adminId) {
        // Prepare notification message
        $notificationType = "post_deletion";
        $notificationDate = date('Y-m-d H:i:s');
        $notificationTitle = "Post Deleted";
        $notificationText = "Your post (ID: $postId) has been deleted by admin (ID: $adminId).";

        $notif = new NotificationManager($this->getConn());
        // Insert the notification into the 'notifications' table
        $notif->insertNotification($userId, $notificationDate, $notificationTitle, $notificationText, $notificationType);
    }
    
    public function markAsSensitive($postID, $adminID): void {
        if (!$this->isUserAdmin($adminID)) {
            throw new NotAnAdminException();
        }
        $update_query = "UPDATE `posts` SET sensitive_content=1 WHERE textID=?";
        $stmt = $this->prepare($update_query);
        $stmt->bind_param("s", $postID);
        $stmt->execute();
    }
    
    public function isUserAdmin($userID): bool {
        $query = "SELECT isAdmin FROM users WHERE UID=?";
        $stmt = $this->createRequest($query, "i", $userID);
        $row = $stmt->fetch_assoc();
        return $row["isAdmin"] == "1";
    }

    public function sendNotificationToAdmins($notificationTitle, $notificationText, $notificationType) {
        $adminQuery = "SELECT UID FROM users WHERE isAdmin=1";
        $stmt = $this->query($adminQuery);
        $notif = new NotificationManager($this->getConn());

        if ($stmt->num_rows > 0) {
            while ($row = $stmt->fetch_assoc()) {
                $notif->insertNotification($row["UID"], date('Y-m-d H:i:s'), $notificationTitle, $notificationText, $notificationType);
            }
            return true;
        } else {
            return false;
        }
    }
    
    public function banUserAndSendNotification($userId, $adminId, $duration, $reason) {
        // Ban the user
        $this->banUser($userId, $adminId, $duration);
        
        // Send a notification to the banned user
        $this->sendBanNotification($userId, $adminId, $duration, $reason);
    }

    // Send a notification to the banned user
    public function sendBanNotification($userId, $adminId, $duration, $reason) {
        // Get the username of the banned user
        $bannedUserName = $this->getUserNameById($userId);

        // Notification details
        $notificationType = "ban";
        $notificationDate = date('Y-m-d H:i:s');
        $notificationTitle = "$bannedUserName - You have been banned - duration : $duration";
        $notificationText = "You have been banned by admin $adminId. Reason : $reason";

        $notif = new NotificationManager($this->getConn());
        // Insert the notification into the 'notifications' table
        $notif->insertNotification($userId, $notificationDate, $notificationTitle, $notificationText, $notificationType);
    }

    private function convertDurationToSeconds($durationString) {
        $duration = strtolower($durationString);
        $value = (int) preg_replace('/[^0-9]/', '', $duration);
        if (strpos($duration, 'h') !== false) {
            return $value * 3600;
        } elseif (strpos($duration, 'j') !== false) {
            return $value * 86400;
        } elseif (strpos($duration, 'm') !== false) {
            return $value * 60;
        } elseif (strpos($duration, 's') !== false) {
            return $value;
        } else {
            return $value * 3600;
        }
    }    
    public function banUser($userID, $adminID, $duration = "0"): void {
        $startTime = date('Y-m-d H:i:s');
        
        if (!$this->isUserAdmin($adminID)) {
            throw new NotAnAdminException();
        }
    
        $durationInSeconds = $this->convertDurationToSeconds($duration);
    
        $query = "INSERT INTO bans(userID, adminID, duration, startTime) VALUES (?, ?, ?, ?)";
        $stmt = $this->prepare($query);
        $stmt->bind_param("iiis", $userID, $adminID, $durationInSeconds, $startTime);
        $stmt->execute();
    }
    
    
    public function alreadyBanned($userID): BanStatus {
        $query = "SELECT * FROM bans WHERE userID=?";
        $result = $this->createRequest($query, "i", $userID);
        if ($result->num_rows == 0) {
            return new BanStatus(false);
        }
        $row = $result->fetch_assoc();
    
        $admin_name_query = $this->createRequest("SELECT username FROM `users` WHERE UID=?", "i", $row["adminID"]);
        $admin_name_row = $admin_name_query->fetch_assoc();
        $admin_name = $admin_name_row["username"];
        return new BanStatus(true, $row["userID"], $admin_name, $row["duration"]);
    }

    public function checkAndLiftBan($userID): bool {
        $query = "SELECT startTime, duration FROM bans WHERE userID=?";
        $result = $this->createRequest($query, "i", $userID);
        
        if ($result->num_rows == 0) {
            return true;
        }
    
        $row = $result->fetch_assoc();
        $startTime = $row["startTime"];
        $duration = $row["duration"];
    
        // Convertir la durée du bannissement en secondes
        $banDurationInSeconds = $duration;
    
        // Convertir la date et l'heure de début en timestamp Unix
        $startTimeStamp = strtotime($startTime);
    
        // Obtenir le timestamp actuel
        $currentTimeStamp = time();
    
        // Calculer la différence en secondes entre l'heure actuelle et l'heure de début du bannissement
        $timeDifference = $currentTimeStamp - $startTimeStamp;
    
        echo $timeDifference;
    
        if ($timeDifference >= $banDurationInSeconds) {
            $this->liftBan($userID);
            return true;
        }
        return false;
    }       
    
    private function liftBan($userID): void {
        $query = "DELETE FROM bans WHERE userID=?";
        $this->createRequest($query, "i", $userID);
    }

    public function warnUser($userId, $adminId, $message = ""): void {
        if (!$this->isUserAdmin($adminId)) {
            throw new NotAnAdminException();
        }

        // Get the username of the warned user
        $warnedUserName = $this->getUserNameById($userId);

        // Notification details
        $notificationType = "warning";
        $notificationDate = date('Y-m-d H:i:s');
        $notificationTitle = "$warnedUserName - You have been warned !";
        $notificationText = "You have been warned by admin $adminId. Message : $message";

        $notif = new NotificationManager($this->getConn());
        // Insert the notification into the 'notifications' table
        $notif->insertNotification($userId, $notificationDate, $notificationTitle, $notificationText, $notificationType);
    }
    
    public function unbanUser($userId): void {
        // Vérifier si l'utilisateur est déjà débanni
        $banStatus = $this->alreadyBanned($userId);
        if (!$banStatus->isBanned()) {
            throw new Exception("L'utilisateur n'est pas banni !");
        }

        // Lever le bannissement de l'utilisateur
        $this->liftBan($userId);
    }

    public function markAsNonSensitive($postId): void {
        // Marquer le post avec l'ID $postId comme non-sensible
        $update_query = "UPDATE `posts` SET sensitive_content=0 WHERE textId=?";
        $stmt = $this->prepare($update_query);
        $stmt->bind_param("s", $postId);
        $stmt->execute();
    }

    public function getBannedUsers(): array {
        // Requête SQL pour récupérer les utilisateurs bannis
        $query = "SELECT u.UID, u.username FROM users u INNER JOIN bans b ON u.UID = b.userID";
        $result = $this->query($query);
    
        $banned_users = array();
        while ($row = $result->fetch_assoc()) {
            $banned_users[] = $row;
        }
    
        return $banned_users;
    }
    
    public function getSensitivePosts(): array {
        // Requête SQL pour récupérer les posts marqués comme sensibles
        $query = "SELECT p.textId, u.username AS author, p.text_content FROM posts p INNER JOIN users u ON p.users_uid = u.UID WHERE p.sensitive_content = 1";
        $result = $this->query($query);
    
        $sensitive_posts = array();
        while ($row = $result->fetch_assoc()) {
            $sensitive_posts[] = $row;
        }
    
        return $sensitive_posts;
    }    

    // ------------------------------------------------------------------------------------------- Follow
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
    public function unFollowUser($follower, $target): bool {
        $query = "DELETE FROM `follow` WHERE follower_id=? AND target_id=?"; // SQL query to delete follow relationship
        $success = $this->createRequest($query, "ss", $follower, $target); // Execute the query

        return $success;
    }

    // Method to delete follow relationship
    public function deleteFollow($follower, $target) {
        $query = "DELETE FROM `follow` WHERE follower_id=? AND target_id=?"; // SQL query to delete follow relationship
        $this->executeRequest($query, "ss", $follower, $target); // Execute the query
    }


    // ------------------------------------------------------------------------------------------- Like
    // Method to add a like
    public function addLike($userId, $textId, $action) {
        // Insert the like into the database
        $stmt = $this->prepare('INSERT INTO likes (users_uid, text_id, action) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $textId, $action]);
    }

    // Method to increment the like counter of a text
    public function likeText($textId) {
        // Update the like counter in the text table
        $stmt = $this->prepare('UPDATE posts SET likes = likes + 1 WHERE textId = ?');
        $stmt->execute([$textId]);
    }

    // Method to increment the dislike counter of a text
    public function dislikeText($textId) {
        // Update the dislike counter in the text table
        $stmt = $this->prepare('UPDATE posts SET dislikes = dislikes + 1 WHERE textId = ?');
        $stmt->execute([$textId]);
    }

    // ------------------------------------------------------------------------------------------- Comment
    // Method to insert a comment
    public function insertComment($commentId, $parentId, $content) {
        $userId = $_SESSION['UID'];

        // Prepare the SQL query to insert a comment
        $query = "INSERT INTO comments (content, users_uid, parent_id, comment_id) VALUES (?, ?, ?, ?)";

        // Execute the query with the provided values
        $stmt = $this->prepare($query);
        $stmt->bind_param("siss", $content, $userId, $parentId, $commentId);
        $stmt->execute();

        // Check if insertion was successful
        if ($stmt->affected_rows === 1) {
            return true; // Return true if insertion was successful
        } else {
            return false; // Return false if insertion failed
        }
    }

    public function insertChatMessage($content, $type, $srcId, $destId):bool {
        $insert_query = "INSERT INTO `chat` (`content`, `type`, `chat_src_id`, `chat_dest_id`) VALUES (?, ?, ?, ?)";
        $stmt = $this->prepare($insert_query);
        $stmt->bind_param("ssii", $content, $type, $srcId, $destId);
        $success = $stmt->execute();
        return $success;
    }

    public function getChatMessages() {
        $select_query = "SELECT `content`, `datetime`, `type`, `chat_src_id`, `chat_dest_id` FROM `chat`";
        $result = $this->query($select_query);
        $messages = array();
        while ($row = $result->fetch_assoc()) {
            $messages[] = array(
                'content' => $row['content'],
                'datetime' => $row['datetime'],
                'type' => $row['type'],
                'chat_src_id' => $row['chat_src_id'],
                'chat_dest_id' => $row['chat_dest_id']
            );
        }
        return $messages;
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - NOT_AN_ADMIN_EXCEPTION
class NotAnAdminException extends Exception {
    public function __construct() {
        parent::__construct("You are not an admin");
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - BAN_STATUS
class BanStatus {
    public string $userID;
    public string $adminID;
    public string $duration;
    public bool $isBanned;
    public function __construct($banned, $userID="", $adminID="", $duration="")
    {
        $this->isBanned = $banned;
        $this->userID = $userID;
        $this->adminID = $adminID;
        $this->duration = $duration;
    }

    public function isBanned() {
        return $this->isBanned;
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - USER_INFO
class UserInfo {
    private int $uid = 0;
    private string $username = "";
    private string $password = "";
    private string $email = "";
    public string $bio = "";
    private string $profile_picture = ""; 
    private string $user_theme = "";
    private string $user_lang = "";
    private array $settingsArray = []; 

    public function __construct(array $info) {
        $this->bio = $info["biography"] ?? "";
        $this->username = $info["username"] ?? "";
        $this->email = $info["email"] ?? "";
        $this->uid = $info["UID"] ?? 0;
        $this->profile_picture = $info["profile_pic_url"] ?? "";
        $this->user_theme = $info["theme"] ?? "";
        $this->user_lang = $info["lang"] ?? "";
        $this->password= $info["password"] ?? "";
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
        if ($password != $this->password || $password != '') {
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

    public function insertUserInfo(mysqli $conn): bool {
        $query = "INSERT INTO users (UID, username, password, email, biography, profile_pic_url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssss", $this->uid, $this->username, $this->password, $this->email, $this->bio, $this->profile_picture);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function insertUserSettings(mysqli $conn): bool {
        $query = "INSERT INTO user_settings (users_uid, theme, lang, settings_array) VALUES (?, ?, ?, ?)";
        $settings_json = json_encode($this->settingsArray);
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $this->uid, $this->user_lang, $this->user_theme, $settings_json);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function insertUserInfoAndSettings(mysqli $conn): bool {
        $user_info_inserted = $this->insertUserInfo($conn);
        $user_settings_inserted = $this->insertUserSettings($conn);
        return $user_info_inserted && $user_settings_inserted;
    }

    public function updateUserInfo(mysqli $conn): bool {
        $query = "UPDATE users SET username=?, password=?, email=?, biography=?, profile_pic_url=? WHERE UID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $this->username, $this->password, $this->email, $this->bio, $this->profile_picture, $this->uid);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateUserSettings(mysqli $conn): bool {
        $query = "UPDATE user_settings SET settings_array=?, theme=?, lang=? WHERE users_uid=?";
        $settings_json = json_encode($this->settingsArray);
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $settings_json, $this->user_theme, $this->user_lang, $this->uid);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function updateUserInfoAndSettings(mysqli $conn): bool {
        $user_info_updated = $this->updateUserInfo($conn);
        $user_settings_updated = $this->updateUserSettings($conn);
        return $user_info_updated && $user_settings_updated;
    }

    // Getters
    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getTheme(): string {
        return $this->user_theme;
    }

    public function getLang(): string {
        return $this->user_lang;
    }

    public function getProfilePicture(): string {
        return $this->profile_picture;
    }

    public function getHandle(): string {
        return "@".$this->username;
    }

    public function getID(): int {
        return $this->uid;
    }

    public function getBiography(): string {
        return $this->bio;
    }

    public function getAvatar(): string {
        return $this->profile_picture;
    }

    public function getSettingsArray(): array {
        return $this->settingsArray;
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - CUICUI_SESSION
class CuicuiSession extends CuicuiManager {
    public ?CuicuiManager $cuicui_manager = NULL;
    public bool $initialised = false;

    public function __construct(CuicuiManager $cuicui_manager) {
        $this->cuicui_manager = $cuicui_manager;
        $this->initialised = true;
    }

    public static function getUsername(): string {
        if (!isset($_SESSION["username"])) {
            $result = "Log in";
            switch ($_SESSION["lang"]) {
                case "fr":
                    $result = "Connectez-vous";
                    break;
            }
            return $result;
        } else {
            return $_SESSION["username"];
        }
    }

    public function getAttribute(string $name): mixed {
        return $_SESSION[$name];
    }

    public function getUserInfo(string $UID): ?UserInfo {
        if ($this->cuicui_manager == NULL) {
            die("No manager connection");
        }
        return $this->cuicui_manager->getUserInfo($UID);
    }

    public function getUserSettings(string $UID): ?UserInfo {
        if ($this->cuicui_manager == NULL) {
            die("No manager connection");
        }
        return $this->cuicui_manager->getUserSettings($UID);
    }

    public function getUserInfoAndSettings(string $UID): ?UserInfo {
        if ($this->cuicui_manager == NULL) {
            die("No manager connection");
        }
        return $this->cuicui_manager->getUserInfoAndSettings($UID);
    }

    public function updateBio(string $newBio, $UID) {
        if ($this->cuicui_manager == NULL) {
            die("No manager connection");
        }
        $secureText = CuicuiDB::SecurizeString_ForSQL($newBio);
        $query = "UPDATE `user` SET biography=? WHERE UID=?";
        $this->cuicui_manager->executeRequest($query, "si", $newBio, $UID);
    }
}
// ------------------------------------------------------------------------------------------------------------------------------------- CLASS - LOGIN_STATUS
class LoginStatus {
    private bool $loginSuccess = false;
    private ?string $text = null;

    public function __construct(bool $success, ?string $text = null) {
        $this->loginSuccess = $success;
        $this->text = $text;
    }
     
    public function getLoginStatus(): bool {
        return $this->loginSuccess;
    }

    public function getText(): ?string {
        return $this->text;
    }
}