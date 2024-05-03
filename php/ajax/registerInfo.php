<?php

require "../templates/registerGetStep.php";


if(isset($_GET["lang"])) {
    
    $response = getSecondStep($_GET["lang"]);

    if($response == false) {
        echo "failure";
        return;
    }

    echo $response;
    return;
}

?>