#!/usr/bin/php
<?php
//Добрый день!!!

/**
 * Файл проба Mongo так как PHP5 (устаревший)
 */









$cm = new \MongoClient();                                // создаем соединение
$bas = $cm->cinema;                                     // подключаемся к базе, не обязательно должна быть создана заранее
$col = $bas->movies;                                    // подключаемся к коллекции, на таблицу похоже
$doc = array("title" => "Plane Crazy Mickey Mouse",     // создаем документ
             "time" => "6",

             "color" => "No");
$col->insert( $doc );                                   // вставляем документ
$document = $col->findOne();                            // получение одного документа
//var_dump( $document );
//echo $col->count();                                   // подсчет документов коллекции
$que = array('time' => "6");                            // установка критериев запроса
$csr = $col->find( $que );
while ( $csr->hasNext() ) {

    var_dump( $csr->getNext() );

}