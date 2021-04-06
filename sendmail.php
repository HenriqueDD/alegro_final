<?php

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST)) {
    $data = $_POST;

    $name = filter_var($data["name"], FILTER_SANITIZE_STRING);
    $phone = filter_var($data["phone"], FILTER_SANITIZE_STRING);
    $email = filter_var($data["email"], FILTER_SANITIZE_EMAIL);
    $message = filter_var($data["message"], FILTER_SANITIZE_STRING);

    $error = "";

    if (empty($name)) $error .= "<p>Insira um nome válido!</p>";
    if (empty($phone)) $error .= "<p>Insira um telefone válido!</p>";
    if (empty($email)) $error .= "<p>Insira um email válido!</p>";
    if (empty($message)) $error .= "<p>Insira uma mensagem válida!</p>";

    if ($error !== "") return json_encode(["status" => "error", "message" => $error]);
    else {
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'exemplo@gmail.com';
        $mail->Password = 'senha';
        $mail->Port = 587;

        $mail->setFrom('email@gmail.com', 'Contato');
        $mail->addAddress('email@mail.com.br');

        $mail->isHTML(true);

        $mail->Subject = "Mensagem enviado pelo site Alegro Odonto";

        $content  = "<h1>Contato pelo site Alegro Odonto</h1>";
        $content .= "<h4>Nome:</h4> <p>{$name}</p>";
        $content .= "<h4>Telefone/Celular:</h4> <p>{$phone}</p>";
        $content .= "<h4>Email:</h4> <p>{$email}</p>";
        $content .= "<h4>Mensagem:</h4> <p>{$message}</p>";

        $mail->Body = nl2br($content);
        $mail->AltBody = nl2br(strip_tags($content));

        if (!$mail->send()) {
            return json_encode(["status" => "error", "message" => $mail->ErrorInfo]);
        } else {
            return json_encode(["status" => "success", "message" => "Sua mensagem foi enviada com sucesso!"]);
        }
    }
}