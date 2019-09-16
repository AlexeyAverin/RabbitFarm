<?php



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'secret.php';
require 'setting.php';
include 'NotORM.php';
spl_autoload_register( function ($class) { include 'classes/'.$class.'.php'; });
ini_set('display_errors', 1);
ini_set('display_atartup_errors',1);
ini_set('error_reporting', E_ALL);
mb_internal_encoding("UTF-8");


//Передаем массивы дынных для построения форм по ajax php -> js
if ( isset($_POST['metod']) && $_POST['metod'] === 'arrays_php_js' ) {
    $array = copulation_id_mysql( $mysql, $_POST['couplingid'] );
    $men = $array[0];
    $women = $array[1];

    $arrays_from_settings = array(
        'women' => $women,
        'men' => $men,
        'genders' => $genders,
    
        'places' => $places,
        'injections' => $injections_arr,
        'breeds' => $breeds);
    echo json_encode($arrays_from_settings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES |JSON_NUMERIC_CHECK );

}

if ( isset($_GET['metod']) && $_GET['metod'] === 'string_to_mysql' ) {
    rabbit_insert_dbase( $mysql, $injections_arr ); //string_to_mysql( $mysql );
}

// Отправляет письмо
function sender_mail( $mail_account, $mail_msg ){ //echo 'Добрый день!!!';
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
    $mail->Username = $mail_account['user'];

    $mail->Password = $mail_account['pass'];
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->Subject = 'Письмо от RabbitFarm';
    $mail->Body = $mail_msg; //'Good Day!!! Проверка почтового сообщения!!!';
    $mail->send();
    //echo $mail->ErrorInfo; //if ( $mail->send() ) { echo 'Добрый день!!! Письмо!!!'; }
}

// Дата следующей прививки
function date_next_injection($date, $interval, $formatdate = 0){
    //$formatdate=0 => d-m-Y - сайт отображение, $formatdate=1 => Y-m-d - dbase
    $date = new DateTime($date);
    $interval = 'P'.trim($interval).'D';
    $date->add(new DateInterval($interval));
    if ( $formatdate == 0 ) {
        return $date->format('d-m-Y');
    }
    if ( $formatdate == 1 ) {
        return $date->format('Y-m-d');

    }
}


// Возращает количество дней до прививки
function days_priorto_injection($date, $interval){
    $date_next = new DateTime(date_next_injection($date, $interval));
    $date_now = new DateTime('now');
    $days = $date_now->diff($date_next);
    return $days->format('%a');
}

function days_for_next_injection_sec($injectionfinish){
    $date_now = new DateTime('now');
    $injectionfinish = new DateTime($injectionfinish);

    $days = $date_now->diff($injectionfinish);
    return $days->format('%a');
}

// Оборачивает колличество дней в теги html и возращает если дней осталось меньше чем $injections_limit_day
function wrapper_days_prior_to_injection( $date, $interval, $injections_limit_day ){
    $days = days_priorto_injection($date, $interval);
    if ( $days <= $injections_limit_day ) {
        $days = '<em>'.$days.'</em>';

        return $days;
    }   
}

function wrapper_days_for_next_injection_sec($injectionfinish, $injections_limit_day){
    $days = days_for_next_injection_sec($injectionfinish);
    if ( $days <= $injections_limit_day ) {
        $days = '<em>'.$days.'</em>';

        return $days;
    }
}


