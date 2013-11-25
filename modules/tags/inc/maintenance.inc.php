<?

	require_once($kapenta->installPath . 'modules/tags/models/index.mod.php');
	require_once($kapenta->installPath . 'modules/tags/models/tag.mod.php');

//--------------------------------------------------------------------------------------------------
//*	maintenance script for Tags module
//--------------------------------------------------------------------------------------------------
//+	reports are human-readable HTML, with script-readable HTML comments

//--------------------------------------------------------------------------------------------------
//|	install the Tags module
//--------------------------------------------------------------------------------------------------
//returns: html report or false if not authorized [string][bool]

function tags_maintenance() {
	global $db, $aliases, $user, $theme;
	if ('admin' != $user->role) { return false; }
	$report = '';

	//----------------------------------------------------------------------------------------------
	//	check all Index objects
	//----------------------------------------------------------------------------------------------
	$recordCount = 0;
	$errorCount = 0;
	$fixCount = 0;

	$errors = array();
	$errors[] = array('UID', 'UID', 'error');

	$model = new Tags_Index();
	$dbSchema = $model->getDbSchema();

	$sql = "select * from tags_index";
	$handle = $db->query($sql);

	while ($objAry = $db->fetchAssoc($handle)) {
		$objAry = $db->rmArray($objAry);		// remove database markup
		$model->loadArray($objAry);		// load into model
		$recordCount++;

		//------------------------------------------------------------------------------------------
		//	check references to other objects
		//------------------------------------------------------------------------------------------
		//TODO: complete this check, make sure tagged objects exist
		/*
		if (false == $db->objectExists('*_*', $model->refUID)) {
			// TODO: take action here, if possibe assign valid reference to a *_*
			$errors[] = array($model->UID, $model->UID, 'invalid reference (refUID:*_*)');
			$errorCount++;
		}
		*/

		if (false == $db->objectExists('tags_tag', $model->tagUID)) {
			// TODO: take action here, if possibe assign valid reference to a Tags_Tag
			$errors[] = array($model->UID, $model->UID, 'invalid reference (tagUID:tags_tag)');
			$errorCount++;
		}

		if (false == $db->objectExists('users_user', $model->createdBy)) {
			// TODO: take action here, if possibe assign valid reference to a Users_User
			$errors[] = array($model->UID, $model->UID, 'invalid reference (createdBy:users_user)');
			$errorCount++;
		}

		if (false == $db->objectExists('users_user', $model->editedBy)) {
			// TODO: take action here, if possibe assign valid reference to a Users_User
			$errors[] = array($model->UID, $model->UID, 'invalid reference (editedBy:users_user)');
			$errorCount++;
		}

		
	} // end while Tags_Index

	//----------------------------------------------------------------------------------------------
	//	add Index objects to report
	//----------------------------------------------------------------------------------------------
	$report .= ''
	 . $theme->arrayToHtmlTable($errors, true, true)
	 . "<b>Checked:</b> $recordCount objects<br/>\n"
	 . "<b>Errors:</b> $errorCount<br/>\n"
	 . "<b>Fixed:</b> $fixCount<br/>\n";

	//----------------------------------------------------------------------------------------------
	//	check all Tag objects
	//----------------------------------------------------------------------------------------------
	$recordCount = 0;
	$errorCount = 0;
	$fixCount = 0;

	$errors = array();
	$errors[] = array('UID', 'name', 'error');

	$model = new Tags_Tag();
	$dbSchema = $model->getDbSchema();

	$sql = "select * from tags_tag";
	$handle = $db->query($sql);

	while ($objAry = $db->fetchAssoc($handle)) {
		$objAry = $db->rmArray($objAry);		// remove database markup
		$model->loadArray($objAry);				// load into model
		$recordCount++;

		//------------------------------------------------------------------------------------------
		//	check references to other objects
		//------------------------------------------------------------------------------------------
		if (false == $db->objectExists('users_user', $model->createdBy)) {
			// TODO: take action here, if possibe assign valid reference to a Users_User
			$errors[] = array($model->UID, $model->name, 'invalid reference (createdBy:users_user)');
			$errorCount++;
		}

		if (false == $db->objectExists('users_user', $model->editedBy)) {
			// TODO: take action here, if possibe assign valid reference to a Users_User
			$errors[] = array($model->UID, $model->name, 'invalid reference (editedBy:users_user)');
			$errorCount++;
		}

		//------------------------------------------------------------------------------------------
		//	update object counts (direct references)
		//------------------------------------------------------------------------------------------
		$updatedCount = $model->updateObjectCount();
		if (true == $updatedCount) {
			$errors[] = array($model->UID, $model->name, 'Corrected object counts.');
			$errorCount++;
			$fixCount++;
		}
		
	} // end while Tags_Tag

	//----------------------------------------------------------------------------------------------
	//	add Tag objects to report
	//----------------------------------------------------------------------------------------------
	$report .= ''
	 . $theme->arrayToHtmlTable($errors, true, true)
	 . "<b>Checked:</b> $recordCount objects<br/>\n"
	 . "<b>Errors:</b> $errorCount<br/>\n"
	 . "<b>Fixed:</b> $fixCount<br/>\n";

	//----------------------------------------------------------------------------------------------
	//	done
	//----------------------------------------------------------------------------------------------
	return $report;
}

?>