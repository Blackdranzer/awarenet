<?

//--------------------------------------------------------------------------------------------------
//	install script for announcements modules
//--------------------------------------------------------------------------------------------------

require_once($installPath . 'modules/announcements/models/announcement.mod.php');

//--------------------------------------------------------------------------------------------------
//	install the gallery module
//--------------------------------------------------------------------------------------------------
//returns: html report [string]

function announcements_install_module() {
	global $user;

	if ($user->data['ofGroup'] != 'admin') { return false; }	// only admins can do this

	$report .= "<h3>Installing Announcements Module</h3>\n";

	//------------------------------------------------------------------------------------------
	//	create announcement table if it does not exist, upgrade it if it does
	//------------------------------------------------------------------------------------------
	$model = new Announcement();
	$dbSchema = $model->initDbSchema();
	$report .= dbInstallTable($dbSchema);	

	//------------------------------------------------------------------------------------------
	//	done
	//------------------------------------------------------------------------------------------
	return $report;
}

//--------------------------------------------------------------------------------------------------
//	discover if this module is installed
//--------------------------------------------------------------------------------------------------
//returns: HTML installation report [string]
// if installed correctly report will contain HTML comment <!-- installed correctly -->

function announcements_install_status_report() {
	global $user;
	if ($user->data['ofGroup'] != 'admin') { return false; }	// only admins can do this

	//---------------------------------------------------------------------------------------------
	//	ensure that the announcement table exists and is correct
	//---------------------------------------------------------------------------------------------
	$installed = true;
	$model = new Announcement();
	$dbSchema = $model->initDbSchema();

	if (dbTableExists($dbSchema['table']) == true) {
		//-----------------------------------------------------------------------------------------
		//	table present
		//-----------------------------------------------------------------------------------------
		$extantSchema = dbGetSchema($dbSchema['table']);

		if (dbCompareSchema($dbSchema, $extantSchema) == false) {
			//-------------------------------------------------------------------------------------
			// table schemas DO NOT match (fail)
			//-------------------------------------------------------------------------------------
			$installed = false;		
			$report .= "<p>A '" . $dbSchema['table'] . "' table exists, but does not match "
					 . "object's schema.</p>\n"
					 . "<b>Object Schema:</b><br/>\n" . dbSchemaToHtml($dbSchema) . "<br/>\n"
					 . "<b>Extant Table:</b><br/>\n" . dbSchemaToHtml($extantSchema) . "<br/>\n";

		} else {
			//-------------------------------------------------------------------------------------
			// table schemas match
			//-------------------------------------------------------------------------------------
			$report .= "<p>'" . $dbSchema['table'] . "' table exists, matches object schema.</p>\n"
					 . "<b>Database Table:</b><br/>\n" . dbSchemaToHtml($dbSchema) . "<br/>\n";

		}

	} else {
		//-----------------------------------------------------------------------------------------
		//	table missing (fail)
		//-----------------------------------------------------------------------------------------
		$installed = false;
		$report .= "<p>'" . $dbSchema['table'] . "' table does not exist in the database.</p>\n"
				 . "<b>Object Schema:</b><br/>\n" . dbSchemaToHtml($dbSchema) . "<br/>\n";
	}

	if (true == $installed) { $report .= "<!-- installed correctly -->"; }

	return $report;
}

//-------------------------------------------------------------------------------------------------
//	deprecated	// TODO: remove
//-------------------------------------------------------------------------------------------------

function install_announcements_module() {
	global $installPath;
	global $user;
	if ($user->data['ofGroup'] != 'admin') { return false; }

	$model = new Announcement();

	$report = '';
	$report .= $model->install();

	return $report;
}

?>
