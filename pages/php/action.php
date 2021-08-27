<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST["submit"])) {
  //recebendo inputs e Limpando inputs
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
  $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
  $from = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $subject=filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
  $to='WHO WILL RECIVE THE EMAIL';
  try {
    $email = new PHPMailer(true);

    $email->isSMTP();
    $email->Port = "465";
    $email->Host = "smtp.gmail.com";
    $email->isHTML(true);
    $email->SMTPSecure = "ssl";
    $email->Mailer = "smtp";
    $email->CharSet = "UTF-8";
    $email->SMTPAuth = true;
    $email->Username = "your email";
    $email->Password = "your password";
    $email->SingleTo = true;

    $email->AddAddress($to);//to
    $email->AddReplyTo($from);
    $email->SetFrom($from);
    $email->Subject = $subject;
    $email->Body = $message;
    if (!$email->send()) {
      $_SESSION["mensagem"] = "Não foi possivel enviar o email";
      header("Location: contato");
    } else {
      $_SESSION["mensagem"] = "Email enviado com sucesso";
      header("Location: contato");
    }
  } catch (Exception $e) {
    $_SESSION["mensagem"] = $e->getMessage();
    header("Location: contato");
  }
}