// Функцию дергает sendmail.php которого дергает cron
function get_msg_mail( $mail_account, $mens, $womens, $mail_msg, $injections_limit_day, $injections_arr, $mysql ){
    $rabbits = array_from_mysql( $mysql, $mens, $womens )[0];

    foreach ( $rabbits as $rabbit_id => $rabbit ){
        $days = days_priorto_injection($rabbit[10], $injections_arr[trim($rabbit[11])] );
        if ( $days <= $injections_limit_day ) {
            $name = $rabbit[0];
            $mail_msg = '<p> Добрый день!!!'.$name.' '.$days.' </p>';

            sender_mail( $mail_account, $mail_msg );
        }
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

// Считывание данных зайцев
function rabbits_from_dbase( $mysql, $mens, $womens ){//"Добрый день!!!"
    try {
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        foreach($connect_dbase->query('SELECT * FROM rabbits ORDER BY status DESC, birthdate ASC') as $row){
            $id = $row['id'];
            $name = $row['name'];

            // Запрос injections
            $select_from_injections = $connect_dbase->query('SELECT * FROM injections WHERE name="'.$name.'" ORDER BY injectiondate DESC LIMIT 1;');
            $object_from_injections = $select_from_injections->fetch(PDO::FETCH_ASSOC);
            $status = $row['status'];
            $breedingid = $row['breedingid'];
            $breed = $row['breed'];
            $birthdate = $row['birthdate'];
            $gender = $row['gender'];
            $label = $row['label'];
            $women = $row['women'];
            $men = $row['men'];
            $place = $row['place'];

            $injectiondate = $object_from_injections['injectiondate'];
            $injectiontype = $object_from_injections['injectiontype'];

            $injectionfinish = $object_from_injections['injectionfinish'];
            $arr =  array( $name, $status, $breedingid, $breed, $birthdate, $gender, $label, $women, $men, $place, $injectiondate, $injectiontype, $injectionfinish );
            $rabbits[$id] = $arr;

            if ( $gender == 'M' ) {
                $mens[] = $name;
            }
            if ( $gender == 'W' ) {
                $womens[] = $name;
            }
        }        
    } catch (PDOException $e) {
        echo ("Good day!!!<br> Error: " . $e->getMessage()."<br>");
        die();
    }
    $connect_dbase = null;

    $mens = array_unique($mens);
    $womens = array_unique($womens);
    $rabbits_mens_womens = array($rabbits, $mens, $womens);
    return $rabbits_mens_womens;
}

// Добавляем нового зайца в MySQL
function rabbit_insert_dbase( $mysql, $injections_arr ){
    $_GET['status'] = isset($_GET['status']) ? $_GET['status'] : '';
    try{
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);

        $connect_dbase->beginTransaction();
        $select_rabbit_data_from_dbase = $connect_dbase->exec('INSERT INTO rabbits (name, status, breedingid, breed, birthdate, gender, label, women, men, place, injectiondate, injectiontype) VALUES ("'.$_GET['name'].'", "'.$_GET['status'].'", "'.$_GET['breedingid'].'", "'.$_GET['breed'].'", "'.$_GET['birth'].'", "'.$_GET['gender'].'", "'.$_GET['label'].'", "'.$_GET['women'].'", "'.$_GET['men'].'", "'.$_GET['place'].'", "'.$_GET['injectiondate'].'", "'.$_GET['injectiontype'].'");');
        $select_injection_id_from_dbase = $connect_dbase->query('SELECT LAST_INSERT_ID();');
        $select_injection_id_from_dbase = $select_injection_id_from_dbase->fetch()[0];
        $date_finish_injection = date_next_injection($_GET['injectiondate'], $injections_arr[trim($_GET['injectiontype'])], 1);
        $insert_injection_data_to_dbase = $connect_dbase->exec('INSERT INTO injections (injectiontype, injectiondate, injectionfinish, name, breedingid, injectionstatus) VALUES ("'.$_GET['injectiontype'].'", "'.$_GET['injectiondate'].'", "'.$date_finish_injection.'", "'.$_GET['name'].'", "", "on");');

        $connect_dbase->commit();
        //$results_dbase = $connect_dbase->exec($query_dbase);
        /*if ( $results_dbase === false ){

            echo "Добрый день!!! В rabbit_insert_dbase ошибка!!!";
        }*/
    } catch (PDOException $e) {
        $connect_dbase->rollBack();
        echo ("Good day!!!<br> Error: " . $e->getMessage()."<br>");
        die();
    }
    //$results_dbase = null;
    $connect_dbase = null;

}

// Изменение данных зайца в MySQL
function rabbit_update_dbase( $mysql, $rabbit_id ){
    try{
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $query_dbase = 'UPDATE rabbits SET name="'.$_GET['name'].'", status="'.$_GET['status'].'", breedingid="'.$_GET['breedingid'].'", breed="'.$_GET['breed'].'", birthdate="'.$_GET['birth'].'", gender="'.$_GET['gender'].'", label="'.$_GET['label'].'", women="'.$_GET['women'].'", men="'.$_GET['men'].'", place="'.$_GET['place'].'" WHERE id="'.$rabbit_id.'";'; //echo $query_mysql;
        $results_dbase = $connect_dbase->exec($query_dbase);

        if ( $results_dbase === false )
            echo "Добрый день!!! В rabbit_update_dbase ошибка!!!";
    } catch (PDOException $e) {
        echo ("Good day!!!<br> Error: " . $e->getMessage()."<br>");
        die();
    }
    $results_dbase = null;
    $connect_dbase = null;

}

// Удаление данных зайца из MySQL
function rabbit_delete_dbase( $mysql, $rabbit_id ){
    try{
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $query_dbase = 'DELETE FROM rabbits WHERE id="'.$rabbit_id.'";';
        $results_dbase = $connect_dbase->exec($query_dbase);

        if ( $results_dbase === false )
            echo "Добрый день!!! В rabbit_delete_dbase ошибка!!!";
    } catch(PDOException $e) {
        echo ("Good day!!!<br> Error: " . $e->getMessage()."<br>");
        die();
    }
    $results_dbase = null;
    $connect_dbase = null;
    
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

// Считывание данных случек из mysql
/*function copulations_from_dbase( $mysql ){
    $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);

    $notorm = new NotORM($connect_dbase);
    $rows_copulations = $notorm->copulations()
        ->select("*")
        ->order("couplingdate");
    foreach ( $rows_copulations as $copulation ) {

        $copulations[$copulation['couplingid']] = array( $copulation['couplingid'], $copulation['couplingdate'], $copulation['couplingmen'], $copulation['couplingwomen'], $copulation['couplingplace'] );
    }
    return $copulations;
}*/

/*function copulations_insert_dbase( $mysql ){
    $connect_dbase =  new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
    $notorm = new NotORM($connect_dbase);

    $insert_array = array('couplingdate' => $_GET['couplingdate'], 'couplingmen' => $_GET['couplingmen'], 'couplingwomen' => $_GET['couplingwomen'], 'couplingplace' => $_GET['couplingplace']);
    $results_dbase = $notorm->copulations()->insert($insert_array);
}*/


/*function copulation_delete_dbase( $mysql ){
    $connect_dbase =  new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
    $notorm = new NotORM($connect_dbase);
    $coupling = $notorm->copulations->where('couplingid', [$_GET['id']]);

    if ( $coupling ) { // Исключение ошибок
        $coupling->delete();
    }

}*/

/*function copulation_update_dbase( $mysql ){

    $connect_dbase =  new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
    $notorm = new NotORM($connect_dbase);
    $coupling = $notorm->copulations->where('couplingid', [$_GET['couplingid']]);
    $update_array = array('couplingdate' => $_GET['couplingdate'], 'couplingmen' => $_GET['couplingmen'], 'couplingwomen' => $_GET['couplingwomen'], 'couplingplace' => $_GET['couplingplace']);

    if ( $coupling ) { // Исключение ошибок
        $coupling->update($update_array);
    }
}*/

/*function injections_from_dbase( $mysql ){
    $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
    try {
        $results = $connect_dbase->query('SELECT * FROM injections;');
        $injections = $results->fetchAll(PDO::FETCH_CLASS, "Injection");
    } catch (PDOException $e) {
        echo ("Good day!!!<br> Error: " . $e->getMessage()."<br>");
        die();
    }
    $connect_dbase = null;
    return $injections;
}*/

/*function injection_update_dbase( $mysql ) {
    $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);

    $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $results_dbase = $connect_dbase->exec('UPDATE injections SET injectiontype="'.$_GET['injectiontype'].'", injectiondate="'.$_GET['injectiondate'].'", injectionfinish="'.$_GET['injectionfinish'].'", name="'.$_GET['name'].'", breedingid="'.$_GET['breedingid'].'", injectionstatus="'.$_GET['injectionstatus'].'" WHERE injectionid="'.$_GET['injectionid'].'";');
    } catch ( PDOException $e ) {
        echo $e->getCode().':'.$e->getMessage();
    }
    $connect_dbase = null;
}*/

/*function injection_insert_dbase( $mysql, $injections_arr ){
 
    $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
    $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $injectionfinish = date_next_injection($_GET['injectiondate'], $injections_arr[trim($_GET['injectiontype'])], 1);

    try {
        $results_dbase = $connect_dbase->exec('INSERT INTO injections (injectiontype, injectiondate, injectionfinish, name, breedingid, injectionstatus) VALUES ("'.$_GET['injectiontype'].'", "'.$_GET['injectiondate'].'", "'.$injectionfinish.'", "'.$_GET['name'].'", "'.$_GET['breedingid'].'", "'.$_GET['injectionstatus'].'");');
    } catch ( PDOException $e ) {

        echo $e->getCode().':'.$e->getMessage();
    }

    $connect_dbase = null;
}*/

/*function injection_delete_dbase( $mysql) {
    $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
    $results_dbase = $connect_dbase->exec('DELETE FROM injections WHERE injectionid="'.$_GET['id'].'";');
    $connect_dbase = null;
}*/



function injections_select_rabbit( $mysql, $rabbit_name ){
    $injections = [];
    $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
    $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {

        foreach($connect_dbase->query('SELECT * FROM injections WHERE name="'.$rabbit_name.'";') as $row){
            $injections[$row['injectionid']] = array($row['injectiontype'], $row['injectiondate'], $row['injectionfinish'], $row['name'], $row['breedingid'], $row['injectionstatus']);
        }
    } catch (PDOException $e) {
        echo $e->getCode().':'.$e->getMessage();

    }
    return $injections;
}

// Считывание базы случек по имени зайца
function copulations_rabbit_mysql( $mysql, $rabbit_name ){
    $connect_mysql = connect_mysql( $mysql );

    $query_mysql = 'SELECT * FROM copulations WHERE couplingmen="'.$rabbit_name.'" OR couplingwomen="'.$rabbit_name.'" ORDER BY couplingdate;';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    $rows_mysql = $results_mysql->num_rows;

    $copulations_rabbit = array();
    for ( $i = 0; $i < $rows_mysql; ++$i ) {
        $results_mysql->data_seek($i);
        $string_mysql = $results_mysql->fetch_array(MYSQLI_ASSOC);
        $couplingid = $string_mysql['couplingid'];

        $date = $string_mysql['couplingdate'];
        $men = $string_mysql['couplingmen'];
        $women = $string_mysql['couplingwomen'];
        $place = $string_mysql['couplingplace'];
        $arr = array( $couplingid, $date, $men, $women, $place );

        $copulations_rabbit[$couplingid] = $arr;
    }
    $results_mysql->close();
    $connect_mysql->close();

    return $copulations_rabbit;
}


// Считывание базы случек по ID случки
/*function copulation_id_mysql( $mysql, $couplingid ){
    $connect_mysql = connect_mysql( $mysql );
    $query_mysql = 'SELECT * FROM copulations WHERE couplingid="'.$couplingid.'";';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );

    $results_mysql->data_seek(0);
    $string_mysql = $results_mysql->fetch_array(MYSQLI_ASSOC);
    $men = $string_mysql['couplingmen'];
    $women = $string_mysql['couplingwomen'];

    $arr = array($men, $women);
    $results_mysql->close();
    $connect_mysql->close();// $arr = array('Крол', 'Крольчиха');

    return $arr;
}*/

/*function breedings_from_mysql( $mysql ){
    $connect_mysql = connect_mysql( $mysql );
    $query_mysql = 'SELECT * FROM copulations NATURAL JOIN breedings ORDER BY breedingdate;';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    $rows_mysql = $results_mysql->num_rows;
    for ( $i = 0; $i < $rows_mysql; ++$i ) {

        $results_mysql->data_seek($i);
        $string_mysql = $results_mysql->fetch_array(MYSQLI_ASSOC);
        $breedingid = $string_mysql['breedingid'];
        $breedingdate = $string_mysql['breedingdate'];
        $breedingnumberall = $string_mysql['breedingnumberall'];

        $breedingnumberlive = $string_mysql['breedingnumberlive'];
        $couplingmen = $string_mysql['couplingmen'];
        $couplingwomen = $string_mysql['couplingwomen'];
        $couplingid = $string_mysql['couplingid'];
        $arr = array( $breedingid, $breedingdate, $breedingnumberall, $breedingnumberlive, $couplingmen, $couplingwomen, $couplingid );
        $breedings[$breedingid] = $arr;
    }


    $results_mysql->close();
    $connect_mysql->close();
    return $breedings;
}*/

/*function breeding_update_mysql( $mysql ){
    $connect_mysql = new mysqli( $mysql['node'], $mysql['user'], $mysql['passwd'], $mysql['dbase']);
    if ( $connect_mysql->connect_error ) die ( $connect_mysql->connect_error );
    $query_mysql = 'UPDATE breedings SET breedingdate="'.$_GET['breedingdate'].'", breedingnumberall="'.$_GET['breedingnumberall'].'", breedingnumberlive="'.$_GET['breedingnumberlive'].'", couplingid="'.$_GET['couplingid'].'" WHERE breedingid="'.$_GET['breedingid'].'";';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    if ( !$results_mysql ) die ( $connect_mysql->connect_error );
    //$results_mysql->close();
    $connect_mysql->close();
}*/

function breedings_rabbit( $mysql, $rabbit_name ){
    $breedings_rabbit = array();
    $connect_mysql = connect_mysql( $mysql );
    $query_mysql = 'SELECT * FROM copulations NATURAL JOIN breedings WHERE couplingmen="'.$rabbit_name.'" OR couplingwomen="'.$rabbit_name.'" ORDER BY breedingdate;';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    $rows_mysql = $results_mysql->num_rows;
    for ( $i = 0; $i < $rows_mysql; ++$i ) {
        $results_mysql->data_seek($i);
        $string_mysql = $results_mysql->fetch_array(MYSQLI_ASSOC);
        $breedingid = $string_mysql['breedingid'];
        $breedingdate = $string_mysql['breedingdate'];

        $breedingnumberall = $string_mysql['breedingnumberall'];
        $breedingnumberlive = $string_mysql['breedingnumberlive'];
        $couplingid = $string_mysql['couplingid'];
        $arr = array( $breedingid, $breedingdate, $breedingnumberall, $breedingnumberlive, $couplingid );
        $breedings_rabbit[$breedingid] = $arr;
    }
    return $breedings_rabbit;
}

/*function breeding_from_copulation_to_mysql( $mysql ){
    $connect_mysql = connect_mysql( $mysql );
    $query_mysql = 'INSERT INTO breedings (breedingdate, breedingnumberall, breedingnumberlive, couplingid) VALUES ("'.$_GET['breedingdate'].'", "'.$_GET['breedingnumberall'].'", "'.$_GET['breedingnumberlive'].'", "'.$_GET['couplingid'].'");';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    if ( !$results_mysql ) die ( $connect_mysql->connect_error );
    //$results_mysql->close();
    $connect_mysql->close();
}*/

/*function breeding_delete_mysql( $mysql ){
    $connect_mysql = new mysqli( $mysql['node'], $mysql['user'], $mysql['passwd'], $mysql['dbase']);
    if ( $connect_mysql->connect_error ) die ( $connect_mysql->connect_error );
    $query_mysql = 'DELETE FROM breedings WHERE breedingid="'.$_GET["id"].'";';
    $results_mysql = send_query_mysql( $connect_mysql, $query_mysql );
    if ( !$results_mysql ) die ( $connect_mysql->connect_error );
    //$results_mysql->close();
    $connect_mysql->close();
    }*/
?>