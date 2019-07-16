<?php

session_start();

require 'functor.php';
mb_internal_encoding("UTF-8");
// functor.php
// sendmail.php
// men.js
// secret.php
// seting.php







// Считывание данных MySQL с целью того что массивы $mens и $women нужны в Кроликах и случках
$rabbits_mens_womens = array_from_mysql( $mysql, $mens, $womens );
$rabbits = $rabbits_mens_womens[0];
$mens = $rabbits_mens_womens[1];
$womens = $rabbits_mens_womens[2];

if ( !isset($_GET['str']) ) {
    $string_nav = '';
    $string_middle = "<div class='secret'><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><input name='login' placeholder='login' type='text'><br><input name='password' placeholder='password' type='password'><br><input type='submit' value='Войти'></form></div>";
    if ( $_GET['login'] == 'Farmer' && $_GET['password'] == '777' ) {
        $_GET['str'] = 'rab';
    }

}
if ( $_GET['str'] == 'rab' ) { // Функции кроликов
    $string_nav = '<nav><a class="selected" href="index.php?str=rab">Кролики</a><a href="index.php?str=bre">Окролы</a><a href="index.php?str=cop">Случки</a></nav>';
    // Добавление данных зайца в MySQL
    if ( $_GET['action'] == 'ins' ) {//echo "Good Day!!!";
        string_to_mysql( $mysql );
    }


    // Удаление данных кролика из MySQL
    if ( (isset($_GET['id'])) && $_GET['action'] == 'del' ) {//echo "Добрый вечер!!!";
        string_delete_mysql( $mysql, $_GET['id'] ); 
    }

    // Изменений данных кролика в MySQL
    if ( (isset($_GET['rabbitid'])) && $_GET['action'] == 'upd' ) {
        update_string_mysql( $mysql, $_GET['rabbitid'] ); ###
    }
}
if ( $_GET['str'] == 'cop' ) { // Функции случек
    $string_nav = '<nav><a href="index.php?str=rab">Кролики</a><a href="index.php?str=bre">Окролы</a><a class="selected" href="index.php?str=cop">Случки</a></nav>';
    // Добавление случки
    if ( $_GET['action'] == 'ins' ) {//echo "Good Day!!!";
        copulations_to_mysql( $mysql );
    } elseif ( $_GET['action'] == 'del' ) {
        copulation_delete_mysql( $mysql );
    } elseif ( $_GET['action'] == 'upd' ) {
        copulation_update_mysql( $mysql );
    }

    // Считывание данных MySQ по случке
    $copulations = copulations_from_mysql( $mysql );
}
if ( $_GET['str'] == 'bre' ) { // Функции окролов
    $string_nav = '<nav><a href="index.php?str=rab">Кролики</a><a class="selected" href="index.php?str=bre">Окролы</a><a href="index.php?str=cop">Случки</a></nav>';
    // Добавление нового окрола из случки
    if ( $_GET['action'] == 'ins' || $_GET['action'] == 'crtbre' ) {
        // Создание нового окрола в том числе из окрола
        breeding_from_copulation_to_mysql( $mysql );
    } elseif ( $_GET['action'] == 'del' ) {
        breeding_delete_mysql( $mysql );
    } elseif ( $_GET['action'] == 'upd' ) {
        breeding_update_mysql( $mysql );
    }
    //Считывание данных MySQL по окролам
    $breedings = breedings_from_mysql( $mysql );
}



// Отображение страницы
$string_up = "
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>

        <link href='rabbits.css' rel='stylesheet'>
        <title>Ферма кроликов</title>
    </head>
    <body>
        <form action='index.php'  method='GET' enctype='application/x-www-form-urlncoded'>
        <header>
            <div class='logotip'><a href='index.php'><img src='rabbit.png'></a></div>
            <div class='brand'>Добрый день!!!</div>
            
            $string_nav
        </header>
        </form>
        <script src='men.js'>
        </script>

        <section>";

