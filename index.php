<?php

session_start();

require 'functor.php';
mb_internal_encoding("UTF-8");
// functor.php
// sendmail.php
// men.js
// secret.php
// seting.php





$str = isset($_GET['str']) ? $_GET['str'] : null;
if ( !isset($str) ) {
    $string_nav = '';
    $string_middle = "<div class='secretContainer'><div class='secret'><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><input name='login' placeholder='login' type='text'><br><input name='password' placeholder='password' type='password'><br><input type='submit' value='Войти'></form></div></div>";
    $login = isset($_GET['login']) ? $_GET['login'] : '';
    $password = isset($_GET['password']) ? $_GET['password'] : '';
    if ( $login == 'Farmer' && $password == '777' ) {
        $str = 'rab';
    }

}
if ( $str == 'rab' ) { // Функции кроликов
    $string_nav = '<nav><a class="selected" href="index.php?str=rab">Кролики</a><a href="index.php?str=bre">Окролы</a><a href="index.php?str=cop">Случки</a><a href="index.php?str=inj">Вакцины</a></nav>';
    // Добавление данных зайца в MySQL
    if ( isset($_GET['action']) && $_GET['action'] == 'ins' ) {//echo "Good Day!!!";
        rabbit_insert_dbase( $mysql );
    }


    // Удаление данных кролика из MySQL
    if ( (isset($_GET['id'])) && $_GET['action'] == 'del' ) {//echo "Добрый вечер!!!";
        rabbit_delete_dbase( $mysql, $_GET['id'] ); 
    }

    // Изменений данных кролика в MySQL
    if ( (isset($_GET['rabbitid'])) && $_GET['action'] == 'upd' ) {
        rabbit_update_dbase( $mysql, $_GET['rabbitid'] ); ###
    }
 }
 // Считывание данных MySQL с целью того что массивы $mens и $women нужны в Кроликах и случках
 //$rabbits_mens_womens = array_from_mysql( $mysql, $mens, $womens );

 $rabbits_mens_womens = rabbits_from_dbase( $mysql, $mens, $womens );
 $rabbits = $rabbits_mens_womens[0];
 $mens = $rabbits_mens_womens[1];
 $womens = $rabbits_mens_womens[2];
if ( $str == 'cop' ) { // Функции случек
    $string_nav = '<nav><a href="index.php?str=rab">Кролики</a><a href="index.php?str=bre">Окролы</a><a class="selected" href="index.php?str=cop">Случки</a><a href="index.php?str=inj">Вакцины</a></nav>';
    // Добавление случки
    if ( isset($_GET['action']) ) {
        if ( $_GET['action'] == 'ins' ) {//echo "Good Day!!!";
            copulations_insert_dbase( $mysql );
        } elseif ( $_GET['action'] == 'del' ) {
            copulation_delete_dbase( $mysql );
        } elseif ( $_GET['action'] == 'upd' ) {
            copulation_update_dbase( $mysql );
        }
    }
    // Считывание данных MySQ по случке
    $copulations = copulations_from_dbase( $mysql );
}
if ( $str == 'bre' ) { // Функции окролов
    $string_nav = '<nav><a href="index.php?str=rab">Кролики</a><a class="selected" href="index.php?str=bre">Окролы</a><a href="index.php?str=cop">Случки</a><a href="index.php?str=inj">Вакцины</a></nav>';
    // Добавление нового окрола из случки
    if ( isset($_GET['action']) ) {
        if ( $_GET['action'] == 'ins' || $_GET['action'] == 'crtbre' ) {
            // Создание нового окрола в том числе из окрола
            breeding_from_copulation_to_mysql( $mysql );
        } elseif ( $_GET['action'] == 'del' ) {
            breeding_delete_mysql( $mysql );
        } elseif ( $_GET['action'] == 'upd' ) {
            breeding_update_mysql( $mysql );
        }

    }
    //Считывание данных MySQL по окролам
    $breedings = breedings_from_mysql( $mysql );
}


