<?php
die('disabled by GCU');

// TODO: moet nog aangepast worden

require_once "../classes/start.inc.php";

// connection to the database
$dbhandlePT = mssql_connect($databases['protime_live']['host'], $databases['protime_live']['username'], $databases['protime_live']['password']) or die("Couldn't connect to SQL Server on: " . $databases['protime_live']['host']);

// select a database to work with
$selectedPT = mssql_select_db($databases['protime_live']['database'], $dbhandlePT) or die("Couldn't open database " . $databases['protime_live']['database']);

$oWebuser->checkLoggedIn();

$arr = array( 37 ); // TODO: GCU

foreach ( $arr as $pid) {

	$user = advancedSingleRecordSelectMssql(
				$dbhandlePT
				, "CURRIC"
				, array("PERSNR", "NAME", "FIRSTNAME", "ADDRESS", "ZIPCODE", "CITY", "COUNTRY", "BIRTHPLACE", "DATEBIRTH", "DATE_IN", "DATE_OUT", "DEPART", "BADGENR", "BADGE_VERSION", "REGISTERNR", "EMPLOYEENR", "PHONENR", "ID_CARDNR", "LIMITGROUP", "RREGISTER", "LEGALSTATE", "CHILDSUPNR", "HOUR_WAGE", "MAXADVANCE", "NATIONALTY", "SEX", "SECTOR", "TERMDISP", "FROMDATE1", "CYC_ORG1", "DAYNUMBER1", "FROMDATE2", "CYC_ORG2", "DAYNUMBER2", "PIP", "EMPLOYER", "ADD_INFO", "FUNCTION_CAT", "WTD_DATE", "PROPLAN_FUNCTION", "COSTCENTERGROUP1", "PAYPERIO", "USER01", "USER02", "USER03", "USER04", "USER05", "USER06", "USER07", "USER08", "USER09", "USER10", "USER11", "USER12", "USER13", "USER14", "USER15", "USER16", "USER17", "USER18", "USER19", "USER20", "CTRLGROUP", "YEAR_START", "PHOTO", "CALENDAR", "LANGUAGENR", "EMAIL", "CY_HOURWAGE", "CY_ADVANCE", "BANK_PREFIX", "BANKACCOUNT", "ACCGROUP1", "ACCDATE1", "ACCDAYNR1", "ACCGROUP2", "ACCDATE2", "ACCDAYNR2", "DOMAINUSER", "PINCODE", "COSTCENTERGROUP2", "CCG_DATE1", "NAME_INITIAL", "WORKLOCATION", "PLANNINGPOOL", "LASTMOD")
				, "PERSNR IN ( " . $pid . " ) "
			);

	echo "<pre>";
	print_r($user);
	echo "</pre>";
	echo "<br><br>";

	echo "Protime ID: " . $user["persnr"] ."<br>";
	echo "Naam: " . $user["firstname"] . ' ' . $user["name"] ."<br>";
	echo "KNAW werknemers nummer: " . $user["registernr"] ."<br>";

	echo "<hr>";
}

function advancedSingleRecordSelectMssql($handle, $table, $fields, $criterium, $fieldselect = '', $order_by = '' ) {
	$retval = array();

	if ( $fieldselect == '' || $fieldselect == '*' ) {
		$fieldselect = implode(', ', $fields);
	}

	$advSelect = "SELECT " . $fieldselect . " FROM " . $table;
	$retval["__is_record_found"] = '0';

	if ( $criterium != '' ) {
		$advSelect .= " WHERE " . $criterium . " ";
	}

	if ( $order_by != '' ) {
		$advSelect .= " ORDER BY " . $order_by . " ";
	}

	$resultAdvSelect = mssql_query($advSelect, $handle);
	if ($rowSelect = mssql_fetch_assoc($resultAdvSelect)) {
		$retval["__is_record_found"] = '1';
		if ( is_array($fields) ) {
			foreach ($fields as $a) {
				$retval[strtolower($a)] = $rowSelect[$a];
			}
		} else {
			$retval[strtolower($fields)] = $rowSelect[$fields];
		}
	}

	return $retval;
}
