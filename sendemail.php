<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
require_once 'phpmailer/Exception.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';

$servername = "localhost";
$dbname = "login-p4";
$username = "root";
$password = "";
$tableName = "users"; // Adjust the table name

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
        // Generate a unique verification code
        $verificationCode = random_int(100000, 999999);

        // Store the verification code in the database
        $stmt = $conn->prepare("UPDATE $tableName SET reset_code = :reset_code WHERE email = :email");
        $stmt->bindParam(":reset_code", $verificationCode);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // Send the verification code email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'luukstaal12@gmail.com'; // Gmail address which you want to use as SMTP server
            $mail->Password = 'gnip etzf mpdt ytgr'; // Gmail address Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = '587';

            $mail->setFrom('luukstaal12@gmail.com'); // Gmail address which you used as SMTP server
            $mail->addAddress($email); // Email address where you want to receive emails

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Code';
            $mail->Body = "<h3>Your password reset code is: $verificationCode</h3>";

            $mail->send();
            header("Location: password_reset_code_sent.html");
            exit();
        } catch (Exception $e) {
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
