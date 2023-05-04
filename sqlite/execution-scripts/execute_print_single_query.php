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
  $queries = $pdo->getLoggedQueries();
  $out_queries = [];
  if (count($queries)) {
    foreach ($queries as $query) {
      $sql = $query['query'];
      if (str_starts_with($sql, 'BEGIN'))
        continue;
      if (str_contains($sql, '_mysql_data_types_cache'))
        continue;
      $out_queries = normalize_queries([$query]);
      break;
    }
  }
  echo json_encode($out_queries);
} catch (Exception $e) {
  echo $e->getMessage();
}