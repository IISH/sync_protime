<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("CYCLIQ");
$sync->setTargetDatabases(array($dbTimecard));
$sync->setTargetTable("protime_cycliq");
$sync->setPrimaryKey("CYCLIQ");
$sync->addFields( array("CYCLIQ", "SHORT_1", "SHORT_2", "ITEM_LEVEL", "NROFDAYS", "WEEKNORM", "DAYNORM", "CUSTOMER") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
