<?php
class PathManager
{
    private $normalized_paths = [];

    // Constructeur
    public function __construct()
    {
        // Inclusion du fichier de vérification des chemins
        require_once __DIR__.'/data/check_paths.php';

        // Chargement des informations du projet depuis le fichier JSON
        $project_info = json_decode(file_get_contents(__DIR__.'/data/project_info.json'), true);

        // Tableau des constantes initiales et leurs valeurs
        $constants = [
            'PATH_ROOT' => '/',
            'UP' => '..',
            '2UP' => '../..',
            '3UP' => '../../..',
            'PATH_BASE_PATH' => realpath(__DIR__),
            'PATH_CUICUI_APP' => rtrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__)), DIRECTORY_SEPARATOR),
            'PATH_CUICUI_PROJECT' => dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__))),
        ];

        // Chemin entre les projets
        $constants['INTER_PROJECT_PATH'] = str_replace('/'.$constants['PATH_CUICUI_APP'], '', str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', __DIR__))));

        // Chemins prédéfinis
        $paths = [
            'PATH_MODULES' => $constants['PATH_CUICUI_APP'] . '/@modules',
            'PATH_DATA' => $constants['PATH_CUICUI_APP'] . '/data',
            'PATH_JSON' => $constants['PATH_CUICUI_APP'] . '/json',
            'PATH_ROUTES' => $constants['PATH_CUICUI_APP'] . '/routes',
            'PATH_TEMPLATES' => $constants['PATH_CUICUI_APP'] . '/templates',
            'PATH_FR' => $constants['PATH_CUICUI_APP'] . '/fr',
            'PATH_EN' => $constants['PATH_CUICUI_APP'] . '/en',
            'PATH_CSS_DIR' => $constants['PATH_CUICUI_PROJECT'] . '/css',
            'PATH_IMG_DIR' => $constants['PATH_CUICUI_PROJECT'] . '/img',
            'PATH_JS_DIR' => $constants['PATH_CUICUI_PROJECT'] . '/js',
            'PATH_PHP_DIR' => $constants['PATH_CUICUI_PROJECT'] . '/php',
            'PATH_SQL_DIR' => $constants['PATH_CUICUI_PROJECT'] . '/sql',
            'PATH_API_DIR' => $constants['PATH_CUICUI_PROJECT'] . '/api',
        ];

        // Normalisation des chemins et enregistrement dans le tableau
        foreach ($paths as $path => $value) {
            if (strpos($value, '/') !== 0) {
                $value = DIRECTORY_SEPARATOR . $value;
            }
            $this->normalized_paths[$path] = $this->normalizePath($value, $_SERVER['DOCUMENT_ROOT']);
        }

        // Normalisation des constantes et enregistrement dans le tableau
        foreach ($constants as $constant => $value) {
            if (strpos($value, '/') !== 0) {
                $value = DIRECTORY_SEPARATOR . $value;
            }
            $this->normalized_paths[$constant] = $this->normalizePath($value, $_SERVER['DOCUMENT_ROOT']);
        }

        // Enregistrement des chemins normalisés dans un fichier JSON
        file_put_contents(__DIR__.'/json/normalized_paths.json', json_encode($this->normalized_paths, JSON_PRETTY_PRINT));

        // Affichage des valeurs des constantes normalisées
        /*foreach ($this->normalized_paths as $variable => $value) {
            echo "-- $variable: $value <br>";
        }*/
    }

    // Méthode pour normaliser les chemins
    public static function normalizePath($path, $server_document_root)
    {
        // Remplacement des backslashes par des slashes
        $path = str_replace('\\', '/', $path);
        // Recherche de la position du document root dans le chemin
        $root_position = strpos($path, $server_document_root);
        if ($root_position !== false) {
            // Troncature après le document root
            $path = substr($path, $root_position + strlen($server_document_root));
        }
        return $path;
    }    

    // Méthode pour récupérer les chemins normalisés
    public function getNormalizedPaths()
    {
        return $this->normalized_paths;
    }
}

// Utilisation de la classe PathManager
$path_manager = new PathManager();

// Si le formulaire est soumis, redirection vers le chemin sélectionné
if (isset($_POST['path'])) {
    $selectedPath = $_POST['path'];
    header('Location: ' . $selectedPath);
    exit();
}
?>
