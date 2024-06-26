<?php
session_start();

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../phpmailer/Exception.php';
require_once '../phpmailer/PHPMailer.php';
require_once '../phpmailer/SMTP.php';

// Database connection details
$servername = "localhost";
$dbname = "login-p4";
$username = "root";
$password = "";
$tableName = "users";

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM $tableName WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Generate a verification code
        $verificationCode = random_int(100000, 999999);

        // Hash the verification code before storing in the database
        $hashedVerificationCode = password_hash($verificationCode, PASSWORD_DEFAULT);

        // Update the database with the hashed verification code
        $stmt = $conn->prepare("UPDATE $tableName SET reset_code = :reset_code WHERE email = :email");
        $stmt->bindParam(":reset_code", $hashedVerificationCode);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // Send email with the verification code
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'luukstaal12@gmail.com'; // Gmail address which you want to use as SMTP server
            $mail->Password = 'gnip etzf mpdt ytgr'; // Gmail address Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = '587';

            // Email content
            $mail->setFrom('your_email@gmail.com', 'Your Name');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body = "Your verification code is: $verificationCode";

            // Send email
            $mail->send();

            // Redirect to reset_password.html with email parameter
            header("Location: ../html/reset_password.html?email=" . urlencode($email));
            exit();
        } catch (Exception $e) {
            // Handle email send error
            header("Location: email_send_error.html");
            exit();
        }
    } else {
        // Redirect if email not found in database
        header("Location: email_not_found.html");
        exit();
    }
} else {
    // Redirect if accessed directly
    header("Location: ../index.html");
    exit();
}
?>
