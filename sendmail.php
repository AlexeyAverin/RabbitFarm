<?php

//Добрый день!!!

// Скрипт отправки почты!!!!!

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'secret.php';
require 'functor.php';





// Отправка письма
$mail_msg = 'Добрый день!!!';
get_msg_mail( $mail_account, $mens, $womens, $mail_msg, $injections_limit_day, $injections, $mysql );
?>