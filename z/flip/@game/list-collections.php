<?php
$directory = 'collections';
$collections = array_diff(scandir($directory), array('..', '.'));

echo json_encode($collections);
?>
