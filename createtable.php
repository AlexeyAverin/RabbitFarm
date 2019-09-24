#!/usr/bin/php
<?php
//Добрый день!

/**
 * Файл создает таблицы и заполняет их строками
 * chmode +x createtable.php
 * ./createtable.php
 */











$connect_dbase = new PDO('sqlite:rabbitfarm');
/** Создаем таблицу rabbits */
$connect_dbase->query("CREATE TABLE rabbits (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(70),
    status VARCHAR(70),
    breedingid VARCHAR(70),
    breed VARCHAR(70),
    birthdate DATE,
    gender VARCHAR(1),
    label VARCHAR(70),
    women VARCHAR(70),
    men VARCHAR(70),
    place VARCHAR(70),
    injectiondate DATE,
    injectiontype VARCHAR(70));");
/** Заполняем таблицу */
$connect_dbase->query("INSERT INTO rabbits (name, status, birthdate, gender, women, men, place, injectiondate, injectiontype) 
                                    VALUES ('Rabbit3', 'on', '2019-09-23', 'men', 'Rabbit1', 'Rabbit2', 'Place1', '2019-09-23', 'ABC');");

/** Создаем таблицу copulations */
$connect_dbase->query("CREATE TABLE copulations (
    couplingid INTEGER PRIMARY KEY AUTOINCREMENT,
    couplingdate DATE,
    couplingmen VARCHAR(70),
    couplingwomen VARCHAR(70),
    couplingplace VARCHAR(70));");

/** Заполняем таблицу */
$connect_dbase->query("INSERT INTO copulations (couplingdate, couplingmen, couplingwomen, couplingplace)
                                    VALUES ('2019-09-23', 'Rabbit1', 'Rabbit2', 'Place1')");

/** Создаем таблицу */
$connect_dbase->query("CREATE TABLE breedings (
    breedingid INTEGER PRIMARY KEY AUTOINCREMENT,
    breedingdate DATE,
    breedingnumberall INTEGER(2),
    breedingnumberlive INTEGER(2),
    couplingid INTEGER);");


$connect_dbase->query("INSERT INTO breedings (breedingdate, breedingnumberall, breedingnumberlive, couplingid)
                                    VALUES ('2019-09-23', '3', '3', '1')");


$connect_dbase = null;




?>