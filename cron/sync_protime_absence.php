<?php
require_once "../classes/start.inc.php";

// check cron key
checkCronKey();

// show time
echo "Start time: " . date("Y-m-d H:i:s") . "<br>\n";

// sync
$sync = new SyncProtime2Pdo();
$sync->setSourceTable("ABSENCE");
$sync->setTargetTable("protime_absence");
$sync->setPrimaryKey("ABSENCE");
$sync->addFields( array("ABSENCE", "SHORT_1", "SHORT_2", "CODE") );
SyncInfo::save($sync->getTargetTable(), 'start', date("Y-m-d H:i:s"));
$sync->doSync();

//
echo "<br>Rows inserted/updated: " . $sync->getCounter() . "<br>";

// save sync last run
SyncInfo::save($sync->getTargetTable(), 'end', date("Y-m-d H:i:s"));
SyncInfo::save($sync->getTargetTable(), 'last_insert_id', $sync->getLastInsertId());

// show time
echo "End time: " . date("Y-m-d H:i:s") . "<br>\n";