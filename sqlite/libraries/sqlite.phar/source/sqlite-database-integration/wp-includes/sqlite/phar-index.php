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
    function mb_substr($a,$b,$c)
    {
        return substr($a,$b,$c);
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

function sqlite_phar_connect()
{
    $pdo = new LoggingPDO('sqlite::memory:');
    $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
    $pdo->query(WP_SQLite_Translator::CREATE_DATA_TYPES_CACHE_TABLE);
    $GLOBALS['@pdo'] = $pdo;
    new WP_SQLite_PDO_User_Defined_Functions($GLOBALS['@pdo']);
    $db = new Phar_WP_SQLite_DB();
    $db->db_connect();
    unset($GLOBALS['@pdo']);
    return $db;
}

function results_as_html_table($results)
{
    if (!count($results)) {
        return '';
    }
    // Get the keys of the first array in $results to use as table headers
    $headers = array_keys((array) reset($results));

    // Generate the HTML for the table headers
    $header_html = '<tr>';
    foreach ($headers as $header) {
        $header_html .= '<th>' . $header . '</th>';
    }
    $header_html .= '</tr>';

    // Generate the HTML for the table rows
    $row_html = '';
    foreach ($results as $result) {
        $row_html .= '<tr>';
        foreach ((array) $result as $value) {
            $row_html .= '<td>' . $value . '</td>';
        }
        $row_html .= '</tr>';
    }

    // Generate the final HTML table
    $table_html = '<table class="pure-table">';
    $table_html .= $header_html;
    $table_html .= $row_html;
    $table_html .= '</table>';
    $table_html .= '<style>
    .pure-table {
        border: 1px solid #cbcbcb;
        border-spacing: 0;
        empty-cells: show
    }
    
    .pure-table caption {
        color: #000;
        font: italic 85%/1 arial,sans-serif;
        padding: 1em 0
    }
    
    .pure-table td,.pure-table th {
        border-bottom-width: 0;
        border-left: 1px solid #cbcbcb;
        border-right-width: 0;
        border-top-width: 0;
        font-size: inherit;
        margin: 0;
        overflow: visible
    }
    
    .pure-table thead {
        background-color: #e0e0e0;
        color: #000;
        text-align: left;
        vertical-align: bottom
    }
    
    .pure-table td {
        background-color: initial
    }
    
    .pure tr:nth-child(2n-1) td {
        background-color: #f2f2f2
    }
    </style>';

    // Return the HTML table
    return $table_html;
}