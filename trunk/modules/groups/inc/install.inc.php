<?

	require_once($kapenta->installPath . 'core/dbdriver/mysqladmin.dbd.php');
	require_once($kapenta->installPath . 'modules/groups/models/group.mod.php');
	require_once($kapenta->installPath . 'modules/groups/models/membership.mod.php');

//--------------------------------------------------------------------------------------------------
//*	install script for Groups module
//--------------------------------------------------------------------------------------------------
//+	reports are human-readable HTML, with script-readable HTML comments

//--------------------------------------------------------------------------------------------------
//|	install the Groups module
//--------------------------------------------------------------------------------------------------
//returns: html report or false if not authorized [string][bool]

function groups_install_module() {
	global $db, $user;
	if ('admin' != $user->role) { return false; }
	$dba = new KDBAdminDriver();
	$report = '';

	//----------------------------------------------------------------------------------------------
	//	create or upgrade Groups_Group table
	//----------------------------------------------------------------------------------------------
	$model = new Groups_Group();
	$dbSchema = $model->getDbSchema();
	$report .= $dba->installTable($dbSchema);

	//----------------------------------------------------------------------------------------------
	//	copy all records from previous table
	//----------------------------------------------------------------------------------------------
	$rename = array('recordAlias' => 'alias');
	$count = $dba->copyAll('groups', $dbSchema, $rename); 
	$report .= "<b>moved $count records from 'groups' table.</b><br/>";

	//----------------------------------------------------------------------------------------------
	//	create or upgrade Groups_Membership table
	//----------------------------------------------------------------------------------------------
	$model = new Groups_Membership();
	$dbSchema = $model->getDbSchema();
	$report .= $dba->installTable($dbSchema);

	//----------------------------------------------------------------------------------------------
	//	copy all records from previous table
	//----------------------------------------------------------------------------------------------
	$rename = array();
	$count = $dba->copyAll('groupmembers', $dbSchema, $rename); 
	$report .= "<b>moved $count records from 'groupmembers' table.</b><br/>";

	//----------------------------------------------------------------------------------------------
	//	done
	//----------------------------------------------------------------------------------------------
	return $report;
}

//--------------------------------------------------------------------------------------------------
//|	discover if this module is installed
//--------------------------------------------------------------------------------------------------
//:	if installed correctly report will contain HTML comment <!-- installed correctly -->
//returns: HTML installation status report [string]

function groups_install_status_report() {
	global $user;
	if ('admin' != $user->role) { return false; }

	$dba = new KDBAdminDriver();
	$report = '';
	$installNotice = '<!-- table installed correctly -->';
	$installed = true;

	//----------------------------------------------------------------------------------------------
	//	ensure the table which stores Group objects exists and is correct
	//----------------------------------------------------------------------------------------------
	$model = new Groups_Group();
	$dbSchema = $model->getDbSchema();
	$treport = $dba->getTableInstallStatus($dbSchema);

	if (false == strpos($treport, $installNotice)) { $installed = false; }
	$report .= $treport;

	//----------------------------------------------------------------------------------------------
	//	ensure the table which stores Membership objects exists and is correct
	//----------------------------------------------------------------------------------------------
	$model = new Groups_Membership();
	$dbSchema = $model->getDbSchema();
	$treport = $dba->getTableInstallStatus($dbSchema);

	if (false == strpos($treport, $installNotice)) { $installed = false; }
	$report .= $treport;

	//----------------------------------------------------------------------------------------------
	//	done
	//----------------------------------------------------------------------------------------------
	if (true == $installed) { $report .= '<!-- module installed correctly -->'; }
	return $report;
}

?>