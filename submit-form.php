<?php

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../mail-info.php';
require '/home/dh_nni3e9/zackmdesigns.com/vendor/autoload.php';

date_default_timezone_set('America/Los_Angeles');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //validate user input
    $name = $phone = $email = $error = "";
    //email
    if (empty($_POST["contact_email"])) {
        $error = "Email is required";
    } else {
        $email = validate_input($_POST["contact_email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format";
        }
    }
    //phone
    if (empty($_POST["contact_phone"])) {
        $error = "Phone is required";
    } else {
        $phone = validate_input($_POST["contact_phone"]);
    }
    //name
    if (empty($_POST["contact_name"])) {
        $error = "Name is required";
    } else {
        $name = validate_input($_POST["contact_name"]);
    }
    echo $error;

    //send email
    $mail = new PHPMailer(true);

    try {
        //$mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.dreamhost.com';
        $mail->SMTPAuth = true;
        $mail->Username = $mail_user;
        $mail->Password = $mail_pass;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('zack@zackmdesigns.com', 'Contact Form');
        $mail->addAddress('zackmdesigns@gmail.com', 'Zack');

        if ($mail->addReplyTo($email, $name)) {
            $mail->Subject = 'Contact Form';
            //Keep it simple - don't use HTML
            $mail->isHTML(false);
            //Build a simple message body
            $mail->Body = <<<EOT
Email: {$email}
Name: {$name}
Phone: {$phone}
EOT;

            if (!$mail->send()) {
                //The reason for failing to send will be in $mail->ErrorInfo
                //but you shouldn't display errors to users - process the error, log it on your server.
                $msg = 'Sorry, something went wrong. Please try again later.';
            }
            else {
                $msg = 'Message sent!';
            }
        }
        else {
            $msg = 'Invalid email address.';
        }

    }
    catch (Exception $e) {

    }
    echo $msg;
}

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>