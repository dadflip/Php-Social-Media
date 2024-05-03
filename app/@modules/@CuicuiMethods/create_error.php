<?php
function create_error($lang, $error_type, $error_msg): string {
    $file_str = file_get_contents("../$lang/templates/error_template.php");
    if($file_str == false) { die ("Error error"); }
    $end_str = str_replace("{{error}}", $error_type, $file_str);
    $end_str = str_replace("{{msg}}", $error_msg, $end_str);
    return $end_str;
}
?>

