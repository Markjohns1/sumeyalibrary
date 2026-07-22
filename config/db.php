<?php
$host="localhost";
$user="root";
$pass="";
$dbname="library_system"; //not a table name
$port="3307";
try{
 $pdo =new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //error reporting
 //echo "Connected successfully";
}
catch(PDOException $error){
die("Connection failed" . $error->getMessage());
    
}

?>