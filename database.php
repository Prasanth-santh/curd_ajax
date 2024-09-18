<?php

$dbhostname="localhost";
$dbuser="root";
$dbpassword="";
$dbname="crud_grid";
$conn=mysqli_connect($dbhostname,$dbuser,$dbpassword,$dbname);
if(!$conn){
    die("someting went wrong");
}
?>