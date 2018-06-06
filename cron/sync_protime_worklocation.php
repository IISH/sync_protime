<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("WORKLOCATION");
$sync->setTargetDatabases(array($dbTimecard));
$sync->setTargetTable("protime_worklocation");
$sync->setPrimaryKey("LOCATIONID");
$sync->addFields( array("LOCATIONID", "SHORT_1", "SHORT_2", "DESCRIPTION") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
