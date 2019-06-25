<?php



require 'functor.php';
mb_internal_encoding("UTF-8");
// functor.php
// sendmail.php
// men.js
// secret.php






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
$rabbits_mens_womens = array_from_mysql( $mysql, $mens, $womens );
$rabbits = $rabbits_mens_womens[0];
$mens = $rabbits_mens_womens[1];
$womens = $rabbits_mens_womens[2];

// Отображение страницы
$string_up = <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>

            
            html, body { margin: 0; padding: 0; font-family: monospace; --color-grey-soft: rgb(230, 230, 230); --color-grey-middle: rgb(170, 170, 170); --color-grey-dark: rgb(110, 110, 110); }
            div.contact { height: 50px; padding: 0 10px; display: flex; justify-content: space-between; color: rgb(170, 170, 170); }
            div.contact div { line-height: 50px; }
            header { width: 100%; display: flex; flex-direction: col; height: 70px; font-size: 20px; }
            header div.logotip { width: 70px; height: 100%; }
            header div.brand   { width: 210px; height: 100%; color: var(--color-grey-middle); line-height: 70px; }
            header nav         { height: ; 100%; flex-direction: row; justify-content: flex-end; display: flex; flex-grow: 1; }
            header nav:last-child { margin-right: 10px; }

            header nav a { color: var(--color-grey-middle); padding: 0 10px; line-height: 70px; text-decoration: none; font-size:; }
            header nav a:hover { background-color: var(--color-grey-dark); }
            section { width: 100%; height: calc(100vh - 120px); font-size: 15px; background-color: white; }
            table.ferma                     { width: 100%; margin: 0 0 50px 0; border-style: hidden; color: blue; }
            table.ferma td                  {  }
            table.ferma td a                { color: orange; text-decoration: none; }
            table.ferma td em               { color: var(--color-grey-dark); margin-left: 7px; padding: 3px; font-size: 10px; border-radius: 35%; border: 1px solid red; }
                            .erase-rabbit   { cursor: pointer; color: orange; }
                            .erase-rabbit a { margin: 0 30px; }
            table.ferma th                  { text-align: left; color: white; background-color: var(--color-grey-dark); }
            table.ferma tr:hover            { background-color: var(--color-grey-soft); }
            table.ferma tr:nth-child(odd)   { color: var(--color-grey-middle); }
            table.ferma tr:nth-child(even)  { color: var(--color-grey-dark); }
            table.rabbit                    { width: 100%; margin: 0 0 50px 0; color: blue; border-style: hidden; }
            table.rabbit th                 { text-align: left; color: var(--color-grey-dark); }
            table.rabbit tr                 { text-align: center;  }
            table.rabbit tr:nth-child(odd)  { background-color: ; }

            table.rabbit tr:nth-child(even) { background-color: ; color: var(--color-grey-middle); }
            table.rabbit select             { width: 150px; margin-bottom: 19px; color: var(--color-grey-dark); }
            table.rabbit input              { width: 135px; margin-bottom: 19px; color: var(--color-grey-dark); }
            table.rabbit input[type=submit] { width: 150px; }

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
        <script src="men.js">
        </script>
        <section>
EOD;

// Отображается список кроликов в при простом отображении и при удалении кролика
if ( !isset($_GET['action']) || $_GET['action'] == 'upd' || $_GET['action'] == 'ins' || $_GET['action'] == 'del' ) {
    $string_rabbits = '';

    $rabbit_new_id = 0;
        foreach ( $rabbits as $rabbit_id => $rabbit ){
            $rabbit_gender_shot = mb_substr($rabbit[5], 0, 1);
            $string_rabbit = "<tr><td>$rabbit_id</td><td><a href='index.php?action=mod&rabbitid=$rabbit_id'>$rabbit[0]</a></td><td>$rabbit[6]</td><td>".date('d-m-Y', strtotime($rabbit[4]))."</td><td>$rabbit_gender_shot</td><td>$rabbit[3]</td><td>$rabbit[9]</td><td>".date_next_injection($rabbit[10], $injections[trim($rabbit[11])])."".wrapper_days_prior_to_injection($rabbit[10], $injections[trim($rabbit[11])], $injections_limit_day)."</td><td><div class='erase-rabbit' rabbitid='".$rabbit_id."'>x</div></td</tr>"; //<a href='index.php?rabbitid=$rabbit_id&action=del'>x</a></td></tr>";//Добрый день!!!

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

?>