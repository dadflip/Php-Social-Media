<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../defs.functions.php';
echo '<button onclick="window.location.href=\'' . $appdir['PATH_CUICUI_APP'] . '/admin/database/db_config.php\'">Configurer</button>';