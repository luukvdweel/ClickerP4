<?php
session_start();
$servername = "localhost";
$dbname = "login-p4";
$username = "root";
$password = "";
$tableName = "users"; // Change this variable to adjust the table name

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: ". $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];

  // Check if the email exists in the database
  $stmt = $conn->prepare("SELECT * FROM $tableName WHERE email = :email");
  $stmt->bindParam(":email", $email);
  $stmt->execute();
  $user = $stmt->fetch();

  if ($user) {
    // Generate a unique token
    $token = bin2hex(random_bytes(32));

    // Store the token in the database
    $stmt = $conn->prepare("UPDATE $tableName SET reset_token = :token WHERE email = :email");
    $stmt->bindParam(":token", $token);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    // Send the password reset email
    $resetLink = "https://yourwebsite.com/reset_password.php?token=$token";
    $subject = "Password Reset";
    $message = "Click the following link to reset your password: $resetLink";
    $headers = "From: luukvanderweel@outlook.com"; // Replace with your email address

    if (mail($email, $subject, $message, $headers)) {
      // Email sent successfully
      header("Location: password_reset_sent.html");
      exit();
    } else {
      // Email failed to send
      header("Location: email_send_error.html");
      exit();
    }
  } else {
    // Redirect the user to a page indicating that the email address was not found
    header("Location: email_not_found.html");
    exit();
  }
} else {
  // Redirect the user to the home page if they accessed this script directly without submitting the form
  header("Location: ../index.html");
  exit();
}
?>
