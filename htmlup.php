<?php



$string_up = <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>

            
            html, body { margin: 0; padding: 0; font-family: monospace; --color-grey-soft: rgb(230, 230, 230); --color-grey-middle: rgb(170, 170, 170); --color-grey-dark: rgb(110, 110, 110); }
            div.contact { height: 50px; padding: 0 10px; display: flex; justify-content: space-between; color: rgb(170, 170, 170); }
            div.contact div { line-height: 50px; }
            header { width: 100%; display: flex; flex-direction: col; height: 70px; font-size: 20px; }
            header div.logotip { width: 70px; height: 100%; }
            header div.brand   { width: 210px; height: 100%; color: var(--color-grey-middle); line-height: 70px; }
            header nav         { height: ; 100%; flex-direction: row; justify-content: flex-end; display: flex; flex-grow: 1; }
            header nav:last-child { margin-right: 10px; }

            header nav a { color: var(--color-grey-middle); padding: 0 10px; line-height: 70px; text-decoration: none; font-size:; }
            header nav a:hover { background-color: var(--color-grey-dark); }
            section { width: 100%; height: calc(100vh - 120px); font-size: 15px; background-color: white; }
            table.ferma                       { width: 100%; margin: 0 0 50px 0; border-style: hidden; color: blue; }
            table.ferma td a                  { color: orange; text-decoration: none; }
            table.ferma th                    { text-align: left; color: white; background-color: var(--color-grey-dark); }
            table.ferma tr:hover              { background-color: var(--color-grey-soft); }
            table.ferma tr:nth-child(odd)     { color: var(--color-grey-middle); }
            table.ferma tr:nth-child(even)    { color: var(--color-grey-dark); }
            table.rabbit                    { width: 100%; margin: 0 0 50px 0; color: blue; border-style: hidden; }
            table.rabbit th                 { text-align: left; color: var(--color-grey-dark); }
            table.rabbit tr                 { text-align: center;  }
            table.rabbit tr:nth-child(odd)  { background-color: ; }

            table.rabbit tr:nth-child(even) { background-color: ; color: var(--color-grey-middle); }
            table.rabbit select             { width: 150px; margin-bottom: 19px; color: var(--color-grey-dark); }
            table.rabbit input              { width: 135px; margin-bottom: 19px; color: var(--color-grey-dark); }
            table.rabbit input[type=submit] { width: 340px; }

        </style>
        <title>Ферма кроликов</title>
    </head>
    <body>

        <form action="index.php"  method="GET">
        <header>
            <div class="logotip"><a href="index.php"><img src="rabbit.png"></a></div>
            <div class="brand">Добрый день!!!</div>
            <nav><a href="index.php">Главная</a><a href="index.php">Кролики</a><a href="">Статистика</a></nav>
        </header>
        </form>
        <section>
EOD;
?>