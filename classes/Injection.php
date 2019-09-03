<?php



class Injection {
    //use secretTrait;

    CONST ACTION_UPD = 'upd';
    CONST ACTION_INS = 'ins';
    CONST STR_INJ = 'inj';

    //public $injectionid, $injectiontype, $injectiondate, $injectionfinish, $name, $breedingid, $injectionstatus;




    function __construct(){
        //echo var_dump($this->injectionid); //, $this->injectiontype, $this->injectiondate, $this->injectionfinish, $this->name, $this->breedingid, $this->injectionstatus,"<br>";
        //if ( $this->injectionid == null ) {
        //    $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        //    $this->injectionid = $connect_dbase->query('SELECT LAST_INSERT_ID();');

        //}
    }



    /*static public function getNextId( $mysql ){
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        //$select_injection_id_from_dbase = $connect_dbase->query('SHOW TABLE STATUS LIKE "injections";')->fetch()['Auto_increment'];
        $select_injection_id_from_dbase = $connect_dbase->query('SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME = "injections";')->fetch()[0];
        $connect_dbase = null;
        echo $select_injection_id_from_dbase;
    }*/
    
    
    static public function getNav(){
        /**Строка навигации */
        return '<nav><a href="index.php?str=rab">Кролики</a><a href="index.php?str=bre">Окролы</a><a href="index.php?str=cop">Случки</a><a class="selected" href="index.php?str=inj">Вакцины</a></nav>';

    }
    
    static public function getNewForm($mysql, $mens_womens, $injections_arr){
        /** Форма новой Вакцины */
        $injection_status   = 'checked';
        $name               = 'Нет данных';
        $injectiontype      = 'ABC';
    
        $injectiondate      = date('Y-m-d', time());
        $injectionfinish    = date('Y-m-d', time());

        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $select_injection_id_from_dbase = $connect_dbase->query('SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME = "injections";')->fetch()[0];
        $connect_dbase = null;

        return "<table class='rabbit'>
                    <tr><th colspan='5'>Учетные данные вакцины</th></tr>
                    <tr><td>ID Вакцины</td><td>Дата вакцинации</td><td>Тип вакцины</td><td>Дата следующей</td><td>ID Кролика</td><td>ID Окрола</td><td>С</td><td></td></tr>
                    <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' name='injectionid' value='".$select_injection_id_from_dbase."' readonly ></td><td><input name='injectiondate' value='{$injectiondate}' type='date'></td><td>".fill_select(array_keys($injections_arr), 'injectiontype', $injectiontype)."</td><td><input name='injectionfinish' value='{$injectionfinish}' type='date'></td><td>".fill_select($mens_womens, 'name', $name)."</td><td><input name='breedingid' type='number' value='' min='0'></td><td><input name='injectionstatus' ".$injection_status." type='checkbox'></td></tr>
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


    public function updateDBase( $mysql ){
        /** Метод измения данных */
        $this->injectiontype   = $_GET['injectiontype'];
        $this->injectiondate   = $_GET['injectiondate'];
        $this->injectionfinish = $_GET['injectionfinish'];
        $this->name            = $_GET['name'];
        $this->breedingid      = $_GET['breedingid'];
        $this->injectionstatus = $_GET['injectionstatus'];

        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $results_dbase = $connect_dbase->exec("UPDATE injections SET injectiontype='{$this->injectiontype}', injectiondate='{$this->injectiondate}', injectionfinish='{$this->injectionfinish}', name='{$this->name}', breedingid='{$this->breedingid}', injectionstatus='{$this->injectionstatus}' WHERE injectionid='{$this->injectionid}';");
        } catch ( PDOException $e ) {
            echo $e->getCode().':'.$e->getMessage();

        }
        $connect_dbase = null;
    }

    public function delereBdase( $mysql ){
        /** Метод удаления вакцины из базы данных */
    }

    public function getTR($counter){
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
                
                <td><div class='erase' str=".self::STR_INJ." id='{$counter}'>&Cross;</div></td>
                </tr>";
    }

    public function getEditForm($mens_womens, $injections_arr){
        /** Форма редактирования Вакцины */
        $injection_status = $this->injectionstatus == 'on' ? 'checked' : '';
        return "<table class='rabbit'>
                    <tr><th colspan='5'>Учетные данные вакцины</th></tr>
                    <tr><td>ID Вакцины</td><td>Дата вакцинации</td><td>Тип вакцины</td><td>Дата следующей</td><td>ID Кролика</td><td>ID Окрола</td><td>С</td><td></td></tr><tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' name='injectionid' value='{$this->injectionid}' readonly ></td><td><input name='injectiondate' value='{$this->injectiondate}' type='date'></td><td>".fill_select(array_keys($injections_arr), 'injectiontype', $this->injectiontype)."</td><td><input name='injectionfinish' value='{$this->injectionfinish}' type='date'></td><td>".fill_select($mens_womens, 'name', $this->name)."</td><td><input name='breedingid' type='number' value='{$this->breedingid}' min='0'></td><td><input name='injectionstatus' ".$injection_status." type='checkbox'></td></tr>
                    <tr><td></td><td></td><td></td><td><td></td><td><input name='id' value='".$_GET['id']."' type='hidden'><input name='str' value='".self::STR_INJ."' type='hidden'><input name='action' value='".self::ACTION_UPD."' type='hidden'><input value='Записать' type='submit'></td></form></tr>
                    <tr id='parcrtrab' colspan='5'></tr>
                </table>";
    }
}

?>