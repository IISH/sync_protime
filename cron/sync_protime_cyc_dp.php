<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("CYC_DP");
$sync->setTargetDatabases(array($dbConn, $dbTimecard));
$sync->setTargetTable("protime_cyc_dp");
$sync->setPrimaryKey("CYC_DP");
$sync->addFields( array("CYC_DP", "CYCLIQ", "DAYNR", "DAYPROG") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
