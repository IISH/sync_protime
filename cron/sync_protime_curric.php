<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("CURRIC");
$sync->setTargetDatabases(array($dbConn, $dbTimecard));
$sync->setTargetTable("protime_curric");
$sync->setPrimaryKey("PERSNR");
$sync->addFields( array("PERSNR", "NAME", "FIRSTNAME", "EMAIL", "REGISTERNR", "WORKLOCATION", "ADDRESS", "ZIPCODE", "CITY", "COUNTRY", "DATEBIRTH", "DATE_IN", "DATE_OUT", "DEPART", "BADGENR", "SEX", "USER01", "USER02", "USER03", "USER04", "USER05", "USER06", "USER07", "USER08", "USER09", "USER10", "USER11", "USER12", "USER13", "USER14", "USER15", "USER16", "USER17", "USER18", "USER19", "USER20", "PHOTO") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
