<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("LNK_CURRIC_PROFILE");
$sync->setTargetDatabases(array($dbConn, $dbTimecard));
$sync->setTargetTable("protime_lnk_curric_profile");
$sync->setPrimaryKey("REC_NR");
$sync->addFields( array("REC_NR", "PERSNR", "PROFILE", "PROFILETYPE", "DATEFROM", "DAYNUMBER", "CUSTOMER") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
