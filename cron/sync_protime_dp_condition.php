<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("DP_CONDITION");
$sync->setTargetDatabases(array($dbTimecard));
$sync->setTargetTable("protime_dp_condition");
$sync->setPrimaryKey("DP_CONDITION");
$sync->addFields( array("DP_CONDITION", "DAYPROG", "EXEC_ORDER", "DOTHIS", "DO_VALUE1", "DO_VALUE2", "DO_VALUE3") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
