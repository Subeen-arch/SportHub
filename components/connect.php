<?php
$host = '127.0.0.1'; //local host url 
$port = 3306; //
$dbname = 'shop_db';
$username = 'root';
$password = '#Subin000';

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}
?>