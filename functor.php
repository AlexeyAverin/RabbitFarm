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






/*
// Добавление данных зайца в MySQL
if ( $_GET['action'] == 'ins' ) {//echo "Good Day!!!";
    string_to_mysql( $mysql );
}

// Удаление кролика
if ( (isset($_GET['rabbitid'])) && $_GET['action'] == 'del' ) {//echo "Добрый вечер!!!";
    string_delete_mysql( $mysql, $_GET['rabbitid'] ); 
}

// Изменений данных кролика
if ( (isset($_GET['rabbitid'])) && $_GET['action'] == 'upd' ) {
    update_string_mysql( $mysql, $_GET['rabbitid'] ); ###
}

// Считывание данных MySQL
$rabbits = array_from_mysql( $mysql, $mens, $momens );



// Отображение страницы
// Отображается список кроликов в при простом отображении и при удалении кролика
if ( !isset($_GET['action']) || $_GET['action'] == 'upd' || $_GET['action'] == 'ins' || $_GET['action'] == 'del' ) {
        $string_rabbits = '';
        $rabbit_new_id = 0;
        foreach ( $rabbits as $rabbit_id => $rabbit ){
            $rabbit_gender_shot = mb_substr($rabbit[5], 0, 1);
            $string_rabbit = "<tr><td>$rabbit_id</td><td><a href='index.php?action=mod&rabbitid=$rabbit_id'>$rabbit[0]</a></td><td>$rabbit[6]</td><td>".date('d-m-Y', strtotime($rabbit[4]))."</td><td>$rabbit_gender_shot</td><td>$rabbit[3]</td><td>$rabbit[9]</td><td>".date_next_injection($rabbit[10], $injections[trim($rabbit[11])])."".wrapper_days_prior_to_injection($rabbit[10], $injections[trim($rabbit[11])], $injections_limit_day, $mail_user, $mail_pass, $rabbit[0])."</td><td><div class='erase-rabbit' rabbitid='".$rabbit_id."'>x</div></td</tr>"; //<a href='index.php?rabbitid=$rabbit_id&action=del'>x</a></td></tr>";//Добрый день!!!
            $string_rabbits .= $string_rabbit;
            $rabbit_new_id = ++$rabbit_id;
        }
        $string_middle = <<<EOD
            <table class="ferma">
                <tr><th>№</th><th>Кличка</th><th>Клемо</th><th>Дата рождения</th><th>Пол</th><th>Порода</th><th>Клетка</th><th>Прививка</th><th></th></tr>
                $string_rabbits
                <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
                <tr><td>...</td><td><a href="index.php?action=new&rabbit">Добавить нового кроллика</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
            </table>

EOD;

// Отображение общей информации по кролику, отображается при 'Вывод информации кролика' 'Добавление нового кролика' 
} elseif ( $_GET['action'] == 'new' || $_GET['action'] == 'mod' ) {
    $rabbit_id = $_GET['rabbitid'];
    if ( $_GET['action'] == 'mod' ) {
        $action_type = 'upd';
        $rabbit_name            = $rabbits[$rabbit_id][0];

        $rabbit_breedingid      = $rabbits[$rabbit_id][2];
        $rabbit_breed           = $rabbits[$rabbit_id][3];
        $rabbit_birth_date      = date('Y-m-d', strtotime($rabbits[$rabbit_id][4]));    
        $rabbit_gender          = $rabbits[$rabbit_id][5];
        $rabbit_label           = $rabbits[$rabbit_id][6];
        $rabbit_women           = $rabbits[$rabbit_id][7];
        $rabbit_men             = $rabbits[$rabbit_id][8];
        $rabbit_place           = $rabbits[$rabbit_id][9];

        $rabbit_injection_date  = date('Y-m-d', strtotime($rabbits[$rabbit_id][10]));
        $rabbit_injection_type  = $rabbits[$rabbit_id][11];
    } elseif ( $_GET['action'] == 'new' ) {
        $action_type = 'ins';
        $rabbit_birth_date      = date('Y-m-d', time());

        $rabbit_injection_date  = date('Y-m-d', time());
        $rabbit_injection_type = 'ABC';
    }

    $string_middle = "
    <form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'>
        <table class='rabbit'>
            <tr><th colspan='5'>Персональный данные</th></tr>

            <tr><td>ID Кролика</td><td>Кличка</td><td>Порода</td><td>Пол</td><td>Клеймо</td></tr>
            <tr><td><input type='text' name='rabbitid' value='$rabbit_id' disabled></td><td><input name='name' placeholder='Введите имя' value='".$rabbit_name."' type='text'></td><td>".fill_select($breeds, 'breed', $rabbit_breed)."</td><td>".fill_select($genders, 'gender', $rabbit_gender)."</td><td><input type='text' name='label' placeholder='Введите клеймо' value='$rabbit_label'></td></tr>
            <tr><td>ID Окрола</td><td>Крольчиха Мама</td><td>Кролик Отец</td><td>Дата рождения</td><td>Линия</td></tr>
            <tr><td>".fill_select($breedingid, 'breedingid', $rabbit_breedingid)."</td><td>".fill_select($womens, 'women', $rabbit_women)."<td>".fill_select($mens, 'men', $rabbit_men)."</td><td><input name='birth' type='date' value=$rabbit_birth_date></td><td><select name='pedigree'><option>Мать - Отец</option><option>Матушка - Батюшка</option></select></td></tr>
            <tr><td>Клетка</td><td>Дата прививки</td><td>Прививка</td><td></td><td> </td></tr>
            <tr><td>".fill_select($places, 'place', $rabbit_place)."</td><td><input type='date' name='injectiondate' id='id' value='$rabbit_injection_date'></td><td>".fill_select(array_keys($injections), 'injectiontype', $rabbit_injection_type)."</td><td> </td><td><input type='hidden' value='".$action_type."' name='action'><input type='hidden' name='rabbitid' value=".$rabbit_id."><input type='submit' value='Записать'>  </td></tr>
        </table>
    </form>";
    
    // Отображается дополнительная информация по кроликам 'Вывод информации кролика'
    if ( (array_key_exists($_GET['rabbitid'], $rabbits)) ) {
        $string_middle .= "<table class='ferma'>
            <tr><td colspan='3'>Данные по вакцинации</td></tr>
            <tr><th>Наименование</th><th>Дата проведения</th><th>Следующая дата</th></tr>
            <tr><td>Ассоциированная</td><td>01.01.2001</td><td>01.07.2001</td></tr>
        </table>
        <table class='ferma'>
            <tr><td colspan='3'>Данные по случке</td></tr>
            <tr><th>Партнер</th><th>Дата проведения</th><th>Дата ожидаемая</th></tr>
            <tr><td>Тарзан</td><td>01.01.2001</td><td>01.07.2001</td></tr>
        </table>
        <table class='ferma'>
            <tr><td colspan='3'>Данные по окролу</td></tr>
            <tr><th>Наименование</th><th>Дата проведения</th><th>Следующая дата</th></tr>
            <tr><td>Ассоциированная</td><td>01.01.2001</td><td>01.07.2001</td></tr>

        </table>";
    }
}
*/
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
        /*if ($tag == '') {
            $tag .= "<option disabled>$type</option>";
        }
        else*/if ( $type == $value ) {
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