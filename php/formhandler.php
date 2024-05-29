<?php
session_start();
$servername = "localhost";
$dbname = "login-p4";
$username = "root";
$password = "";
$tableName = "users"; // Specify the table name here

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: ". $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $pwd = $_POST["pwd"];

  $sql = "SELECT * FROM $tableName WHERE username = :username"; // Use $tableName variable
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":username", $username);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($pwd, $user['password'])) {
    // Login successful, set session variables
    $_SESSION['username'] = $username;
    $_SESSION['coins'] = $user['coins'];
    $_SESSION['invest'] = $user['invest'];

    header("Location: ../index.html"); // Redirect to homepage or wherever you want
    exit();
  } else {
    // Login failed
    header("Location: ../index.html"); // Redirect back to login page
    exit();
  }
} else {
  header("Location: ../index.html"); // Redirect back to login page if accessed directly
  exit();
}
?>
