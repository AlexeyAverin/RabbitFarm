<?php



class InjectionCollection {
    //use secretTrait;

    public $injections = [];

    public function __construct( $mysql ) {
        /** Загрузка из DBase и создание $injections */

        function createInjection($injectionid, $injectiontype, $injectiondate, $injectionfinish, $name, $breedingid, $injectionstatus){
            /** Создает эллемент  Injection */


            $injection = new Injection($injectionid, $injectiontype, $injectiondate, $injectionfinish, $name, $breedingid, $injectionstatus);
            //$injections[] = $injection;
            return $injection;
        }
        $connect_dbase = new PDO('mysql:host=' . $mysql['node'] . ";" . 'dbname=' . $mysql['dbase'], $mysql['user'], $mysql['passwd']);
        try {
            $results = $connect_dbase->query('SELECT * FROM injections;');

            $injections = $results->fetchAll(PDO::FETCH_FUNC, "createInjection"); //$injections = $results->fetchAll(PDO::FETCH_CLASS, "Injection");
            // var_dump($injections);
        } catch (PDOException $e) {
            echo ("Good day!!!<br> Error: " . $e->getMessage()."<br>");
            die();
        }


       $connect_dbase = null;
       $this->injections = $injections;
    }

    public function insertInIC( $injection ){
        /**  */
        
    }


    public function getTABLE(){
        /** Создание Таблицы Вакцин */
        $string_injection = '';
        $counter = 0;
        foreach ( $this->injections as $injection ){
            $string_injection .= $injection->getTR($counter);

            $counter++;
        }
        $string_middle = "<table class='ferma'>
        <tr><th>ID Вакцины</th><th>Тип вакцины</th><th>Дата вакцинации</th><th>Дата следующей</th><th>ID Кролика</th><th>ID Окрола</th><th>C</th><th></th></tr>
        $string_injection 
        <tr><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td><td>.</td></tr>
        <tr><td><a href='index.php?str=inj&action=new'>Добавить новую вакцинацию</a></td><td>...</td><td>...</td><td>...</td><td>...</td><td>...</td><td>.</td><td>.</td></tr>

        </table>";
        return $string_middle;
    }

    public function getInjection($counter){
        /** Возращает эллемент класса по попорядковому номеру */
        return $this->injections[$counter];
    }

}

?>