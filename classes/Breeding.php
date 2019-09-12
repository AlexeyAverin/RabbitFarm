<?php



class Breeding {
    
    CONST ACTION_UPD = 'upd';
    CONST ACTION_INS = 'ins';
    CONST ACTION_MOD = 'mod';
    CONST DBTABLE = 'breedings';  // Внести в Injection
    CONST CMBCRTBRE = 'cmbcrtbre';
    CONST STR = 'bre';//

    public $breedingid, $breedingdate, $breedingnumberall, $breedingnumberlive, $couplingmen, $couplingwomen, $couplingid;//


    function __construct($breedingid, $breedingdate, $breedingnumberall, $breedingnumberlive, $couplingmen, $couplingwomen, $couplingid){//     
        $this->breedingid = $breedingid;
        $this->breedingdate = $breedingdate;
        $this->breedingnumberall = $breedingnumberall;
        $this->breedingnumberlive = $breedingnumberlive;

        $this->couplingmen = $couplingmen;
        $this->couplingwomen = $couplingwomen;
        $this->couplingid = $couplingid;
    }
        
    static public function getNav(){//
        /**Строка навигации */

        return '<nav><a href="index.php?str=rab">Кролики</a><a class="selected" href="index.php?str=bre">Окролы</a><a href="index.php?str=cop">Случки</a><a href="index.php?str=inj">Вакцины</a></nav>';
    }
    
    static public function getNewForm( $mysql ){//
        /** Форма новой Случки */
        $breedingdate  = date('Y-m-d', time());
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);//
        $select_item_id = $connect_dbase->query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME = '".self::DBTABLE."';")->fetch()[0];
        $connect_dbase = null;

        return "<table class='rabbit'>
            <tr><th colspan='5'>Учетные данные окрола</th></tr>
            <tr><td>ID Окрола</td><td>Дата</td><td>Кол-во всего</td><td>Кол-во живых</td><td>ID Случки</td></tr>
            <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' name='breedingid' value='".$select_item_id."' readonly ></td><td><input name='breedingdate' value='".$breedingdate."' type='date'></td><td><input name='breedingnumberall' value='0' min='0' max='99' type='number'></td><td><input name='breedingnumberlive' value='0' min='0' max='99' type='number'></td><td><input name='couplingid' type='number' value='0' min='0'></td></tr>
            <tr><td></td><td></td><td></td><td><input name='str' value='".self::STR."' type='hidden'><input name='action' value='".self::ACTION_INS."' type='hidden'><input value='Записать' type='submit'></td></form><td><!--<input id='cmdcrtrab' couplingid='".$breedings[$id][6]."' breedingid='".$id."' birth='".$breedingdate."' type='button' value='Создать Кролика'>--></td></tr>
            <tr id='parcrtrab' colspan='5'></tr>
        </table>";

    }

    static public function insertDBase($mysql){//
        /** Метод вставляет Случку в базу данных */
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $results_dbase = $connect_dbase->exec("INSERT INTO ".self::DBTABLE." (breedingdate, breedingnumberall, breedingnumberlive, couplingid) VALUES ('".$_GET['breedingdate']."', '".$_GET['breedingnumberall']."', '".$_GET['breedingnumberlive']."', '".$_GET['couplingid']."');");

        } catch ( PDOException $e ) {
            echo $e->getCode().':'.$e->getMessage();
        }
        $connect_dbase = null;
    }


    public function updateDBase( $mysql ){//
        /** Метод измения данных */
        $this->breedingid = $_GET['breedingid'];
        $this->breedingdate = $_GET['breedingdate'];
        $this->breedingnumberall = $_GET['breedingnumberall'];
        $this->breedingnumberlive = $_GET['breedingnumberlive'];
        $this->couplingid = $_GET['couplingid'];
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {

            $results_dbase = $connect_dbase->exec("UPDATE ".self::DBTABLE." UPDATE breedings SET breedingdate='{$this->breedingdate}', breedingnumberall='{$this->breedingnumberall}', breedingnumberlive='{$this->breedingnumberlive}', couplingid='{$this->couplingid}' WHERE breedingid='{$this->breedingid}';");
        } catch ( PDOException $e ) {
            echo $e->getCode().':'.$e->getMessage();

        }
        $connect_dbase = null;
    }

    public function deleteDBase( $mysql ){//
        /** Метод удаления Случки из базы данных */
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $results_dbase = $connect_dbase->exec("DELETE FROM ".self::DBTABLE." WHERE breedingid='{$this->breedingid}';");

        $connect_dbase = null;
    }

    public function getTR($counter){
        /** Выводит строчку Случки*/
        return "<tr>
                <td><a href='index.php?str=".self::STR."&action=".self::ACTION_MOD."&id={$counter}'>{$this->breedingid}</a></td>
                <td>{$this->breedingdate}</td>

                <td>{$this->breedingnumberall}</td>
                <td>{$this->breedingnumberlive}</td>
                <td>{$this->couplingmen}</td>
                <td>{$this->couplingwomen}</td>
                <td>{$this->couplingid}</td>

                <td><div class='erase' str=".self::STR." id='{$counter}'>&Cross;</div></td>
                </tr>";

    }

    public function getEditForm( $mysql ){
        /** Форма редактирования Случки */
        return "<table class='rabbit'>
            <tr><th colspan='5'>Учетные данные окрола</th></tr>
            <tr><td>ID Окрола</td><td>Дата</td><td>Кол-во всего</td><td>Кол-во живых</td><td>ID Случки</td></tr>
            <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' name='breedingid' value='{$this->breedingid}' readonly ></td><td><input name='breedingdate' value='{$this->breedingdate}' type='date'></td><td><input name='breedingnumberall' value='{$this->breedingnumberall}' min='0' max='99' type='number'></td><td><input name='breedingnumberlive' value='{$this->breedingnumberlive}' min='0' max='99' type='number'></td><td><input name='couplingid' type='number' value='{$this->couplingid}' min='0'></td></tr>
            <tr><td></td><td></td><td></td><td><input name='str' value='".self::STR."' type='hidden'><input name='action' value='".self::ACTION_INS."' type='hidden'><input value='Записать' type='submit'></td></form><td><input id='cmdcrtrab' couplingid='".$breedings[$id][6]."' breedingid='".$id."' birth='".$breedingdate."' type='button' value='Создать Кролика'></td></tr>
            <tr id='parcrtrab' colspan='5'></tr>
        </table>";
    }
}

?>