<?php
session_start();

$servername = "localhost";
$dbname = "login_p4"; // Ensure this matches your actual database name
$username = "root";
$password = "";
$tableName = "users";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: ". $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];

    // Hash the password
    $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

    // Insert into the specified table
    $sql = "INSERT INTO $tableName (email, username, password) VALUES (:email, :username, :pwd)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":pwd", $hashed_password);
    $stmt->execute();

    // Close connection and statement
    $conn = null;
    $stmt = null;

    // Redirect to index.html after successful registration
    header("Location: ../index.html");
    die();
} else {
    // Redirect to index.html if accessed directly without POST request
    header("Location: ../index.html");
}
?>
