<?php

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST["username"]; 
    $pwd = $_POST["pwd"];
    $email = $_POST["email"];
    
    $servername = "localhost";
    $dbname = "login-p4";
    $username1 = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username1, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

        $query = "INSERT INTO login (username, password, email) VALUES (:username, :pwd, :email)";
        $stmt = $pdo->prepare($query);
        
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":pwd", $hashed_password);
        $stmt->bindParam(":email", $email);
        
        $stmt->execute();

        $pdo = NULL;
        $stmt = NULL;

        header("Location: ../index.html");
        die();
    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
}
else {
    header("Location: ../index.html");
}
