<?php

include 'functions.php';

function class_autoloader($class) {
    include_once 'classes/' . $class . '.class.php';
}

session_start();

spl_autoload_register('class_autoloader');

$database = new PDO('mysql:host=csweb.hh.nku.edu;dbname=db_fall19_stephensow1', 'stephensow1', '63fcdc7a');
