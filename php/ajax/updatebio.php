<?php
require "../templates/databaseFunc.php";
require "../templates/test.php";

$cuicui_sess = new CuicuiSession();

if(isset($_GET["bio"])) {
    $cuicui_sess->updateBio($_GET["bio"],$_SESSION["UID"]);
    echo "ok";
}
else {
    echo "err";
}


?>
