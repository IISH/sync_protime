<?php
function getPersnr() {
	$persnr = '';
	if ( isset($_GET["PERSNR"]) ) {
		if (preg_match('/^[0-9]+$/', $_GET["PERSNR"])) {
			$persnr = $_GET["PERSNR"];
		}
	}

	return $persnr;
}

function preprint( $object ) {
	echo '<pre>';
	print_r( $object );
	echo '</pre>';
}

function checkCronKey() {
	$cron_key = '';
	if ( isset($_GET["cron_key"]) ) {
		$cron_key = $_GET["cron_key"];
	} elseif ( isset($_POST["cron_key"]) ) {
		$cron_key = $_POST["cron_key"];
	}
	if ( trim( $cron_key ) != Settings::get('cron_key') ) {
		die('Error: Incorrect cron key');
	}
}
