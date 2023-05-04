<?php
/*
 * SQLite | print queries
 * Code runner: PHP
 * Output format: JSON (tabular, SQL queries)
 */

require_once '/sqlite.phar';

$db = sqlite_phar_connect('/db.sql');

$pdo = $db->get_pdo();

$pdo->reset();
try {
  $db->query(file_get_contents($SCRIPT_PATH));
  echo json_encode($pdo->getLoggedQueries());
} catch (Exception $e) {
  echo $e->getMessage();
}