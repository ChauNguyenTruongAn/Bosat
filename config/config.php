<?php
$dbname = "CuaHangBoSat";
$serverName = "localhost";
$username = "root";
$password = "";
try {
    $conn = new PDO("mysql:host=$serverName;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connect fail config: " . $e->getMessage();
}
?>