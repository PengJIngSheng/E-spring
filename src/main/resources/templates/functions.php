<?php

use PHPMailer\PHPMailer\PHPMailer;

function sendMail($to, $title, $name, $email, $contact, $message)
{
    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "yynarrator@gmail.com";
    $mail->Password = 'rleovrsouxvtwqst';
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->isHTML(true);
    $mail->setFrom = 'yynarrator@gmail.com';
    $mail->addAddress($to, 'xym在线通知');
    $mail->Subject = $title;
    $mail->Body = "Name: $name<br>Email: $email<br>Contact: $contact<br>Message: $message";
    $status = $mail->send();
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function sendWelcomeEmail($to, $title, $firstname)
{
    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "yynarrator@gmail.com";
    $mail->Password = 'rleovrsouxvtwqst';
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->isHTML(true);
    $mail->setFrom = 'yynarrator@gmail.com';
    $mail->addAddress($to);
    $mail->Subject = "Welcome to Cafe Caborana";
    $mail->Body = "Dear $title $firstname,<br><br>Welcome to Cafe Bibliotoca!<br><br>Thank you for registering with us. We are excited to have you as a member of our community.<br><br>Best regards,<br>The Cafe Caborana Team";
    $status = $mail->send();
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function sendRecipt($to, $title, $firstname, $card, $date, $cvv, $cartItems, $totalPrice)
{
    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "yynarrator@gmail.com";
    $mail->Password = 'rleovrsouxvtwqst';
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->isHTML(true);
    $mail->setFrom('yynarrator@gmail.com');
    $mail->addAddress($to, 'User');
    $mail->Subject = "Receipt from Cafe Caborana";

    // Create the email body with cart items and total price
    $body = "Dear $title $firstname,<br><br>Welcome to Cafe Bibliotoca!<br><br>";
    $body .= "Here is your receipt:<br><br>";

    foreach ($cartItems as $item) {
        $productname = $item['productname'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $itemTotal = $price * $quantity;

        $body .= "Product: $productname, Price: $price, Quantity: $quantity, Item Total: $itemTotal<br>";
    }

    $body .= "Total Price: $totalPrice<br><br>";
    $body .= "Best regards,<br>The Cafe Caborana Team";

    $mail->Body = $body;

    $status = $mail->send();

    if ($status) {
        return true;
    } else {
        return false;
    }
}








