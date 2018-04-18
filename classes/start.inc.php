<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

//
$settings = array();
require_once dirname(__FILE__) . "/../sites/default/settings.php";

//
require_once dirname(__FILE__) . "/feestdag.inc.php";
require_once dirname(__FILE__) . "/misc.inc.php";
require_once dirname(__FILE__) . "/pdo.inc.php";
require_once dirname(__FILE__) . "/settings.inc.php";
require_once dirname(__FILE__) . "/syncinfo.inc.php";
require_once dirname(__FILE__) . "/syncprotimemysql.inc.php";

// connect to database (target databases)
$dbConn = new class_pdo( $databases['staff'] );
$dbTimecard = new class_pdo( $databases['timecard'] );
//
$targetDatabases = array($dbConn, $dbTimecard);

// connect to database (source database)
$dbProtime = new class_pdo( $databases['protime'] );

//
if ( !defined('ENT_XHTML') ) {
	define('ENT_XHTML', 32);
}
