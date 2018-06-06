<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("BOOKINGS");
$sync->setSourceCriterium(" BOOKDATE >= '" . date("Ymd") . "' ");
$sync->setTargetDatabases(array($dbConn, $dbTimecard));
$sync->setTargetTable("staff_today_checkinout");
$sync->setPrimaryKey("REC_NR");
$sync->addFields( array("REC_NR", "PERSNR", "BOOKDATE", "BOOK_ORIG", "BOOKTIME", "BOOKTYPE", "CCABS", "TERMINAL", "USER_ID", "COMMENTS", "REQUEST", "CALCBOOKTIME") );
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// remove old records
$query = "DELETE FROM " . $sync->getTargetTable() . " WHERE BOOKDATE<'" . date("Ymd") . "' ";
$sync->executeQuery($query, $sync->getTargetDatabases());

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
