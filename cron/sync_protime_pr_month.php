<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("PR_MONTH");
$sync->setSourceCriterium(" BOOKDATE>='" . date("Ymd", mktime(0, 0, 0, date("m")-3, 1, date("Y"))) . "' ");
$sync->setTargetTable("protime_pr_month");
$sync->setPrimaryKey("PR_KEY");
$sync->addFields( array("PR_KEY", "PERSNR", "BOOKDATE", "CYC_DP", "DAYPROG", "NORM", "WORKED", "PREST", "RPREST", "EXTRA", "WEEKPRES1", "WEEKPRES2", "WEEKPRES3", "PAYPERIO_PRES", "BALANCE", "TERMINAL", "FLAGS1", "FLAGS2", "FLAGS3", "FLAGS4", "FLAGS5", "FLAGS6", "FLAGS7", "ABS_CORE", "NROFBREAKS", "BREAKTIME", "CALCULATED", "ACCESSGROUP", "SHIFT", "CYCLIQ", "COSTCENTERGROUP", "COSTBLOCKING", "PP_FUNCTION", "COMMENTS", "CUSTOMER" ) );
SyncInfo::save($sync->getTargetTable(), 'start', date("Y-m-d H:i:s"));
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// save sync last run
SyncInfo::save($sync->getTargetTable(), 'end', date("Y-m-d H:i:s"));
SyncInfo::save($sync->getTargetTable(), 'last_insert_id', $sync->getLastInsertId());

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
