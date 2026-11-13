<?php
declare(strict_types=1);

// Connect with new PDO($dsn, $username, $password)

$dsn = 'mysql:host=localhost;dbname=campus_club;charset=utf8mb4';
$username = 'root';
$password = '';

// This is the PDO object
$db = new PDO($dsn, $username, $password);

try{
    $db = new PDO($dsn, $username, $password);
    echo('Successfully linked to database.');
} catch (PDOException $e){
    echo($e->getMessage());
}
?>