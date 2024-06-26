<?php
session_start();

// Include your database connection setup here
$servername = "localhost";
$dbname = "login-p4"; // Adjusted database name
$username = "root";
$password = "";
$tableName = "users";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $reset_token = $_POST["reset_token"];
    $new_password = $_POST["new_password"];

    // Validate the reset token and email
    $stmt = $conn->prepare("SELECT * FROM $tableName WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($reset_token, $user['reset_code'])) {
        // Token matches, proceed with password reset
        // Hash the new password for security
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update_stmt = $conn->prepare("UPDATE $tableName SET password = :hashed_password, reset_code = NULL WHERE email = :email");
        $update_stmt->bindParam(":hashed_password", $hashed_password);
        $update_stmt->bindParam(":email", $email);
        $update_stmt->execute();

        // Redirect to a success page
        header("Location: ../index.html");
        exit();
    } else {
        // Redirect to an error page or back to the reset password form with an error message
        header("Location: reset_password.html?error=invalid_reset_token");
        exit();
    }
} else {
    // Handle method not allowed error (send a 405 response)
    http_response_code(405);
    echo "Method Not Allowed";
    exit();
}
?>
