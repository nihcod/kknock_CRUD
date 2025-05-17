<?php
$sname= "db";
$uname= "root";
$password="root";

$db_name = "user_info";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn){
    echo("Connection failed!");

}
?>