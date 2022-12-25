<?php
require 'new file/PHPMailer.php';
require 'new file/SMTP.php';
require 'new file/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function Email_Varification($email){
    $token = bin2hex(random_bytes(50));
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "Smtp.gmail.com";
    $mail->SMTPAuth = "true";
    $mail->isHTML(true); 
    $mail->Username = "your email";
    $mail->Password =  "pasasword";
    $mail->Subject  = "gmail verification";
    $mail->setFrom("your email");
    $mail->Body = '<h1>Click on the link below to activate your Bitly Account</h1><a href="http://localhost/internhip-training/activate.php?token='.$token.'" style="text-decoration:none; color: white; border-radius: 5px; padding: 10px 20px; background-color: red;">CLICK HERE</a>';
    $mail->addAddress($email);
    if($mail->send()){
        $mail->smtpClose();
        return array(true, $token);
    }else{
        $mail->smtpClose();
        return false;
    }
}
?>

