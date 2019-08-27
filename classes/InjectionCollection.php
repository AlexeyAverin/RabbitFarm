<?php



class InjectionCollection {

    public $injections;


    public function __construct( array $injections ) {
        
        $this->injections = $injections;
    }



    static public function injectionFromDBase(){
        /**function injections_from_dbase( $mysql ) */
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