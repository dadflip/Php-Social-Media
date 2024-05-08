<?php

// Define global variables for SQL and JSON paths
$GLOBALS['__var_sql_path__'] = '/../../../sql';
$GLOBALS['__json_path__'] = '/../json/';

/**
 * Recursively scans a directory for files and subdirectories.
 * 
 * @param string $dir The directory path to scan.
 * @return array An array containing files and subdirectories.
 */
function scanDirectory($dir) {
    $files = [];
    $subdirectories = [];

    // Check if directory is readable
    if (!is_readable($dir)) {
        echo "Error: Permission denied to access directory $dir";
        return ['files' => $files, 'subdirectories' => $subdirectories];
    }

    // Open the directory
    $handle = opendir($dir);

    // Read directory contents
    while (($file = readdir($handle)) !== false) {
        if ($file != '.' && $file != '..') {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                // If it's a directory, add to subdirectories list and recursively scan
                $subdirectories[$file] = scanDirectory($path);
            } else {
                // If it's a file, add to files list
                $files[] = $file;
            }
        }
    }

    closedir($handle);

    // Return files and subdirectories
    return ['files' => $files, 'subdirectories' => $subdirectories];
}

// Get the root directory path
$rootDirectory = dirname(__DIR__);

// Scan the root directory
$directoryStructure = scanDirectory($rootDirectory);

// Save the directory structure to a JSON file
file_put_contents(__DIR__.$GLOBALS['__json_path__'].'directory_structure.json', json_encode($directoryStructure, JSON_PRETTY_PRINT));

// Function to create PHP variables for files
function createPhpVariables($directoryStructure, $currentPath = '') {
    $phpFiles = [];

    // Iterate over files in the current directory
    foreach ($directoryStructure['files'] as $file) {
        // Check if file is a .php file
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            // Create a variable for the .php file
            $variableName = pathinfo($file, PATHINFO_FILENAME);
            $filePath = $currentPath . '/' . $file;
            // Remove the name of the first folder from the path
            $adjustedPath = preg_replace('/\/[^\/]+\//', '/', $filePath, 1);
            ${$variableName} = $adjustedPath;
            $phpFiles[$variableName] = $adjustedPath;
        }
    }

    // Iterate over subdirectories
    foreach ($directoryStructure['subdirectories'] as $subdirectoryName => $subdirectory) {
        // Build the full path of the subdirectory
        $subdirectoryPath = $currentPath . '/' . $subdirectoryName;
        // Recursively call the function for each subdirectory
        $phpFiles = array_merge($phpFiles, createPhpVariables($subdirectory, $subdirectoryPath));
    }

    return $phpFiles;
}

// Load content of directory_structure.json
$directoryStructureJson = file_get_contents(__DIR__.$GLOBALS['__json_path__'].'directory_structure.json');
$directoryStructure = json_decode($directoryStructureJson, true);

// Create variables for .php files
$phpFiles = createPhpVariables($directoryStructure);

// Save variables to a JSON file
file_put_contents(__DIR__.$GLOBALS['__json_path__'].'php_files.json', json_encode($phpFiles, JSON_PRETTY_PRINT));
