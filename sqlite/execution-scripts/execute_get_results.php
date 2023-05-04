<?php
/*
 * SQLite | execute and get results
 * Code runner: PHP
 * Output format: JSON (tabular)
 */

require_once '/sqlite.phar';

$db = sqlite_phar_connect('/db.sql');

$pdo = $db->get_pdo();

$queries = split_into_queries(
  file_get_contents($SCRIPT_PATH)
);
for($i=0,$max=count($queries)-1;$i<=$max;$i++) {
  if($i === $max) {
    $result = $db->get_results($queries[$i]);
  } else {
    $db->query($queries[$i]);
  }
}

if($db->last_error) {
  echo $db->last_error;
} else {
  echo json_encode($result);
}
