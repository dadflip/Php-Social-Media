<?php
/**
 * Class PathManager.
 * Manages paths and generates normalized paths.
 */
class PathManager
{
    /** @var array $normalized_paths The array to store normalized paths. */
    private $normalized_paths = [];

    /** @var array $project_info The array to store project information. */
    private $project_info = [];

    /**
     * Constructor.
     * Initializes the PathManager object.
     */
    public function __construct()
    {
        // Include the file for checking paths
        require_once __DIR__.'/data/check_paths.php';

        // Load project information from the JSON file
        $this->project_info = json_decode(file_get_contents(__DIR__.'/data/project_info.json'), true);

        // Define initial constants and their values
        $constants = [
            'PATH_ROOT' => '/',
            'UP' => '..',
            '2UP' => '../..',
            '3UP' => '../../..',
            'PATH_BASE_PATH' => realpath(__DIR__),
            'PATH_CUICUI_APP' => rtrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__)), DIRECTORY_SEPARATOR),
            'PATH_CUICUI_PROJECT' => dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__))),
        ];

        // Path between projects
        $constants['INTER_PROJECT_PATH'] = str_replace('/'.$constants['PATH_CUICUI_APP'], '', str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', __DIR__))));

        // Predefined paths
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

        // Normalize paths and store them in the array
        foreach ($paths as $path => $value) {
            if (strpos($value, '/') !== 0) {
                $value = DIRECTORY_SEPARATOR . $value;
            }
            $this->normalized_paths[$path] = $this->normalizePath($value, $_SERVER['DOCUMENT_ROOT']);
        }

        // Normalize constants and store them in the array
        foreach ($constants as $constant => $value) {
            if (strpos($value, '/') !== 0) {
                $value = DIRECTORY_SEPARATOR . $value;
            }
            $this->normalized_paths[$constant] = $this->normalizePath($value, $_SERVER['DOCUMENT_ROOT']);
        }

        // Save normalized paths to a JSON file
        file_put_contents(__DIR__.'/json/normalized_paths.json', json_encode($this->normalized_paths, JSON_PRETTY_PRINT));
    }

    /**
     * Normalizes the given path.
     *
     * @param string $path The path to normalize.
     * @param string $server_document_root The server document root.
     * @return string The normalized path.
     */
    public static function normalizePath($path, $server_document_root)
    {
        // Replace backslashes with slashes
        $path = str_replace('\\', '/', $path);
        // Find the position of the document root in the path
        $root_position = strpos($path, $server_document_root);
        if ($root_position !== false) {
            // Truncate after the document root
            $path = substr($path, $root_position + strlen($server_document_root));
        }
        return $path;
    }

    /**
     * Retrieves the normalized paths.
     *
     * @return array The array of normalized paths.
     */
    public function getNormalizedPaths()
    {
        return $this->normalized_paths;
    }

    public function getProjectInfo()
    {
        return $this->project_info;
    }
}

// Instantiate the PathManager class
$path_manager = new PathManager();

// If the form is submitted, redirect to the selected path
if (isset($_POST['path'])) {
    $selectedPath = $_POST['path'];
    header('Location: ' . $selectedPath);
    exit();
}