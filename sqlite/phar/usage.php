<?php

require './sqlite.phar';

$db = sqlite_phar_connect();
$pdo = $db->get_pdo();
// $t = new WP_SQLite_Translator($sqlite);


// var_dump($db->query('CREATE TABLE test (id INTEGER PRIMARY KEY, name TEXT)'));
// $db->query('INSERT INTO test (name) VALUES ("test")');
// var_dump($db->query('SELECT * FROM test'));


$sql = "CREATE TABLE _dates (
    ID INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    option_name TEXT NOT NULL default ';',
    option_value DATE NOT NULL
);
INSERT INTO _dates VALUES(1,2,3);
";

print_r(split_into_queries($sql));

// // var_dump($db->query());
// $pdo->reset();
// $db->query("INSERT INTO _dates (option_name, option_value) VALUES ('first', '2016-01-15T00:00:00Z');");
// var_dump($pdo->getLoggedQueries());
// // var_dump($db->query('SELECT * FROM _dates'));
// // var_dump($db->query("SELECT * FROM _dates WHERE option_value BETWEEN '2016-01-15T00:00:00Z' AND '2016-01-17T00:00:00Z' ORDER BY ID;"));
// // var_dump($db->queries);


// // var_dump($db->query( "INSERT INTO _dates (option_name, option_value) VALUES ('first', '2014-10-21 00:42:29');" ));

// $pdo->reset();
// $db->query( "INSERT INTO _dates (option_name, option_value) VALUES ('second', '2014-10-21 01:42:29');" );
// print_r($db->get_results(
//     'SELECT 1 as test FROM DUAL;'
// ));

// // echo PhpMyAdmin\SqlParser\Utils\Formatter::format('SELECT 1 as test FROM DUAL WHERE x = :param0;', ['type' => 'html']);
// // echo PhpMyAdmin\SqlParser\Utils\Formatter::format('SELECT 1 as test FROM DUAL WHERE x = :param0;');