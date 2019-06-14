<?php



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'secret.php';
//ini_set('display_errors', 1);
//ini_set('display_atartup_errors',1);
//ini_set('error_reporting', E_ALL);
mb_internal_encoding("UTF-8");










$file_rabbits = 'rabbits.csv';
// Массив женских имен
$womens = array('Крольчиха Мать', 'Нет данных');
// Массив мужский имен
$mens = array('Кролик Отец', 'Нет данных');
//Массив клеток
$places = array('Выберите клетку', 'Нет данных', 'Клетка 01', 'Клетка 02', 'Клетка 03', 'Клетка 05', 'Клетка 06');
//Массив полов
$genders = array('Выберите пол', 'Нет данных', 'Мужской', 'Женский');
//Массив прививок (дни)
$injections = array('Выберите прививку' => '', 'Нет данных' => 1, 'ABC' => 180, 'EFG' => 90, 'HKL' => 3650);
// Количество дней за которое начинаются формироваться письма
$injections_limit_day = 10*500;
// Массив пород
$breeds = array('Выберите породу', 'Нет данных', 'Беспородная', 'Калифорнийская');
// Массив окролов
$breedingid = array('Выберите ID окрола', 'Нет данных', '1', '2', '3');


//Строка Файла ID,Имя, ,Окрол ID,Порода,Дата рождения,Пол,Клеймо,Мама,Папа,Клетка,Дата прививки
//Массив спортсменов   "001" => array("Имя", "", "Окрол ID", "Порода", "Дата рождения", "Пол", "Клеймо", "Мама", "Папа", "Клетка", "Дата прививки"),


if ( (isset($_GET['rabbitid'])) && $_GET['action'] == 'ins' ) {//echo "Good Day!!!";
    write_string_rabbits($file_rabbits);
}



// Файла rabbits.csv отсутствует
if (!file_exists($file_rabbits)) { //echo "Добрый день!!!";
    file_rabbits_noexist($file_rabbits);
}


// Удаление кролика
if ( (isset($_GET['rabbitid'])) && $_GET['action'] == 'del' ) {//echo "Добрый вечер!!!";
    erase_string_rabbits($file_rabbits, $_GET['rabbitid']);
}


// Изменений данных кролика
if ( (isset($_GET['rabbitid'])) && $_GET['action'] == 'mod' ) {
    change_data_rabbit($file_rabbits);
}








//$rabbits = array_from_file( $file_rabbits );
$rabbits = array_from_mysql($mysql_node, $mysql_user, $mysql_passwd, $mysql_dbase);



// Отображение страницы
// Отображается список кроликов в при простом отображении и при удалении кролика
if ( !(isset($_GET['rabbitid'])) || $_GET['action'] == 'del' || (isset($_GET['rabbitid']) && $_GET['action'] == 'ins') ) {
        $string_rabbits = '';        
        foreach ( $rabbits as $rabbit_id => $rabbit ){
            $rabbit_gender_shot = mb_substr($rabbit[5], 0, 1);
            $string_rabbit = "<tr><td>$rabbit_id => $rabbit_new_id</td><td><a href='index.php?rabbitid=$rabbit_id'>$rabbit[0]</a></td><td>$rabbit[6]</td><td>".date('d-m-Y', strtotime($rabbit[4]))."</td><td>$rabbit_gender_shot</td><td>$rabbit[3]</td><td>$rabbit[9]</td><td>".date_next_injection($rabbit[10], $injections[trim($rabbit[11])])."".wrapper_days_prior_to_injection($rabbit[10], $injections[trim($rabbit[11])], $injections_limit_day, $mail_user, $mail_pass, $rabbit[0])."</td><td><div class='erase-rabbit' rabbitid='".$rabbit_id."'>x</div></td</tr>"; //<a href='index.php?rabbitid=$rabbit_id&action=del'>x</a></td></tr>";//Добрый день!!!

            $string_rabbits .= $string_rabbit;
            $rabbit_new_id = ++$rabbit_id;
        }
        $string_middle = <<<EOD
            <table class="ferma">
                <tr><th>№</th><th>Кличка</th><th>Клемо</th><th>Дата рождения</th><th>Пол</th><th>Порода</th><th>Клетка</th><th>Прививка</th><th></th></tr>
                $string_rabbits
                <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>

                <tr><td>...</td><td><a href="index.php?rabbitid=$rabbit_new_id">Добавить нового кроллика</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
            </table>
EOD;

}

