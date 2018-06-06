<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("DEPART");
$sync->setTargetDatabases(array($dbConn, $dbTimecard));
$sync->setTargetTable("protime_depart");
$sync->setPrimaryKey("DEPART");
$sync->addFields( array("DEPART", "SHORT_1", "SHORT_2", "CODE_EXTERN", "CUSTOMER") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
