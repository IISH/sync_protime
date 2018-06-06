<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("DAYPROG");
$sync->setTargetDatabases(array($dbConn, $dbTimecard));
$sync->setTargetTable("protime_dayprog");
$sync->setPrimaryKey("DAYPROG");
$sync->addFields( array("DAYPROG", "SHORT_1", "SHORT_2", "ITEM_LEVEL", "CODE", "COLOR", "NORM", "F_CORE", "T_CORE", "F_FICTIF", "T_FICTIF", "B_CLOSURE", "E_CLOSURE", "DP_ROUND", "DP_OVERTIME", "DP_SLIDE", "SHIFT", "ABSENCEDAY", "ACCESSDAYTYPE", "CUSTOMER", "DISPLAY_1", "DISPLAY_2", "VALIDFORDAYS", "F_PLANNABLE", "T_PLANNABLE", "USEPLANNEDCORE") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
