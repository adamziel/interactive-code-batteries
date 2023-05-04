<?php

$phar = new Phar('sqlite.phar');
$phar->buildFromDirectory('./');
$phar->setDefaultStub('phar-index.php');


# cp source/constants.php logging-pdo.php class-wpdb.php phar-index.php source/sqlite-database-integration/wp-includes/sqlite


// $files = [
//     'db.php',
//     'constants.php',
//     'phar-index.php',
//     'class-wpdb.php',
//     'logging-pdo.php'
// ];
// foreach ($files as $file) {
//     copy("./$file", "../sqlite/$file");
// }

// $phar->buildFromDirectory('../sqlite');
// $phar->setDefaultStub('phar-index.php');