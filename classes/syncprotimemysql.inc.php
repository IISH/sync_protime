<?php
require_once dirname(__FILE__) . "/../sites/default/settings.php";
require_once "pdo.inc.php";

class SyncProtime2Pdo
{
	protected $settings = null;
	protected $databases;
	protected $sourceTable = '';
	protected $targetTable = '';
	protected $primaryKeyField = '';
	protected $fields = array();
	protected $lastInsertId;
	protected $sourceCriterium = '';
	protected $counter = 0;
	protected $targetDatabases = array();

	public function __construct()
	{
		global $databases;
		$this->databases = $databases;
	}

	public function setSourceTable($sourceTable)
	{
		$this->sourceTable = $sourceTable;
	}

	public function getSourceTable()
	{
		return $this->sourceTable;
	}

	public function setTargetDatabases($targetDatabases)
	{
		if ( !is_array($targetDatabases) ) {
			$targetDatabases = array($targetDatabases);
		}
		$this->targetDatabases = $targetDatabases;
	}

	public function setTargetTable($targetTable)
	{
		$this->targetTable = $targetTable;
	}

	public function getTargetDatabases()
	{
		return $this->targetDatabases;
	}

	public function getTargetTable()
	{
		return $this->targetTable;
	}

	public function setPrimaryKey($primaryKeyField)
	{
		$this->primaryKeyField = $primaryKeyField;
	}

	public function getPrimaryKey()
	{
		return $this->primaryKeyField;
	}

	public function addField($field)
	{
		$this->fields[] = $field;
	}

	public function addFields($fields)
	{
		foreach ($fields as $field) {
			$this->fields[] = $field;
		}
	}

	public function getLastInsertId()
	{
		return $this->lastInsertId;
	}

	public function setSourceCriterium($sourceCriterium) {
		$this->sourceCriterium = $sourceCriterium;
	}

	public function getSourceCriterium() {
		return $this->sourceCriterium;
	}

	public function getCounter() {
		return $this->counter;
	}

	public function doSync() {
		global $dbProtime, $dbConn, $dbTimecard;

		echo "Sync " . $this->sourceTable . " (KNAW) -> " . $this->targetTable . " (IISG)<br>";
		SyncInfo::save($this->getTargetTable(), 'start', date("Y-m-d H:i:s"), array($dbConn, $dbTimecard));

		// set records as being updated
		if ($this->sourceCriterium != '') {
			// subset of records
			$query = "UPDATE " . $this->targetTable . " SET sync_state=2 WHERE " . $this->sourceCriterium;
		} else {
			// all records
			$query = "UPDATE " . $this->targetTable . " SET sync_state=2 ";
		}
       	$this->executeQuery($query, $this->getTargetDatabases());

		// save counter in table
		SyncInfo::save($this->getTargetTable(), 'counter', '-', array($dbConn, $dbTimecard));

		//
		$query = "SELECT * FROM " . $this->sourceTable;
		if ($this->sourceCriterium != '') {
			$query .= ' WHERE ' . $this->sourceCriterium . ' ';
		}
		$query .= " ORDER BY " . $this->getPrimaryKey();

        //
		$stmt = $dbProtime->getConnection()->prepare($query);
		$stmt->execute();
		// loop every record from Protime
		while ( $rowData = $stmt->fetch() ) {
			$this->insertUpdateMysqlRecord($rowData, $this->getTargetDatabases());
		}

		// save counter in table
		SyncInfo::save($this->getTargetTable(), 'counter', $this->counter, $this->getTargetDatabases());

		// remove deleted records
		$query = "DELETE FROM " . $this->targetTable . " WHERE sync_state=2 ";
       	$this->executeQuery($query, $this->getTargetDatabases());

		// save sync last run
		SyncInfo::save($this->getTargetTable(), 'end', date("Y-m-d H:i:s"), $this->getTargetDatabases());
		SyncInfo::save($this->getTargetTable(), 'last_insert_id', $this->getLastInsertId(), $this->getTargetDatabases());
	}

	public function __toString() {
		return "Class: " . get_class($this) . "\nsource: " . $this->sourceTable . "\ntarget: " . $this->targetTable . "\n";
	}

	public function executeQuery($query, $targetDatabases) {
		foreach ($targetDatabases as $db) {
			$stmt = $db->getConnection()->prepare($query);
			$stmt->execute();
        }
    }

	protected function insertUpdateMysqlRecord($protimeRowData, $targetDatabases) {
		$this->lastInsertId = $protimeRowData[$this->getPrimaryKey()];
		$this->counter++;

		foreach ($targetDatabases as $db) {
			// create insert/update query
			$queryUpdate = '';
			$separatorUpdate = '';
			$separator = '';
			$fields = '';
			$values = '';
			foreach ($this->fields as $field) {
				$fields .= $separator . $field;
				$values .= $separator . "'" . addslashes($protimeRowData[$field]) . "'";
				if ($this->getPrimaryKey() != $field) {
					$queryUpdate .= $separatorUpdate . $field . "='" . addslashes($protimeRowData[$field]) . "' ";
					$separatorUpdate = ', ';
				}
				$separator = ', ';
			}

			$fields .= $separator . "last_refresh";
			$fields .= $separator . "sync_state";
			$values .= $separator . "'" . date("Y-m-d H:i:s") . "'";
			$values .= $separator . "1";
			$queryUpdate .= ", last_refresh='" . date("Y-m-d H:i:s") . "', sync_state=1 ";

			$query = "INSERT INTO " . $this->getTargetTable() . " ( $fields ) VALUES ( $values ) ON DUPLICATE KEY UPDATE $queryUpdate ; ";
//echo "<br>" .  $query . " +++<br>\n";


//			$query = "SELECT * FROM " . $this->getTargetTable() . " WHERE " . $this->getPrimaryKey() . "='" . $protimeRowData[$this->getPrimaryKey()] . "' ";
//			$stmt = $db->getConnection()->prepare($query);
//			$stmt->execute();
//			if ($row = $stmt->fetch()) {
//				// create update query
//				$separator = '';
//				$query = "UPDATE " . $this->getTargetTable() . " SET ";
//				foreach ($this->fields as $field) {
//					$query .= $separator . $field . "='" . addslashes($protimeRowData[$field]) . "' ";
//					$separator = ', ';
//				}
//
//				$query .= $separator . " last_refresh='" . date("Y-m-d H:i:s") . "'";
//				$query .= $separator . " sync_state=1";
//
//				$query .= " WHERE " . $this->getPrimaryKey() . "='" . $protimeRowData[$this->getPrimaryKey()] . "' ";
//			} else {
//				// create insert query
//				$separator = '';
//				$fields = '';
//				$values = '';
//				foreach ($this->fields as $field) {
//					$fields .= $separator . $field;
//					$values .= $separator . "'" . addslashes($protimeRowData[$field]) . "'";
//					$separator = ', ';
//				}
//
//				$fields .= $separator . "last_refresh";
//				$fields .= $separator . "sync_state";
//				$values .= $separator . "'" . date("Y-m-d H:i:s") . "'";
//				$values .= $separator . "1";
//
//				$query = "INSERT INTO " . $this->getTargetTable() . " ( $fields ) VALUES ( $values ) ";
//			}



			// execute query
			$stmt = $db->getConnection()->prepare($query);
			$stmt->execute();
		}

		if ($this->counter % 10 === 0) {
			if ($this->counter % 200 === 0) {
				echo $this->counter . ' ';

				// save counter in table
				SyncInfo::save($this->getTargetTable(), 'counter', $this->counter, $targetDatabases);
			} else {
				echo '. ';
			}
			flush();
		}
	}
}
