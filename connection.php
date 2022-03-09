<?php 

//database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname  = "gov_decision";

$conn = new mysqli($servername,$username,$password,$dbname) or die("Connect failed: %s\n". $conn -> error);
?>