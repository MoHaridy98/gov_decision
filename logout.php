<?php
require 'connection.php';
session_start();
$username = $_SESSION['user_id'];
$sql = "UPDATE users set state = 0 WHERE username = '$username';";
$users = mysqli_query($conn,$sql);
unset($_SESSION['user_id']);
unset($_SESSION['user_role']);
session_unset();
header("Location: login.php");
?>