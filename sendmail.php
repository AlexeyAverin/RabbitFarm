<?php

//Добрый день!!!

// Скрипт отправки почты!!!!!

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'secret.php';






$mail = new PHPMailer(true);
$mail->From = 'yvp777@list.ru';
$mail->FromName ='Rabbit Farm';
$mail->addAddress('yvp777@list.ru', 'Фермер');
$mail->isHTML(true);

$mail->CharSet = 'UTF-8';
$mail->SMTPDebug = 0;
$mail->isSMTP();
$mail->Host = 'smtp.list.ru';
$mail->SMTPAuth = true;
$mail->Username = $mail_user;
$mail->Password = $mail_pass;
$mail->SMTPSecure = 'ssl';

$mail->Port = 465;
$mail->Subject = 'Письмо от RabbitFarm';
$mail->Body = 'Good Day!!! Проверка почтового сообщения!!!';
$mail->send();
?>