<?php



class BreedingCollection {

    CONST DBTABLE = 'breedings';
    CONST DBTABLETWO = 'copulations';

    public $items = [];

    public function __construct( $mysql ) {
        /** Загрузка из DBase и создание $injections */

        function createItem($breedingid, $breedingdate, $breedingnumberall, $breedingnumberlive, $couplingmen, $couplingwomen, $couplingid){
            /** Создает эллемент  Injection */


            $items = new Breeding($breedingid, $breedingdate, $breedingnumberall, $breedingnumberlive, $couplingmen, $couplingwomen, $couplingid);
            return $items;
        }
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);

        try {
            $results = $connect_dbase->query('SELECT breedingid, breedingdate, breedingnumberall, breedingnumberlive, couplingmen, couplingwomen, couplingid FROM '.self::DBTABLE.' NATURAL JOIN '.self::DBTABLETWO.' ORDER BY breedingdate;');
            $items = $results->fetchAll(PDO::FETCH_FUNC, "createItem"); //$injections = $results->fetchAll(PDO::FETCH_CLASS, "Injection");
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
        $string_items = '';
        $counter = 0;
        foreach ( $this->items as $item ){
            $string_items .= $item->getTR($counter);
            $counter++;
        }
        $string_middle = "<table class='ferma'>
        <tr><th>ID Окрола</th><th>Дата окрола</th><th>Кол-во общее</th><th>Кол-во живых</th><th>Самец</th><th>Самка</th><th>ID Случки</th><th></th></tr>
            $string_items
        <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        <tr><td><a href='index.php?str=bre&action=new'>Добавить новый окрол</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td></tr>
        </table>";
        return $string_middle;
    }

    public function getItem($counter){
        /** Возращает эллемент класса по попорядковому номеру */
        return $this->items[$counter];
    }
}

?>