<?php



class Injection {
    CONST ACTION_UPD = 'upd';
    CONST ACTION_INS = 'ins';
    CONST STR_INJ = 'inj';








    static public function getNewForm($mens_womens, $injections_arr){
        /** Форма новой Вакцины */
        $injection_status   = 'checked';
        $name               = 'Нет данных';
        $injectiontype      = 'ABC';
    
        $injectiondate      = date('Y-m-d', time());
        $injectionfinish    = date('Y-m-d', time());
        return "<table class='rabbit'>
                    <tr><th colspan='5'>Учетные данные вакцины</th></tr>
                    <tr><td>ID Вакцины</td><td>Дата вакцинации</td><td>Тип вакцины</td><td>Дата следующей</td><td>ID Кролика</td><td>ID Окрола</td><td>С</td><td></td></tr><tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' name='injectionid' value='' readonly ></td><td><input name='injectiondate' value='{$injectiondate}' type='date'></td><td>".fill_select(array_keys($injections_arr), 'injectiontype', $injectiontype)."</td><td><input name='injectionfinish' value='{$injectionfinish}' type='date'></td><td>".fill_select($mens_womens, 'name', $name)."</td><td><input name='breedingid' type='number' value='' min='0'></td><td><input name='injectionstatus' ".$injection_status." type='checkbox'></td></tr>
                    <tr><td></td><td></td><td></td><td><td></td><td><input name='str' value='".self::STR_INJ."' type='hidden'><input name='action' value='".self::ACTION_INS."' type='hidden'><input value='Записать' type='submit'></td></form></tr>
                    <tr id='parcrtrab' colspan='5'></tr>
                </table>";

    }

    static public function insertDBase($mysql, $injections_arr){
        /** Метод вставляет Вакцину в базу данных */
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $injectionfinish = date_next_injection($_GET['injectiondate'], $injections_arr[trim($_GET['injectiontype'])], 1);
        try {

            $results_dbase = $connect_dbase->exec('INSERT INTO injections (injectiontype, injectiondate, injectionfinish, name, breedingid, injectionstatus) VALUES ("'.$_GET['injectiontype'].'", "'.$_GET['injectiondate'].'", "'.$injectionfinish.'", "'.$_GET['name'].'", "'.$_GET['breedingid'].'", "'.$_GET['injectionstatus'].'");');
        } catch ( PDOException $e ) {
            echo $e->getCode().':'.$e->getMessage();
        }
        $connect_dbase = null;
    }


    public function getHTML($counter){
        /** Выводит строчку Вакцины*/
        $injection_sign = $this->injectionstatus == 'on' ? '&#10004;' : '';
        return "<tr>
                <td><a href='index.php?str=inj&action=mod&id={$counter}'>{$this->injectionid}</a></td>
                <td>{$this->injectiontype}</td>
                <td>{$this->injectiondate}</td>
                <td>{$this->injectionfinish}</td>
                <td>{$this->name}</td>
                <td>{$this->breedingid}</td>
                <td>{$injection_sign}</td>
                
                <td><div class='erase' str=".self::STR_INJ." id='{$this->injectionid}'>&Cross;</div></td>
                </tr>";
    }

    public function getEditForm($mens_womens, $injections_arr){
        /** Форма редактирования Вакцины */
        $injection_status = $this->injectionstatus == 'on' ? 'checked' : '';
        return "<table class='rabbit'>
                    <tr><th colspan='5'>Учетные данные вакцины</th></tr>
                    <tr><td>ID Вакцины</td><td>Дата вакцинации</td><td>Тип вакцины</td><td>Дата следующей</td><td>ID Кролика</td><td>ID Окрола</td><td>С</td><td></td></tr><tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' name='injectionid' value='{$this->injectionid}' readonly ></td><td><input name='injectiondate' value='{$this->injectiondate}' type='date'></td><td>".fill_select(array_keys($injections_arr), 'injectiontype', $this->injectiontype)."</td><td><input name='injectionfinish' value='{$this->injectionfinish}' type='date'></td><td>".fill_select($mens_womens, 'name', $this->name)."</td><td><input name='breedingid' type='number' value='{$this->breedingid}' min='0'></td><td><input name='injectionstatus' ".$injection_status." type='checkbox'></td></tr>
                    <tr><td></td><td></td><td></td><td><td></td><td><input name='str' value='".self::STR_INJ."' type='hidden'><input name='action' value='".self::ACTION_UPD."' type='hidden'><input value='Записать' type='submit'></td></form></tr>
                    <tr id='parcrtrab' colspan='5'></tr>
                </table>";

    }
}

?>