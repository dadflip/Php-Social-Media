<?php
    include("../templates/databaseFunc.php");
    include "../templates/test.php";
    $cuicui_sess = new CuicuiSession();

    $protectedText = CuicuiDB::SecurizeString_ForSQL($_GET["search"]);
    if ($protectedText != "") {
        $query = "SELECT UID,username FROM `user` WHERE username LIKE '%$protectedText%'";
        $result = $cuicui_sess->cuicui_db->query($query);

        if($result == false) {
            echo "None";
            return;
        }

        if ($result->num_rows > 0) {
            $i = 1;
            while( ($row = $result->fetch_assoc()) && ($i < 4) ){
                $uid = $row["UID"];
                echo '<a href="./userprofile.php?UID='.$row["UID"].'">'.$row["username"].'</a>';
                if ($i < $result->num_rows) {
                    echo " - ";
                }
                $i++;
            }
        }
        else {
            echo 'None';
        }
    }
    else{
        echo 'None';
    }
?>