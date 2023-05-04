<?php

function apply_filters($filter, $value)
{
    return $value;
}

function wp_load_translations_early()
{
}

function wp_debug_backtrace_summary()
{
    return '';
}

if (!function_exists('mb_substr')) {
    function mb_substr($a, $b, $c)
    {
        return substr($a, $b, $c);
    }
}

function wp_die($msg, $title = '', $args = [])
{
    var_dump($msg);
    var_dump($title);
    var_dump($args);
    exit;
}

define('WP_DEBUG', false);
define('FQDBDIR', dirname(Phar::running(false)));
define('DATABASE_TYPE', 'sqlite');
$table_prefix = 'wp_';
$er = error_reporting();
error_reporting($er & ~E_DEPRECATED);
require 'phar://sqlite.phar/logging-pdo.php';
require 'phar://sqlite.phar/class-wpdb.php';
require 'phar://sqlite.phar/db.php';
// require 'phar://sqlite.phar/vendor/autoload.php';
// error_reporting($er);

class Phar_WP_SQLite_DB extends WP_SQLite_DB
{
    public function get_translator()
    {
        return $this->dbh;
    }
    public function get_pdo()
    {
        return $this->dbh->get_pdo();
    }
}

function sqlite_phar_connect($file = ':memory:')
{
    $pdo = new LoggingPDO('sqlite:' . $file);
    $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
    $pdo->query(WP_SQLite_Translator::CREATE_DATA_TYPES_CACHE_TABLE);
    $GLOBALS['@pdo'] = $pdo;
    new WP_SQLite_PDO_User_Defined_Functions($GLOBALS['@pdo']);
    $db = new Phar_WP_SQLite_DB();
    $db->db_connect();
    unset($GLOBALS['@pdo']);
    return $db;
}

function split_into_queries($sql) {
    $tokens = ( new WP_SQLite_Lexer( $sql ) )->tokens;

    $queries = [];
    $query = '';
    foreach($tokens as $token) {
        if($token->type === WP_SQLite_Token::TYPE_DELIMITER && $token->value === ';') {
            $queries[] = trim($query);
            $query = '';
            continue;
        }
        $query .= $token->token;
    }
    if(trim($query)) {
        $queries[] = trim($query);
    }

    return $queries;
}
