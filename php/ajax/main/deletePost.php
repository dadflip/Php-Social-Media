<?php
if(!isset($_POST["postID"]) || !isset($_POST["adminID"])) {
    echo "unset";
    return;
}

include '../../../app/defs.functions.php';
includeIfDefined('back(0)', baseDir($appdir['PATH_MODULES']) . $phpfile['CuicuiManager']);

$cuicui_manager = new CuicuiManager($database_configs, DATASET);
try {
    $cuicui_manager->deletePost($_POST["postID"],$_POST["adminID"]);
}
catch(NotAnAdminException $ex) {
    echo $ex->getMessage();
    return;
}
echo "ok";