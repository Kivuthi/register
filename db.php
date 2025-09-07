<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$dbname = "register";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("conection failed: " . $conn->connect_error);
} 
header("Location:register.php");
exit();
?>