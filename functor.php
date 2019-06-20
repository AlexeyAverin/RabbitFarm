<?php



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'secret.php';
ini_set('display_errors', 1);
ini_set('display_atartup_errors',1);
ini_set('error_reporting', E_ALL);
mb_internal_encoding("UTF-8");




// Массив женских имен
$womens = array('Крольчиха Мать', 'Нет данных');
// Массив мужский имен
$mens = array('Кролик Отец', 'Нет данных');
//Массив клеток

$places = array('Клетка 01', 'Клетка 02', 'Клетка 03', 'Клетка 05', 'Клетка 06');
//Массив полов
$genders = array('M', 'W');
//Массив прививок (дни)
$injections = array('ABC' => 180, 'EFG' => 90, 'HKL' => 3650);
// Количество дней за которое начинаются формироваться письма
$injections_limit_day = 10*500;
// Массив пород

$breeds = array('Беспородная', 'Калифорнийская');
// Массив окролов
$breedingid = array('1', '2', '3');






// Отправляет письмо
function sender_mail($mail_user, $mail_pass, $mail_msg){ //echo 'Добрый день!!!';
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
    $mail->Body = $mail_msg; //'Good Day!!! Проверка почтового сообщения!!!';
    $mail->send();

    //echo $mail->ErrorInfo; //if ( $mail->send() ) { echo 'Добрый день!!! Письмо!!!'; }

}

// Дата следующей прививки
function date_next_injection($date, $interval){
    $date = new DateTime($date);
    $interval = 'P'.trim($interval).'D';
    $date->add(new DateInterval($interval));
    return $date->format('d-m-Y');
}


// Возращает количество дней до прививки
function days_priorto_injection($date, $interval){
    $date_next = new DateTime(date_next_injection($date, $interval));
    $date_now = new DateTime('now');

    $days = $date_now->diff($date_next);
    return $days->format('%a');
}


// Оборачивает колличество дней в теги html и возращает если дней осталось меньше чем $injections_limit_day
function wrapper_days_prior_to_injection($date, $interval, $injections_limit_day, $mail_user, $mail_pass, $rabbit_name){
    $days = days_priorto_injection($date, $interval);
    if ( $days <= $injections_limit_day ) {
        $days = '<em>'.$days.'</em>';
        $mail_msg = "<div style='color: orange;'>Добрый день, Виталька!!!<br />Здесь будет информация о кроликах!!!<br />$rabbit_name</div>";

        //sender_mail($mail_user, $mail_pass, $mail_msg);
        return $days;
    }   
}

// Соединение с MySQL
function connect_mysql( $mysql ){
    $connect_mysql = new mysqli( $mysql['node'], $mysql['user'], $mysql['passwd'], $mysql['dbase']);
    if ( $connect_mysql->connect_error ) die ( $connect_mysql->connect_error );

    return $connect_mysql;
}

// Посыл запроса MySQL
function send_query_mysql( $connect_mysql, $query_mysql ){

    $results_mysql = $connect_mysql->query($query_mysql);
    if ( !$results_mysql ) die ( $connect_mysql->connect_error );
    return $results_mysql;
}

// Считывание данных зайцев из mysql
function array_from_mysql( $mysql, $mens, $womens ){ //"Добрый день!!!"
    $connect_mysql = connect_mysql( $mysql );
    $query_mysql = 'SELECT * FROM rabbits;';

    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    $rows_mysql = $results_mysql->num_rows;
    for ( $i = 0; $i < $rows_mysql; ++$i ) {
        
        $results_mysql->data_seek($i);
        $string_musql = $results_mysql->fetch_array(MYSQLI_ASSOC);
        $id = $string_musql['id'];
        $name = $string_musql['name']; //echo mb_detect_encoding($name)."<br />";
        $type = $string_musql['type'];

        $breedingid = $string_musql['breedingid'];
        $breed = $string_musql['breed'];
        $birthdate = $string_musql['birthdate'];
        $gender = $string_musql['gender'];
        $label = $string_musql['label'];
        $women = $string_musql['women'];
        $men = $string_musql['men'];
        $place = $string_musql['place'];
        $injectiondate = $string_musql['injectiondate'];
        $injectiontype = $string_musql['injectiontype'];
        $arr =  array( $name, $type, $breedingid, $breed, $birthdate, $gender, $label, $women, $men, $place, $injectiondate, $injectiontype );
        $rabbits[$id] = $arr;



        if ( $gender == 'M' ) {
            $mens[] = $name;
        }
        if ( $gender == 'W' ) {
 
            $womens[] = $name;
        }
    }

    $results_mysql->close();
    $connect_mysql->close();
    return $rabbits;
}

