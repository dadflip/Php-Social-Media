<?php
// Load necessary definitions
require_once __DIR__.'/defs.php';

/**
 * Determines the language based on the HTTP_ACCEPT_LANGUAGE header.
 * Sets the global LANG variable.
 *
 * @return string The determined language code.
 */
function determineLanguage() {
    if (!isset($GLOBALS['LANG'])) {
        $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $GLOBALS['LANG'] = $language;
    }
    return $GLOBALS['LANG'];
}

/**
 * Resolves the path by going back a specified number of directories.
 *
 * @param int $count The number of directories to go back.
 * @return string The resolved directory path.
 */
function resolveBack($count) {
    $currentDir = realpath(__DIR__);
    for ($i = 0; $i < $count; $i++) {
        $currentDir = dirname($currentDir);
    }
    return $currentDir;
}

/**
 * Includes a file if it exists, using provided segments to construct the file path.
 *
 * @param mixed ...$segments The segments of the file path, including constants and strings.
 * @return void
 */
function includeIfDefined(...$segments) {
    if (empty($segments)) {
        echo "Error: No segments provided.<br>";
        return;
    }

    $filePath = '';
    foreach ($segments as $segment) {
        if (is_string($segment)) {
            if (substr($segment, 0, 5) === 'back(' && substr($segment, -1) === ')') {
                $count = intval(substr($segment, 5, -1));
                $resolvedPath = resolveBack($count);
                $filePath .= $resolvedPath;
            } else {
                $filePath .= $segment;
            }
        } else {
            $filePath .= constant($segment[0]) . $segment[1];
        }
    }

    if (file_exists($filePath)) {
        require $filePath;
    } else {
        echo "Error: File not found at '$filePath'.<br>";
    }
}

/**
 * Extracts the base directory name from a path.
 *
 * @param string $path The path.
 * @return string The base directory name.
 */
function baseDir($path) {
    $path = rtrim($path, '/');
    $lastFolder = '/' . basename($path);
    return $lastFolder;
}

// ------------
// Determine the language
determineLanguage();
