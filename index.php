<?php





















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
$injections = array('1' => 180, '2' => 90, '3' => 3650);
// Количество дней за которое начинаются формироваться письма
$injections_limit_day = 10;
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
    $rabbits = array_from_file( $file_rabbits );
    $string_to_array = $_GET['name'].',,'.$_GET['breedingid'].','.$_GET['breed'].','.date('d.m.Y', $_GET['birth']).','.$_GET['gender'].','.$_GET['label'].','.$_GET['women'].','.$_GET['men'].','.$_GET['place'].','.date('d.m.Y', $_GET['injection']);
    //$string_to_array = ',Test,,,,,,,,,';
    echo '<pre>'.trim($string_to_array).'</pre>';
    $rabbits[$_GET['rabbitid']] = explode(',', $string_to_array);
    //$rabbits = array_values($rabbits);
    //print_r ($rabbits);
    echo "<pre>";
    var_dump($rabbits);
    echo "</pre>";
    //array_to_file( $file_rabbits, $rabbits );
}








$rabbits = array_from_file( $file_rabbits );




// Отображение страницы
// Отображается список кроликов в при простом отображении и при удалении кролика
if ( !(isset($_GET['rabbitid'])) || $_GET['action'] == 'del' || (isset($_GET['rabbitid']) && $_GET['action'] == 'ins') ) {
        $string_rabbits = '';        
        foreach ( $rabbits as $rabbit_id => $rabbit ){
            mb_internal_encoding("UTF-8");
            $rabbit_gender_shot = mb_substr($rabbit[5], 0, 1);

            $string_rabbit = "<tr><td>$rabbit_id => $rabbit_new_id</td><td><a href='index.php?rabbitid=$rabbit_id'>$rabbit[0]</a></td><td>$rabbit[6]</td><td>".date('d-m-Y', strtotime($rabbit[4]))."</td><td>$rabbit_gender_shot</td><td>$rabbit[3]</td><td>$rabbit[9]</td><td>".date_next_injection($rabbit[10], $injections, 3)."</td><td><a href='index.php?rabbitid=$rabbit_id&action=del'>x</a></td></tr>";
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
    

    (array_key_exists($_GET['rabbitid'], $rabbits)) ? $action_type = 'mod' : $action_type = 'ins';

    $string_middle = "
    <form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'>
        <table class='rabbit'>
            <tr><th colspan='5'>Персональный данные</th></tr>
            <tr><td>ID Кролика</td><td>Кличка</td><td>Порода</td><td>Пол</td><td>Клеймо</td></tr>
            <tr><td><input type='text' name='rabbitid' value='$rabbit_id' disabled></td><td><input name='name' placeholder='Введите имя' value='".$rabbit_name."' type='text'></td><td>".fill_select($breeds, 'breed', $rabbit_breed)."</td><td>".fill_select($genders, 'gender', $rabbit_gender)."</td><td><input type='text' name='label' placeholder='Введите клеймо' value='$rabbit_label'></td></tr>
            <tr><td>ID Окрола</td><td>Крольчиха Мама</td><td>Кролик Отец</td><td>Дата рождения</td><td>Линия</td></tr>
            <tr><td>".fill_select($breedingid, 'breedingid', $rabbit_breedingid)."</td><td>".fill_select($womens, 'women', $rabbit_women)."<td>".fill_select($mens, 'men', $rabbit_men)."</td><td><input name='birth' type='date' value=$rabbit_birth_date></td><td><select name='pedigree'><option>Мать - Отец</option><option>Матушка - Батюшка</option></select></td></tr>
            <tr><td>Клетка</td><td>Вид</td><td>Дата прививки</td><td> </td><td> </td></tr>
            <tr><td>".fill_select($places, 'place', $rabbit_place)."</td><td><select><option>Такой</option><option>Сякой</option><option>Эдакий</option></select></td><td><input type='date' name='injection' value='$rabbit_injection_date'></td><td></td><td></td></tr>


            <tr><td>  </td><td colspan='2'><input type='hidden' value='".$action_type."' name='action'><input type='hidden' name='rabbitid' value=".$rabbit_id."></td><td colspan='2'><input type='submit' value='Записать изменения'></td></tr>
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




// Дата следующей прививки
function date_next_injection($date, $injections, $injection){
    $date = new DateTime($date);
    $interval = $injections[$injection];    
    $interval = 'P'.$interval.'D';
    $date->add(new DateInterval($interval));
    return $date->format('d-m-Y');
}

// Возращает количество дней до прививки
function days_priorto_injection($date){




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
    $filerabbits_creater_text = "1,Ушастик, ,1,Калифорнийская,01.01.2001,Мужской,Клеймо01,Лапочка,Ушастик,Клетка 01,01.07.2001\r\n2,Лапочка, ,2,Беспородная,03.03.2003,Женский,Клеймо02,Mather02,Pather02,Клетка 02,01.07.2001";
    $fo = fopen($file_rabbits, 'w') or die ('Добрый день, создать файл rabbits.csv не удалось!');
    fwrite($fo, $filerabbits_creater_text) or die ('Добрый день, сбой записи rabbits.csv при создании!');
    fclose($fo);
}

// Добавляет новую строку в rabbits.csv
function write_string_rabbits($file_rabbits) { //&& $_GET['action'] == 'ins' ) {
    //echo "Good Day!!!";
    mb_internal_encoding("UTF-8");
    $string_to_file = "\n".$_GET['rabbitid'].','.$_GET['name'].',,'.$_GET['breedingid'].','.$_GET['breed'].','.$_GET['birth'].','.$_GET['gender'].','.$_GET['label'].','.$_GET['women'].','.$_GET['men'].','.$_GET['place'].','.$_GET['injection'];
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

     $tag = "<select name='$name'>$tag</select>";
    return $tag;
}

?>