// Добавляем нового зайца в MySQL
function string_to_mysql( $mysql ){
    $connect_mysql = new mysqli( $mysql['node'], $mysql['user'], $mysql['passwd'], $mysql['dbase']);

    if ( $connect_mysql->connect_error ) die ( $connect_mysql->connect_error );
    $query_mysql = 'INSERT INTO rabbits (name, type, breedingid, breed, birthdate, gender, label, women, men, place, injectiondate, injectiontype) VALUES ("'.$_GET['name'].'", "", "'.$_GET['breedingid'].'", "'.$_GET['breed'].'", "'.$_GET['birth'].'", "'.$_GET['gender'].'", "'.$_GET['label'].'", "'.$_GET['women'].'", "'.$_GET['men'].'", "'.$_GET['place'].'", "'.$_GET['injectiondate'].'", "'.$_GET['injectiontype'].'");';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    if ( !$results_mysql ) die ( $connect_mysql->connect_error );
    //$results_mysql->close();
    $connect_mysql->close();
}

// Изменение данных зайца в MySQL
function update_string_mysql( $mysql, $rabbit_id ){
    $connect_mysql = new mysqli( $mysql['node'], $mysql['user'], $mysql['passwd'], $mysql['dbase']);    

    if ( $connect_mysql->connect_error ) die ( $connect_mysql->connect_error );
    $query_mysql = 'UPDATE rabbits SET name="'.$_GET['name'].'", type="", breedingid="'.$_GET['breedingid'].'", breed="'.$_GET['breed'].'", birthdate="'.$_GET['birth'].'", gender="'.$_GET['gender'].'", label="'.$_GET['label'].'", women="'.$_GET['women'].'", men="'.$_GET['men'].'", place="'.$_GET['place'].'", injectiondate="'.$_GET['injectiondate'].'", injectiontype="'.$_GET['injectiontype'].'" WHERE id="'.$rabbit_id.'";';
    //echo $query_mysql;
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );

    if ( !$results_mysql ) die ( $connect_mysql->connect_error );

    //$results_mysql->close();
    $connect_mysql->close();
}

// Удаление данных зайца из MySQL
function string_delete_mysql( $mysql, $rabbit_id ){
    $connect_mysql = connect_mysql( $mysql );
    $query_mysql = 'DELETE FROM rabbits WHERE id="'.$rabbit_id.'";';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    //$results_mysql->close();
    $connect_mysql->close();
    
}

// Формирование ассоциативное Select
function fill_ass_select($array, $name, $id, $value){
    $tag = '';
    foreach ( $array as $type => $time ) {
        if ( $type == $value ) {
                $tag .= "<option selected value='$time' >$type</option>";
        }
        else {
            $tag .= "<option value='$time'>$type</option>";
        }
    }
    $tag = "<select id='$id' name='$name'>$tag</select>";
    return $tag;
}


// Формирование простое Select
function fill_select( $array, $name, $value ){
    $tag = '';
    foreach ( $array as $item ) {
        if ( $item == $value ) {
            $tag .= "<option key='$item' val='$value' selected>$item</option>";
        }
        else {
            $tag .= "<option key='$item' val='$value'>$item</option>";
        }
     }
     $tag = "<select id='$name' name='$name'>$tag</select>";
    return $tag;
}
?>