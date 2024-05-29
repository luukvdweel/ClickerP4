<?php

session_start();
$servername = "localhost";
$dbname = "login-p4";
$username = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: ". $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $pwd = $_POST["pwd"];

  // Hash the password
  $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

  $sql = "INSERT INTO login (username, password) VALUES (:username, :pwd)";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":username", $username);
  $stmt->bindParam(":pwd", $hashed_password);
  $stmt->execute();

  $conn = null;
  $stmt = null;

  header("Location: ../index.html");
  die();
} else {
  header("Location: ../index.html");
}
?>
