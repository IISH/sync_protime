<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("ABSENCE");
$sync->setTargetDatabases(array($dbConn, $dbTimecard));
$sync->setTargetTable("protime_absence");
$sync->setPrimaryKey("ABSENCE");
$sync->addFields( array("ABSENCE", "SHORT_1", "SHORT_2", "CODE") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