if ( $str == 'inj' ) { // Функции вакцин

    $string_nav = '<nav><a href="index.php?str=rab">Кролики</a><a href="index.php?str=bre">Окролы</a><a href="index.php?str=cop">Случки</a><a class="selected" href="index.php?str=inj">Вакцины</a></nav>';
    if ( isset($_GET['action']) ) {

    }
    //$injections;
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
            <div class='logotip'><a href='index.php' data-descr='Добрый день!!! Выход из сеанса!!!'></a></div>
            <div class='brand'>Добрый день!!!</div>
            
            $string_nav
        </header>
        </form>
        <script src='men.js'>
        </script>

        <section>";

if ( $str == 'rab' ) {
  // Отображается список кроликов в при простом отображении и при удалении кролика
  if ( !isset($_GET['action']) || $_GET['action'] == 'upd' || $_GET['action'] == 'ins' || $_GET['action'] == 'del' ) {
    $string_rabbits = '';
   
        foreach ( $rabbits as $rabbit_id => $rabbit ){

            $rabbit_gender_shot = mb_substr($rabbit[5], 0, 1);
            $rabbit_sign            = $rabbit[1] == 'on' ? '&#10004;' : '';
            $string_rabbit = "<tr><td>$rabbit_id</td><td>".$rabbit_sign."</td><td><a href='index.php?str=rab&action=mod&rabbitid=$rabbit_id'>$rabbit[0]</a></td><td>$rabbit[6]</td><td>".date('d-m-Y', strtotime($rabbit[4]))."</td><td>$rabbit_gender_shot</td><td>$rabbit[3]</td><td>$rabbit[9]</td><td>".date_next_injection($rabbit[10], $injections_arr[trim($rabbit[11])])."".wrapper_days_prior_to_injection($rabbit[10], $injections_arr[trim($rabbit[11])], $injections_limit_day)."</td><td><div class='erase' str='rab' id='".$rabbit_id."'>x</div></td</tr>"; //<a href='index.php?rabbitid=$rabbit_id&action=del'>x</a></td></tr>";//Добрый день!!!

            $string_rabbits .= $string_rabbit;
            //$rabbit_new_id = ++$rabbit_id;
        }
        $string_middle = <<<EOD
            <table class="ferma">

                <tr><th>ID Кролика</th><th>С</th><th>Кличка</th><th>Клемо</th><th>Дата рождения</th><th>Пол</th><th>Порода</th><th>Клетка</th><th>Прививка</th><th></th></tr>
                $string_rabbits
                <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
                <tr><td>...</td><td>...</td><td><a href="index.php?str=rab&action=new">Добавить нового кроллика</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
            </table>
EOD;

// Отображение общей информации по кролику, отображается при 'Вывод информации кролика' 'Добавление нового кролика' 

} elseif ( $_GET['action'] == 'new' || $_GET['action'] == 'mod' ) {
    $rabbit_id = isset($_GET['rabbitid']) ? $_GET['rabbitid'] : '';
    $rabbit_name = $rabbit_status = $rabbit_sign = $rabbit_breedingid = $rabbit_breed = $rabbit_birth_date = $rabbit_gender = $rabbit_label = $rabbit_women = $rabbit_men = $rabbit_place = $rabbit_injection_date = $rabbit_injection_type = '';

    if ( $_GET['action'] == 'mod' ) {
        $action_type = 'upd';
        $rabbit_name            = $rabbits[$rabbit_id][0];
        $rabbit_status          = $rabbits[$rabbit_id][1] == 'on' ? 'checked' : '';
        $rabbit_sign            = $rabbit_status == 'checked' ? '&#10004;' : '';
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
            <tr><td><input type='text' name='rabbitid' value='$rabbit_id' readonly></td><td><input name='name' maxlength='34' minlength='3' required pattern='[а-яА-Я0-9_]{3,34}' value='".$rabbit_name."' type='text'></td><td>".fill_select($breeds, 'breed', $rabbit_breed)."</td><td>".fill_select($genders, 'gender', $rabbit_gender)."</td><td><input type='text' maxlength='34' name='label' pattern='[а-яА-Яa-zA-Z0-9_]{0,34}' placeholder='Введите клеймо' value='$rabbit_label'></td></tr>
            <tr><td>ID Окрола</td><td>Крольчиха Мама</td><td>Кролик Отец</td><td>Дата рождения</td><td>Линия</td></tr>
            <tr><td><input type='number' name='breedingid' value='".$rabbit_breedingid."' min='0'></td><td>".fill_select($womens, 'women', $rabbit_women)."<td>".fill_select($mens, 'men', $rabbit_men)."</td><td><input name='birth' type='date' value=$rabbit_birth_date></td><td><select name='pedigree'><option>Мать - Отец</option><option>Матушка - Батюшка</option></select></td></tr>
            <tr><td>Клетка</td><td>Дата прививки</td><td>Прививка</td><td>Тип состояния</td><td> </td></tr>
            <tr><td>".fill_select($places, 'place', $rabbit_place)."</td><td><input type='date' name='injectiondate' id='id' value='$rabbit_injection_date'></td><td>".fill_select(array_keys($injections_arr), 'injectiontype', $rabbit_injection_type)."</td><td><input ".$rabbit_status." id='status' name='status' type='checkbox'><label for='status'>Активен</label></td><td><input name='str' value='rab' type='hidden'><input type='hidden' value='".$action_type."' name='action'><input type='hidden' name='rabbitid' value=".$rabbit_id."><input type='submit' value='Записать'>  </td></tr>

        </table>
    </form>";
    
    // Отображается дополнительная информация по кроликам 'Вывод информации кролика'
    if ( isset($_GET['rabbitid']) && (array_key_exists($_GET['rabbitid'], $rabbits)) ) {
        $copulations_rabbit = copulations_rabbit_mysql( $mysql, $rabbit_name );
        $string_middle .= "<table class='ferma'>
                <tr><td colspan='3'>Данные по случке</td></tr>

                <tr><th>ID Случки</th><th>Дата Случки</th><th>Самец</th><th>Самка</th><th>Ожидаемая дата окрола</th></tr>";
        $count_copulations = 0;
        foreach ( $copulations_rabbit as $copulation_id => $copulation ){
            $string_middle .= "<tr><td><a href='index.php?str=cop&action=mod&id=".$copulation_id."'>".$copulation_id."</a></td><td>".date('d-m-Y', strtotime($copulation[1]))."</td><td>".$copulation[2]."</td><td>".$copulation[3]."</td><td>".date_next_injection($copulation[1], '30')."</td></tr>";

            $count_copulations += 1;
        }
        $string_middle .= "
        
            <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td></tr>
            <tr><td>Суммарно случек: ".$count_copulations."</td><td></td><td></td><td></td><td></td></tr>
        </table>";


        
        
        $breedings_rabbit = breedings_rabbit( $mysql, $rabbit_name );
        $string_middle .= "<table class='ferma'>
        <tr><td colspan='3'>Данные по окролу</td></tr>

        <tr><th>ID Окрола</th><th>Дата окрола</th><th>Общее кол-во</th><th>Кол-во живых</th><th>ID Случки</th></tr>";
        $sum_number_all = 0;
        $sum_number_live = 0;
        $count_breedings = 0;

        foreach ( $breedings_rabbit as $breeding_id => $breeding ){
            $string_middle .= "<tr><td><a href='index.php?str=bre&action=mod&id=".$breeding_id."'>".$breeding_id."</a></td><td>".date('d-m-Y', strtotime($breeding[1]))."</td><td>".$breeding[2]."</td><td>".$breeding[3]."</td><td>".$breeding[4]."</td></tr>";
            $sum_number_all += $breeding[2];

            $sum_number_live += $breeding[3];
            $count_breedings += 1;
        }
        $string_middle .= "<tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td></tr>
                           <tr><td>Суммарно окролов: ".$count_breedings."</td><td></td><td>".$sum_number_all."</td><td>".$sum_number_live."</td><td></td></tr>

                        </table>";

        $string_middle .= "<table class='ferma'>
                <tr><td colspan='3'>Данные по вакцинации</td></tr>
                <tr><th>Наименование</th><th>Дата проведения</th><th>Следующая дата</th></tr>
                <tr><td>Ассоциированная</td><td>01.01.2001</td><td>01.07.2001</td></tr>
            </table>";

}
    }

} elseif ( $str == 'bre' ) {

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
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ( $_GET['action'] == 'mod' ) {
            $action_type = 'upd';
            $breedingdate = date('Y-m-d', strtotime($breedings[$id][1]));
            
        } elseif ( $_GET['action'] == 'new' ) {
            $action_type = 'ins';
            $breedingdate = date('Y-m-d', time());

        }

        $string_middle = "<table class='rabbit'>
        <tr><th colspan='5'>Учетные данные окрола</th></tr>
        <tr><td>ID Окрола</td><td>Дата</td><td>Кол-во всего</td><td>Кол-во живых</td><td>ID Случки</td></tr>
        <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' name='breedingid' value='".$id."' readonly ></td><td><input name='breedingdate' value='".$breedingdate."' type='date'></td><td><input name='breedingnumberall' value='".$breedings[$id][2]."' min='0' max='99' type='number'></td><td><input name='breedingnumberlive' value='".$breedings[$id][3]."' min='0' max='99' type='number'></td><td><input name='couplingid' type='number' value='".$breedings[$id][6]."' min='0'></td></tr>
        <tr><td></td><td></td><td></td><td><input name='str' value='bre' type='hidden'><input name='action' value='".$action_type."' type='hidden'><input value='Записать' type='submit'></td></form><td><input id='cmdcrtrab' couplingid='".$breedings[$id][6]."' breedingid='".$id."' birth='".$breedingdate."' type='button' value='Создать Кролика'></td></tr>
        <tr id='parcrtrab' colspan='5'></tr>
        </table>";
    }
    

}  elseif ( $str == 'cop' ) {

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
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ( $_GET['action'] == 'mod' ) {
            $action_type = 'upd';
            $couplingdate = date('Y-m-d', strtotime($copulations[$id][1]));
        } elseif ( $_GET['action'] == 'new' ) {
            $action_type = 'ins';
            $couplingdate = date('Y-m-d', time());
        }


        $string_middle = "
            <table class='rabbit'>
                <tr><th colspan='5'>Учетные данные случки</th></tr>
                <tr><td>ID Случки</td><td>Дата</td><td>Самец</td><td>Самка</td><td>Клетка</td><td></td></tr>
                <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' value='".$id."' name='couplingid' readonly></td><td><input name='couplingdate' value='".$couplingdate."' type='date'></td><td>".fill_select($mens, 'couplingmen', $copulations[$id][2])."</td><td>".fill_select($womens, 'couplingwomen', $copulations[$id][3])."</td><td>".fill_select($places, 'couplingplace', $copulations[$id][4])."</td><td><input name='str' value='cop' type='hidden'><input name='action' value='".$action_type."' type='hidden'><input type='submit' value='Записать'></td></form></tr>
                <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td></td><td> </td><td></td><td></td><td></td><td><input id='cmbcrtbre' couplingid='".$id."' type='button' value='Создать окрол'></td></form></tr>
                <tr><td id='parcrtbre' colspan='5'></td></tr>
            </table>";


    }

} elseif ( $str == 'inj' ) {
    $string_injection = '';
    $string_middle = "<table class='ferma'>
        <tr><th>ID Вакцины</th><th>Тип вакцины</th><th>Дата вакцинации</th><th>Дата окончания действия</th><th>ID Кролика</th><th>ID Окрола</th><th></th></tr>
        $string_injection
        <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        <tr><td><a href='index.php?str=cop&action=new'>Добавить новую вакцинацию</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        </table>";
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