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
$sync->setSourceTable("PR_MONTH");
$sync->setSourceCriterium(" BOOKDATE>='" . $startDate . "' " . $extraCriterium);
$sync->setTargetDatabases(array($dbTimecard));
$sync->setTargetTable("protime_pr_month");
$sync->setPrimaryKey("PR_KEY");
$sync->addFields( array("PR_KEY", "PERSNR", "BOOKDATE", "CYC_DP", "DAYPROG", "NORM", "WORKED", "PREST", "RPREST", "EXTRA", "WEEKPRES1", "WEEKPRES2", "WEEKPRES3", "PAYPERIO_PRES", "BALANCE", "TERMINAL", "FLAGS1", "FLAGS2", "FLAGS3", "FLAGS4", "FLAGS5", "FLAGS6", "FLAGS7", "ABS_CORE", "NROFBREAKS", "BREAKTIME", "CALCULATED", "ACCESSGROUP", "SHIFT", "CYCLIQ", "COSTCENTERGROUP", "COSTBLOCKING", "PP_FUNCTION", "COMMENTS", "CUSTOMER" ) );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
