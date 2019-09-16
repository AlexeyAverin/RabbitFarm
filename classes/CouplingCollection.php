<?php



class CouplingCollection {
    
    CONST DBTABLE = 'copulations';

    public $items = [];

    public function __construct( $mysql ) {
        /** Загрузка из DBase и создание $injections */

        function createInjection($couplingid, $couplingdate, $couplingmen, $couplingwomen, $couplingplace){
            /** Создает эллемент  Injection */


            $item = new Copulation($couplingid, $couplingdate, $couplingmen, $couplingwomen, $couplingplace);
            return $item;
        }
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        try {

            $results = $connect_dbase->query('SELECT * FROM '.self::DBTABLE.';');
            $items = $results->fetchAll(PDO::FETCH_FUNC, "createInjection"); //$injections = $results->fetchAll(PDO::FETCH_CLASS, "Injection");
        } catch (PDOException $e) {
            echo ("Good day!!!<br> Error: " . $e->getMessage()."<br>");
            die();
        }
       $connect_dbase = null;
       $this->items = $items;

    }

    public function insertInCollection( $item ){
        /** Добавляет в коллекцию */
        array_push($this->items, $item);
    }



    public function deleteFromCollection($counter){
        /** Удаляет из коллекции */
        unset($this->items[$counter]);
    }

    public function getTABLE(){
        /** Создание Таблицы Вакцин */
        $string_couplings = '';
        $counter = 0;
        foreach ( $this->items as $item ){
            $string_couplings .= $item->getTR($counter);

            $counter++;
        }
        $string_middle = "<table class='ferma'>
        <tr><th>ID Случки</th><th>Дата</th><th>Самец</th><th>Самка</th><th>Клетка</th><th></th></tr>

        $string_couplings
        <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        <tr><td><a href='index.php?str=cop&action=new'>Добавить новую случку</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        </table>";
        return $string_middle;
    }

    public function getItem($counter){
        /** Возращает эллемент класса по попорядковому номеру */
        return $this->items[$counter];
    }

}

?>