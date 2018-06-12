<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

//
$persnr = getPersnr();

//
if ( $persnr != '' ) {
	$maxMonth = 12;
	$extraCriterium = " AND PERSNR='$persnr' ";
} else {
	$maxMonth = 3;
	$extraCriterium = '';
}

//
$startDate = date("Ymd", mktime(0, 0, 0, date("m")-$maxMonth, 1, date("Y")));

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("P_LIMIT");
$sync->setSourceCriterium(" EXEC_ORDER=2 AND BOOKDATE>='" . $startDate . "' " . $extraCriterium);
$sync->setTargetDatabases(array($dbTimecard));
$sync->setTargetTable("protime_p_limit");
$sync->setPrimaryKey("REC_NR");
$sync->addFields( array("REC_NR", "PERSNR", "BOOKDATE", "LIMIT_LINE", "LIM_PERIODE", "ITEM_TYPE", "YEARCOUNTER", "BEGIN_VAL", "END_VAL", "EXEC_ORDER") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
