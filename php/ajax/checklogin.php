<?php
$cuicui_sess = new CuicuiSession();
$connection_success = $cuicui_sess->cuicui_db->connect_user();

if($connection_success->getLoginStatus() == false && $connection_success->getText() != "") {
    echo "<p class='error-msg'>".$connection_success->getText()."</p>";
}
?>