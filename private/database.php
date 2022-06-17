<?php 

define('DB_NAME', 'collect_complaints_web_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_HOST', 'localhost');

// for using only hard coded user validations
// if (!$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME))
// {
//     //die("Failed to connect!");
// }


//Connection for prepared statement

$string = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
if (!$connection = new PDO($string, DB_USER, DB_PASS))
{
    die("Failed to connect!");
}