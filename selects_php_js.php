<?php



//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
//require 'vendor/autoload.php';
//require 'secret.php';
require 'setting.php';
//ini_set('display_errors', 1);
//ini_set('display_atartup_errors',1);
//ini_set('error_reporting', E_ALL);
mb_internal_encoding("UTF-8");



//Передаем массивы дынных для построения форм по ajax php -> js
$arrays_from_settings = array(
    'womens' => $womens,
    'mens' => $mens,
    'genders' => $genders,

    'places' => $places,
    'injections' => $injections,
    'breeds' => $breeds);
echo json_encode($arrays_from_settings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES |JSON_NUMERIC_CHECK );

?>