// Отображение общей информации по кролику, отображается при 'Вывод информации кролика' 'Добавление нового кролика' 
elseif ( isset($_GET['rabbitid']) && !(isset($_GET['action'])) ) {
    $rabbit_id = $_GET['rabbitid'];
    if ( array_key_exists($_GET['rabbitid'], $rabbits) ) {
        $action_type = 'mod';
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

        $rabbit_injection_type  = $rabbits[$rabbit_id[11]];

    } else {
        $action_type = 'ins';
        $rabbit_birth_date      = date('Y-m-d', time());
        $rabbit_injection_date  = date('Y-m-d', time());
        $rabbit_injection_type = 'Нет данных';
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



require_once "htmlup.php";
require_once "htmldown.php";

echo $string_up.$string_middle.$string_down;
//sender_mail($mail_user, $mail_pass);





// Изменений данных кролике
function change_data_rabbit($file_rabbits){
    $rabbits = array_from_file($file_rabbits);
    $string_to_array = $_GET['name'].',,'.$_GET['breedingid'].','.$_GET['breed'].','.date('d.m.Y', $_GET['birth']).','.$_GET['gender'].','.$_GET['label'].','.$_GET['women'].','.$_GET['men'].','.$_GET['place'].','.date('d.m.Y', $_GET['injectiondate']).', '.$_GET['injectiontype'];
    //$string_to_array = ',Test,,,,,,,,,';
    //echo '<pre>'.trim($string_to_array).'</pre>';
    $rabbits[$_GET['rabbitid']] = explode(',', $string_to_array);

    //$rabbits = array_values($rabbits);
    //print_r ($rabbits);
    echo "<pre>";
    var_dump($rabbits);
    echo "</pre>";
    //array_to_file( $file_rabbits, $rabbits );
}

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

// Удаляет запись зайца из массива и вызывает функцию записи массива в файл
function erase_string_rabbits($file_rabbits, $rabbitid){
    $rabbits = array_from_file( $file_rabbits );
    unset($rabbits[$rabbitid]);
    $rabbits = array_values( $rabbits );
    array_to_file($file_rabbits, $rabbits);
}






// Создает файл rabbits.csv и добавляет две записи
function file_rabbits_noexist($file_rabbits) { //echo "Добрый день!!!";
    $filerabbits_creater_text = "1,Ушастик,,1,Калифорнийская,01.01.2001,Мужской,Клеймо01,Лапочка,Ушастик,Клетка 01,01.07.2015,ABC\r\n2,Лапочка,,2,Беспородная,03.03.2003,Женский,Клеймо02,Mather02,Pather02,Клетка 02,01.07.2015,HKL";
    $fo = fopen($file_rabbits, 'w') or die ('Добрый день, создать файл rabbits.csv не удалось!');
    fwrite($fo, $filerabbits_creater_text) or die ('Добрый день, сбой записи rabbits.csv при создании!');
    fclose($fo);
}

// Добавляет новую строку в rabbits.csv
function write_string_rabbits($file_rabbits) { //echo "Good Day!!!";
    $string_to_file = "\n".$_GET['rabbitid'].','.$_GET['name'].',,'.$_GET['breedingid'].','.$_GET['breed'].','.$_GET['birth'].','.$_GET['gender'].','.$_GET['label'].','.$_GET['women'].','.$_GET['men'].','.$_GET['place'].','.$_GET['injectiondate'].','.$_GET['injectiontype'];
    file_put_contents( $file_rabbits, $string_to_file, FILE_APPEND | LOCK_EX ); 
}

// Перезапись массива данных в файл
function array_to_file ($file_rabbits, $rabbits) {
    unlink( $file_rabbits );
    foreach ( $rabbits as $rabbit_id => $rabbit ){
        $string_to_file = implode(',', $rabbit);
        $string_to_file = $rabbit_id.','.$string_to_file;
        file_put_contents( $file_rabbits, $string_to_file, FILE_APPEND | LOCK_EX );
    }

}

// Считывание данных зайцев из mysql
function array_from_mysql($mysql_node, $mysql_user, $mysql_passwd, $mysql_dbase){
    $connect_mysql = new mysqli($mysql_node, $mysql_user, $mysql_passwd, $mysql_dbase);
    if ( $connect_mysql->connect_error ) die ( $connect_mysql->connect_error );

    $query_mysql = 'SELECT * FROM rabbits;';
    $results_mysql = $connect_mysql->query($query_mysql);
    if ( !$results_mysql ) die ( $connect_mysql->connect_error );
    $rows_mysql = $results_mysql->num_rows;
    for ( $i = 0; $i < $rows_mysql; ++$i ) {

        $results_mysql->data_seek($i);
        echo "Добрый день!!!"." ".$i." ".$results_mysql->fetch_assoc()['name']."<br />";
    }
    $results_mysql->close();
    $connect_mysql->close();
}

// Считывание файла rabbits.csv с превращением данных особей в ассациативный массив, формирование массивов женских и мужских имен
function array_from_file($file_rabbits) {
    $fo = fopen($file_rabbits, 'r');
    if ( $fo ) {
        while ( ( $string_fo = fgets( $fo ) ) !== False ) {
            $array = explode(',', $string_fo);
            $key = $array[0] * 1;
            unset( $array[0] );
            $array = array_values( $array );
            $rabbits[$key] = $array;
            if ( $rabbits[$key][5] == 'Женский' ) {
                $womens[] = $rabbits[$key][0];
            }
            elseif ( $rabbits[$key][5] == 'Мужской' ) {
                $mens[] = $rabbits[$key][0];
            }
        }
    }
    return $rabbits;
}



// Формирование ассоциативное Select
function fill_ass_select($array, $name, $id, $value){
    $tag = '';
    foreach ( $array as $type => $time ) {
        if ($tag == '') {
            $tag .= "<option disabled>$type</option>";
        }
        elseif ( $type == $value ) {
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
function fill_select($array, $name, $value){

    $tag = '';
    foreach ( $array as $item ) {
        if ($tag == '') {
            $tag .= "<option disabled>$item</option>";
        }
        elseif ( $item == $value ) {
            $tag .= "<option selected >$item</option>";
        }
        else {
            $tag .= "<option>$item</option>";
        }
     }

     $tag = "<select id='$name' name='$name'>$tag</select>";
    return $tag;
}
?>