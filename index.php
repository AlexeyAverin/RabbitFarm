<?php





















$file_rabbits = 'rabbits.csv';
$filerabbits_creater_text = "1,Ушастик, ,1,Калифорнийская,01.01.2001,Мужской,Клеймо01,Лапочка,Ушастик,Клетка 01,01.07.2001\r\n2,Лапочка, ,2,Беспородная,03.03.2003,Женский,Клеймо02,Mather02,Pather02,Клетка 02,01.07.2001";
if ( (isset($_POST['rabbitid'])) && $_POST['action'] == 'ins' ) {
    //echo "Good Day!!!";
    mb_internal_encoding("UTF-8");
    $string_to_file = "\n".$_POST['rabbitid'].','.$_POST['name'].',,'.$_POST['breedingid'].','.$_POST['breed'].','.date('d.m.Y', $_POST['birth']).','.$_POST['gender'].','.$_POST['label'].','.$_POST['women'].','.$_POST['men'].','.$_POST['place'].','.date('d.m.Y', $_POST['injection']);
    $fo = fopen($file_rabbits, 'a') or die ('Сбой открытия файла');
    fwrite($fo, $string_to_file) or die ('Сбой записи файла');

    fclose($fo);
}



// Создание файла rabbits.csv в случае его отсутствия
if (!file_exists($file_rabbits)) { //echo "Добрый день!!!";
    $fo = fopen($file_rabbits, 'w') or die ('Добрый день, создать файл rabbits.csv не удалось!');
    fwrite($fo, $filerabbits_creater_text) or die ('Добрый день, сбой записи rabbits.csv при создании!');
    fclose($fo);
}


// Удаление кролика
if ( (isset($_GET['rabbitid'])) && $_GET['action'] == 'del' ) {
    //echo "Добрый вечер!!!";

    $rabbits = array_from_file( $file_rabbits );
    unset($rabbits[$_GET['rabbitid']]);
    $rabbits = array_values( $rabbits );

    array_to_file($file_rabbits, $rabbits);

}


// Изменений данных кролика
if ( (isset($_POST['rabbitid'])) && $_POST['action'] == 'mod' ) {
    $rabbits = array_from_file( $file_rabbits );
    $string_to_array = $_POST['name'].',,'.$_POST['breedingid'].','.$_POST['breed'].','.date('d.m.Y', $_POST['birth']).','.$_POST['gender'].','.$_POST['label'].','.$_POST['women'].','.$_POST['men'].','.$_POST['place'].','.date('d.m.Y', $_POST['injection']);
    $rabbits[$_POST['rabbitid']] = explode(',', $string_to_array);
    array_to_file( $file_rabbits, $rabbits );
}




// Считывание файла rabbits.csv с превращением данных в ассациативный массив
$rabbits = array();
// Массив женских имен
$womens = array('Крольчиха Мать', 'Нет данных');
// Массив мужский имен
$mens = array('Кролик Отец', 'Нет данных');




$rabbits = array_from_file( $file_rabbits );


//Строка Файла ID,Имя, ,Окрол ID,Порода,Дата рождения,Пол,Клеймо,Мама,Папа,Клетка,Дата прививки
//Массив спортсменов   "001" => array("Имя", "", "Окрол ID", "Порода", "Дата рождения", "Пол", "Клеймо", "Мама", "Папа", "Клетка", "Дата прививки"),

//Массив клеток
$places = array('Выберите клетку', 'Нет данных', 'Клетка 01', 'Клетка 02', 'Клетка 03', 'Клетка 05', 'Клетка 06');
//Массив полов
$genders = array('Выберите пол', 'Нет данных', 'Мужской', 'Женский');
//Массив прививок (дни)
$injections = array('Ассоциированная' => 180, 'ВКГБ' => 90, 'Ангина' => 3650);
// Массив пород
$breeds = array('Выберите породу', 'Нет данных', 'Беспородная', 'Калифорнийская');
// Массив окролов
$breedingid = array('Выберите ID окрола', 'Нет данных', '1', '2', '3');