if ( $_GET['str'] == 'rab' ) {
  // Отображается список кроликов в при простом отображении и при удалении кролика
  if ( !isset($_GET['action']) || $_GET['action'] == 'upd' || $_GET['action'] == 'ins' || $_GET['action'] == 'del' ) {
    $string_rabbits = '';
   
        foreach ( $rabbits as $rabbit_id => $rabbit ){
            $rabbit_gender_shot = mb_substr($rabbit[5], 0, 1);
            $string_rabbit = "<tr><td>$rabbit_id</td><td><a href='index.php?str=rab&action=mod&rabbitid=$rabbit_id'>$rabbit[0]</a></td><td>$rabbit[6]</td><td>".date('d-m-Y', strtotime($rabbit[4]))."</td><td>$rabbit_gender_shot</td><td>$rabbit[3]</td><td>$rabbit[9]</td><td>".date_next_injection($rabbit[10], $injections[trim($rabbit[11])])."".wrapper_days_prior_to_injection($rabbit[10], $injections[trim($rabbit[11])], $injections_limit_day)."</td><td><div class='erase' str='rab' id='".$rabbit_id."'>x</div></td</tr>"; //<a href='index.php?rabbitid=$rabbit_id&action=del'>x</a></td></tr>";//Добрый день!!!

            $string_rabbits .= $string_rabbit;
            //$rabbit_new_id = ++$rabbit_id;
        }
        $string_middle = <<<EOD
            <table class="ferma">

                <tr><th>ID Кролика</th><th>Кличка</th><th>Клемо</th><th>Дата рождения</th><th>Пол</th><th>Порода</th><th>Клетка</th><th>Прививка</th><th></th></tr>
                $string_rabbits
                <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
                <tr><td>...</td><td><a href="index.php?str=rab&action=new">Добавить нового кроллика</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
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
            <tr><th colspan='5'>Персональный данные кролика</th></tr>
            <tr><td>ID Кролика</td><td>Кличка</td><td>Порода</td><td>Пол</td><td>Клеймо</td></tr>
            <tr><td><input type='text' name='rabbitid' value='$rabbit_id' disabled></td><td><input name='name' maxlength='34' minlength='3' required pattern='[а-яА-Яa-zA-Z0-9_]{3,34}' value='".$rabbit_name."' type='text'></td><td>".fill_select($breeds, 'breed', $rabbit_breed)."</td><td>".fill_select($genders, 'gender', $rabbit_gender)."</td><td><input type='text' maxlength='34' name='label' pattern='[а-яА-Яa-zA-Z0-9_]{0,34}' placeholder='Введите клеймо' value='$rabbit_label'></td></tr>
            <tr><td>ID Окрола</td><td>Крольчиха Мама</td><td>Кролик Отец</td><td>Дата рождения</td><td>Линия</td></tr>
            <tr><td><input type='number' name='breedingid' value='".$rabbit_breedingid."' min='0'></td><td>".fill_select($womens, 'women', $rabbit_women)."<td>".fill_select($mens, 'men', $rabbit_men)."</td><td><input name='birth' type='date' value=$rabbit_birth_date></td><td><select name='pedigree'><option>Мать - Отец</option><option>Матушка - Батюшка</option></select></td></tr>
            <tr><td>Клетка</td><td>Дата прививки</td><td>Прививка</td><td></td><td> </td></tr>
            <tr><td>".fill_select($places, 'place', $rabbit_place)."</td><td><input type='date' name='injectiondate' id='id' value='$rabbit_injection_date'></td><td>".fill_select(array_keys($injections), 'injectiontype', $rabbit_injection_type)."</td><td> </td><td><input name='str' value='rab' type='hidden'><input type='hidden' value='".$action_type."' name='action'><input type='hidden' name='rabbitid' value=".$rabbit_id."><input type='submit' value='Записать'>  </td></tr>
        </table>
    </form>";
    
    // Отображается дополнительная информация по кроликам 'Вывод информации кролика'
    if ( (array_key_exists($_GET['rabbitid'], $rabbits)) ) {
        $copulations_rabbit = copulations_rabbit_mysql( $mysql, $rabbit_name );
        $string_middle .= "<table class='ferma'>
                <tr><td colspan='3'>Данные по случке</td></tr>
                <tr><th>ID Случки</th><th>Дата Случки</th><th>Самец</th><th>Самка</th><th>Ожидаемая дата окрола</th></tr>";
        foreach ( $copulations_rabbit as $copulation_id => $copulation ){
            $string_middle .= "<tr><td>".$copulation_id."</td><td>".date('d-m-Y', strtotime($copulation[1]))."</td><td>".$copulation[2]."</td><td>".$copulation[3]."</td><td>".date_next_injection($copulation[1], '30')."</td></tr>";
        }

        $string_middle .= "</table>";


        
        
        $breedings_rabbit = breedings_rabbit( $mysql, $rabbit_name );
        $string_middle .= "<table class='ferma'>
        <tr><td colspan='3'>Данные по окролу</td></tr>

        <tr><th>ID Окрола</th><th>Дата окрола</th><th>Общее кол-во</th><th>Кол-во живых</th><th>ID Случки</th></tr>";
        foreach ( $breedings_rabbit as $breeding_id => $breeding ){
            $string_middle .= "<tr><td>".$breeding_id."</td><td>".date('d-m-Y', strtotime($breeding[1]))."</td><td>".$breeding[2]."</td><td>".$breeding[3]."</td><td>".$breeding[4]."</td></tr>";

        }
        $string_middle .= "</table>";

        $string_middle .= "<table class='ferma'>
                <tr><td colspan='3'>Данные по вакцинации</td></tr>
                <tr><th>Наименование</th><th>Дата проведения</th><th>Следующая дата</th></tr>
                <tr><td>Ассоциированная</td><td>01.01.2001</td><td>01.07.2001</td></tr>
            </table>";

}
    }
}  elseif ( $_GET['str'] == 'bre' ) {

    if ( !isset($_GET['action']) || $_GET['action'] == 'crtbre' || $_GET['action'] == 'upd' || $_GET['action'] == 'ins' || $_GET['action'] == 'del' ) {
        $string_breedings = '';
        foreach ( $breedings as $breeding_id => $breeding ){
            $string_breeding = '<tr><td><a href="index.php?str=bre&action=mod&id='.$breeding_id.'">'.$breeding_id.'</a></td><td>'.date('d-m-Y', strtotime($breeding[1])).'</td><td>'.$breeding[2].'</td><td>'.$breeding[3].'</td><td>'.$breeding[4].'</td><td>'.$breeding[5].'</td><td>'.$breeding[6].'</td><td><div class="erase" str="bre" id="'.$breeding_id.'">x</div></td></tr>';
            $string_breedings .= $string_breeding;
        }
        
        
        
        $string_middle = "<table class='ferma'>
        <tr><th>ID Окрола</th><th>Дата окрола</th><th>Кол-во общее</th><th>Кол-во живых</th><th>Самец</th><th>Самка</th><th>ID Случки</th><th></th></tr>
        $string_breedings
        <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        <tr><td><a href='index.php?str=bre&action=new'>Добавить новый окрол</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        </table>";
    } elseif ( $_GET['action'] == 'new' || $_GET['action'] == 'mod' ) {
        if ( $_GET['action'] == 'mod' ) {
            $action_type = 'upd';
            $breedingdate = date('Y-m-d', strtotime($breedings[$_GET['id']][1]));
            
        } elseif ( $_GET['action'] == 'new' ) {
            $action_type = 'ins';
            $breedingdate = date('Y-m-d', time());

        }

        $string_middle = "<table class='rabbit'>
        <tr><th colspan='5'>Учетные данные окрола</th></tr>
        <tr><td>ID Окрола</td><td>Дата</td><td>Кол-во всего</td><td>Кол-во живых</td><td>ID Случки</td></tr>
        <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' name='breedingid' value='".$_GET['id']."' readonly ></td><td><input name='breedingdate' value='".$breedingdate."' type='date'></td><td><input name='breedingnumberall' value='".$breedings[$_GET['id']][2]."' min='0' max='99' type='number'></td><td><input name='breedingnumberlive' value='".$breedings[$_GET['id']][3]."' min='0' max='99' type='number'></td><td><input name='couplingid' type='number' value='".$breedings[$_GET['id']][6]."' min='0'></td></tr>
        <tr><td></td><td></td><td></td><td><input name='str' value='bre' type='hidden'><input name='action' value='".$action_type."' type='hidden'><input value='Записать' type='submit'></td></form></tr>
        </table>";
    }
    

}  elseif ( $_GET['str'] == 'cop' ) {

    if ( !isset($_GET['action']) || $_GET['action'] == 'ins' || $_GET['action'] == 'upd' || $_GET['action'] == 'del' ) {
        $string_couplings = '';
        foreach ( $copulations as $coupling_id => $coupling ){
            $string_coupling = '<tr><td><a href="index.php?str=cop&action=mod&id='.$coupling_id.'">'.$coupling[0].'</a></td><td>'.date('d-m-Y', strtotime($coupling[1])).'</td><td>'.$coupling[2].'</td><td>'.$coupling[3].'</td><td>'.$coupling[4].'</td><td><div class="erase" str="cop" id="'.$coupling_id.'" href="">x</div></td></tr>';
            $string_couplings .= $string_coupling;
        }
        
        
        
        
        $string_middle = "<table class='ferma'>
        <tr><th>ID Случки</th><th>Дата</th><th>Самец</th><th>Самка</th><th>Клетка</th><th></th></tr>
        $string_couplings
        <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        <tr><td><a href='index.php?str=cop&action=new'>Добавить новую случку</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        </table>";
    } elseif ( $_GET['action'] == 'new' || $_GET['action'] == 'mod' ) {

        if ( $_GET['action'] == 'mod' ) {
            $action_type = 'upd';
            $couplingdate = date('Y-m-d', strtotime($copulations[$_GET['id']][1]));
        } elseif ( $_GET['action'] == 'new' ) {
            $action_type = 'ins';
            $couplingdate = date('Y-m-d', time());
        }


        $string_middle = "
            <table class='rabbit'>
                <tr><th colspan='5'>Учетные данные случки</th></tr>
                <tr><td>ID Случки</td><td>Дата</td><td>Самец</td><td>Самка</td><td>Клетка</td><td></td></tr>
                <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' value='".$_GET['id']."' name='couplingid' readonly></td><td><input name='couplingdate' value='".$couplingdate."' type='date'></td><td>".fill_select($mens, 'couplingmen', $copulations[$_GET['id']][2])."</td><td>".fill_select($womens, 'couplingwomen', $copulations[$_GET['id']][3])."</td><td>".fill_select($places, 'couplingplace', $copulations[$_GET['id']][4])."</td><td><input name='str' value='cop' type='hidden'><input name='action' value='".$action_type."' type='hidden'><input type='submit' value='Записать'></td></form></tr>
                <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td></td><td> </td><td></td><td></td><td></td><td><input id='cmbcrtbre' couplingid='".$_GET['id']."' type='button' value='Создать окрол'></td></form></tr>
                <tr><td id='parcrtbre' colspan='5'></td></tr>
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