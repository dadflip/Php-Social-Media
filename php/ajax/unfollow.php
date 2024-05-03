<?php

require "../templates/databaseFunc.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_GET["target"]) && isset($_GET["follower"])) {
    $cuicui_sess = new CuicuiSession();

    $already_follow = $cuicui_sess->cuicui_db->getFollow($_GET["follower"], $_GET["target"]);
    if(!$already_follow) {
        echo "not followed";
        return;
    }
    try {
        $cuicui_sess->deleteFollow($_GET["follower"], $_GET["target"]);
    }
    catch(mysqli_sql_exception $e) {
        echo $cuicui_sess->cuicui_db->getMysqliError();
    }
    echo "ok";
    return;
}
echo "failure";
?>