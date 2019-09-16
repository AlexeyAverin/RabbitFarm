<?php



class Copulation {
    
    CONST ACTION_UPD = 'upd';
    CONST ACTION_INS = 'ins';
    CONST ACTION_MOD = 'mod';
    CONST DBTABLE = 'copulations';  // Внести в Injection
    CONST CMBCRTBRE = 'cmbcrtbre';
    CONST STR = 'cop';//

    public $couplingid, $couplingdate, $couplingmen, $couplingwomen, $couplingplace;//


    function __construct($couplingid, $couplingdate, $couplingmen, $couplingwomen, $couplingplace){//     
        $this->couplingid = $couplingid;
        $this->couplingdate = $couplingdate;
        $this->couplingmen = $couplingmen;
        $this->couplingwomen = $couplingwomen;

        $this->couplingplace = $couplingplace;
    }
        
    static public function getNav(){//
        /**Строка навигации */
        return '<nav><a href="index.php?str=rab">Кролики</a><a href="index.php?str=bre">Окролы</a><a class="selected" href="index.php?str=cop">Случки</a><a href="index.php?str=inj">Вакцины</a></nav>';
    }
    

    static public function getNewForm($mysql, $mens, $womens, $places){//
        /** Форма новой Случки */
        $couplingdate  = date('Y-m-d', time());
        $name = 'Нет данных';
        $place = 'Клетка 1';
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);//
        $select_injection_id_from_dbase = $connect_dbase->query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME = '".self::DBTABLE."';")->fetch()[0];
        $connect_dbase = null;

        return "<table class='rabbit'>
                    <tr><th colspan='5'>Учетные данные случки</th></tr>
                    <tr><td>ID Случки</td><td>Дата</td><td>Самец</td><td>Самка</td><td>Клетка</td><td></td></tr>
                    <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' value='".$select_injection_id_from_dbase."' name='couplingid' readonly></td><td><input name='couplingdate' value='".$couplingdate."' type='date'></td><td>".fill_select($mens, 'couplingmen', $name)."</td><td>".fill_select($womens, 'couplingwomen', $name)."</td><td>".fill_select($places, 'couplingplace', $place)."</td><td><input name='str' value='".self::STR."' type='hidden'><input name='action' value='".self::ACTION_INS."' type='hidden'><input type='submit' value='Записать'></td></form></tr>
                    <tr><td id='parcrtbre' colspan='5'></td></tr>
                </table>";
    }

    static public function insertDBase($mysql){//
        /** Метод вставляет Случку в базу данных */
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $results_dbase = $connect_dbase->exec("INSERT INTO ".self::DBTABLE." (couplingdate, couplingmen, couplingwomen, couplingplace) VALUES ('".$_GET['couplingdate']."', '".$_GET['couplingmen']."', '".$_GET['couplingwomen']."', '".$_GET['couplingplace']."');");
        } catch ( PDOException $e ) {
            echo $e->getCode().':'.$e->getMessage();
        }
        $connect_dbase = null;
    }

    public function updateDBase( $mysql ){//
        /** Метод измения данных */
        $this->couplingid    = $_GET['couplingid'];
        $this->couplingdate  = $_GET['couplingdate'];
        $this->couplingmen   = $_GET['couplingmen'];
        $this->couplingwomen = $_GET['couplingwomen'];
        $this->couplingplace = $_GET['couplingplace'];
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $connect_dbase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $results_dbase = $connect_dbase->exec("UPDATE ".self::DBTABLE." SET couplingdate='{$this->couplingdate}', couplingmen='{$this->couplingmen}', couplingwomen='{$this->couplingwomen}', couplingplace='{$this->couplingplace}' WHERE couplingid='{$this->couplingid}';");
        } catch ( PDOException $e ) {
            echo $e->getCode().':'.$e->getMessage();
        }
        $connect_dbase = null;
    }

    public function deleteDBase( $mysql ){//

        /** Метод удаления Случки из базы данных */
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        $results_dbase = $connect_dbase->exec("DELETE FROM ".self::DBTABLE." WHERE couplingid='{$this->couplingid}';");
        $connect_dbase = null;
    }

    public function getTR($counter){
        /** Выводит строчку Случки*/

        return "<tr>
                <td><a href='index.php?str=".self::STR."&action=".self::ACTION_MOD."&id={$counter}'>{$this->couplingid}</a></td>
                <td>{$this->couplingdate}</td>
                <td>{$this->couplingmen}</td>
                <td>{$this->couplingwomen}</td>
                <td>{$this->couplingplace}</td>
                <td><div class='erase' str=".self::STR." id='{$counter}'>&Cross;</div></td>
                </tr>";

    }

    public function getEditForm($mens, $womens, $mysql, $places){
        /** Форма редактирования Случки */
        return "<table class='rabbit'>

            <tr><th colspan='5'>Учетные данные случки</th></tr>
            <tr><td>ID Случки</td><td>Дата</td><td>Самец</td><td>Самка</td><td>Клетка</td><td></td></tr>
            <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td><input type='text' value='{$this->couplingid}' name='couplingid' readonly></td><td><input name='couplingdate' value='{$this->couplingdate}' type='date'></td><td>".fill_select($mens, 'couplingmen', $this->couplingmen)."</td><td>".fill_select($womens, 'couplingwomen', $this->couplingwomen)."</td><td>".fill_select($places, 'couplingplace', $this->couplingplace)."</td><td><input name='str' value='".self::STR."' type='hidden'><input name='id' type='hidden' value='".$_GET['id']."'><input name='action' value='".self::ACTION_UPD."' type='hidden'><input type='submit' value='Записать'></td></form></tr>
            <tr><form method='GET' action='index.php' enctype='application/x-www-form-urlncoded'><td></td><td> </td><td></td><td></td><td></td><td><input id='".self::CMBCRTBRE."' couplingid='{$this->couplingid}' type='button' value='Создать окрол'></td></form></tr>
            <tr><td id='parcrtbre' colspan='5'></td></tr>
        </table>";
    }
}

?>