if ( !(isset($_GET['rabbitid'])) || $_GET['action'] == 'del' ) {
        $string_rabbits = '';
        $rabbit_new_id = 1;
        foreach ( $rabbits as $rabbit_id => $rabbit ){

            mb_internal_encoding("UTF-8");
            $rabbit_gender_shot = mb_substr($rabbit[5], 0, 1);
            $string_rabbit = "<tr><td>$rabbit_id => $rabbit_new_id</td><td><a href='index.php?rabbitid=$rabbit_id'>$rabbit[0]</a></td><td>$rabbit[6]</td><td>$rabbit[4]</td><td>$rabbit_gender_shot</td><td>$rabbit[3]</td><td>$rabbit[9]</td><td>$rabbit[10]</td><td><a href='index.php?rabbitid=$rabbit_id&action=del'>x</a></td></tr>";

            $string_rabbits .= $string_rabbit;
            ++$rabbit_new_id;
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

// Вывод данных кролика
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
    

    if ( (array_key_exists($_GET['rabbitid'], $rabbits)) ) { $action_type = 'mod'; } else { $action_type = 'ins'; }

    $string_middle = "
    <form method='post' action='index.php' enctype='application/x-www-form-urlncoded'>
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
    
    // Информация не выводиться по новым кроликам

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

$string_up = <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>

            
            html, body { margin: 0; padding: 0; font-family: monospace; --color-grey-soft: rgb(170, 170, 170); --color-grey-dark: rgb(110, 110, 110); }
            div.contact { height: 50px; padding: 0 10px; display: flex; justify-content: space-between; color: rgb(170, 170, 170); }
            div.contact div { line-height: 50px; }
            header { width: 100%; display: flex; flex-direction: col; height: 70px; font-size: 20px; }
            header div.logotip { width: 70px; height: 100%; }
            header div.brand   { width: 210px; height: 100%; color: var(--color-grey-soft); line-height: 70px; }
            header nav         { height: ; 100%; flex-direction: row; justify-content: flex-end; display: flex; flex-grow: 1; }
            header nav:last-child { margin-right: 10px; }

            header nav a { color: var(--color-grey-soft); padding: 0 10px; line-height: 70px; text-decoration: none; font-size:; }
            header nav a:hover { background-color: var(--color-grey-dark); }
            section { width: 100%; height: calc(100vh - 120px); font-size: 15px; background-color: white; }
            table.ferma                       { width: 100%; margin: 0 0 50px 0; border-style: hidden; color: blue; }
            table.ferma td a                  { color: orange; text-decoration: none; }

            table.ferma th                    { text-align: left; color: white; background-color: var(--color-grey-dark); }
            table.ferma tr:nth-child(odd)     { color: var(--color-grey-soft); }
            table.ferma tr:nth-child(even)    { color: var(--color-grey-dark); }
            table.rabbit                    { width: 100%; margin: 0 0 50px 0; color: blue; border-style: hidden; }
            table.rabbit th                 { text-align: left; color: var(--color-grey-dark); }
            table.rabbit tr                 { text-align: center;  }
            table.rabbit tr:nth-child(odd)  { background-color: ; }

            table.rabbit tr:nth-child(even) { background-color: ; color: var(--color-grey-soft); }
            table.rabbit select             { width: 150px; margin-bottom: 19px; color: var(--color-grey-dark); }
            table.rabbit input              { width: 135px; margin-bottom: 19px; color: var(--color-grey-dark); }
            table.rabbit input[type=submit] { width: 340px; }

        </style>
        <title>Ферма кроликов</title>
    </head>
    <body>

        <form action="index.php"  method="GET">
        <header>
            <div class="logotip"><a href="index.php"><img src="rabbit.png"></a></div>
            <div class="brand">Добрый день!!!</div>
            <nav><a href="index.php">Главная</a><a href="index.php">Кролики</a><a href="">Статистика</a></nav>
        </header>
        </form>
        <section>
EOD;
$string_down = <<<EOD
        </section>

        <div class="contact">
            <div class="address">Советский Союз</div>
            <div class="phone">+7 (xxx) xxx-xx-xx</div>
            <div class="right">Все права защищены</div>
        </div>
    </body>

</html>
EOD;

echo $string_up.$string_middle.$string_down;

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