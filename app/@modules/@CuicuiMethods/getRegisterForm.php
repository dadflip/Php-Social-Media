<?php

function getRegisterForm_1() {
    $path = '../../templates';
    return file_get_contents($path . $GLOBALS['php_files']['_RegisterForm1']);
}

function getRegisterForm_2() {
    $path = '../../templates';
    return file_get_contents($path . $GLOBALS['php_files']['_RegisterForm2']);
}
