<?php 
/**
 * Class for settings syncinfo
 */
class SyncInfo {
	private static $settings_table = 'website_syncinfo';

	public static function save( $setting_name, $type, $value, $targetDatabases ) {
		$settingsTable = self::$settings_table;

		foreach ( $targetDatabases as $db ) {
			if ( $setting_name != '' ) {
				$query = "SELECT * FROM $settingsTable WHERE property='" . $setting_name . "' ";
				$stmt = $db->getConnection()->prepare($query);
				$stmt->execute();
				if ( $row = $stmt->fetch() ) {
					// dirty
					$extra = '';
					if ( $type == 'start' ) {
						$extra = ", end='' ";
					}

					$query = "UPDATE $settingsTable SET $type='" . addslashes($value) . "' $extra WHERE property='" . $setting_name . "' ";
					$stmt = $db->getConnection()->prepare($query);
					$stmt->execute();
				}
				else {
					$query = "INSERT INTO $settingsTable (property, $type) VALUES ( '" . $setting_name . "', '" . addslashes($value) . "' ) ";
					$stmt = $db->getConnection()->prepare($query);
					$stmt->execute();
				}
			}
//echo $query . ' +++<br>';
		}
	}

	public function __toString() {
		return "Class: " . get_class($this) . "\n";
	}
}
