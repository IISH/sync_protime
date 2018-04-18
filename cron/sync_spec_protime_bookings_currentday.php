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
$sync->setTargetTable("staff_today_checkinout");
$sync->setPrimaryKey("REC_NR");
$sync->addFields( array("REC_NR", "PERSNR", "BOOKDATE", "BOOK_ORIG", "BOOKTIME", "BOOKTYPE", "CCABS", "TERMINAL", "USER_ID", "COMMENTS", "REQUEST", "CALCBOOKTIME") );
SyncInfo::save($sync->getTargetTable(), 'start', date("Y-m-d H:i:s"));
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// remove old records
$query = "DELETE FROM " . $sync->getTargetTable() . " WHERE BOOKDATE<'" . date("Ymd") . "' ";
$stmt = $dbConn->getConnection()->prepare($query);
$stmt->execute();
$stmt2 = $dbProtime->getConnection()->prepare($query);
$stmt2->execute();

// save sync last run
SyncInfo::save($sync->getTargetTable(), 'end', date("Y-m-d H:i:s"));
SyncInfo::save($sync->getTargetTable(), 'last_insert_id', $sync->getLastInsertId());

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";
