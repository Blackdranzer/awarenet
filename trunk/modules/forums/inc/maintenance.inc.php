<?

	require_once($kapenta->installPath . 'modules/forums/models/board.mod.php');
	require_once($kapenta->installPath . 'modules/forums/models/thread.mod.php');

//--------------------------------------------------------------------------------------------------
//	maintain the forums module
//--------------------------------------------------------------------------------------------------

function forums_maintenance() {
	global $db, $user, $theme, $aliases;
	$report = '';	//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check boards
	//----------------------------------------------------------------------------------------------
	$recordCount = 0;
	$errorCount = 0;
	$fixCount = 0;

	$report .= "<h2>Checking boards ...</h2>";

	$errors = array();
	$errors[] = array('UID', 'Title', 'error');

	$sql = "SELECT * from Forums_Board";
	$result = $db->query($sql);

	while ($row = $db->fetchAssoc($result)) {
		$row = $db->rmArray($row);
		$raAll = $aliases->getAll('forums', 'Forums_Board', $row['UID']);

		//------------------------------------------------------------------------------------------
		//	check alias
		//------------------------------------------------------------------------------------------
		if (false == $raAll) {
			//--------------------------------------------------------------------------------------
			//	board has no alias, create one
			//--------------------------------------------------------------------------------------
			$model = new Forums_Board($row['UID']);
			$model->save();
	
			$error = array($row['UID'], $row['title'], $model->title . " (set alias)");
			$errors[] = $error;

			$fixCount++;
			$errorCount++;
		}

		//------------------------------------------------------------------------------------------
		//	check weight
		//------------------------------------------------------------------------------------------
		if ('' == $row['weight']) {
			$model = new Forums_Board($row['UID']);
			$model->weight = 0;
			$model->save();

			$error = array($row['UID'], $row['title'], "set weight to 0 (" . $model->alias . ")");
			$errors[] = $error;

			$fixCount++;
			$errorCount++;
		}

		$recordCount++;
	}

	//----------------------------------------------------------------------------------------------
	//	compile report
	//----------------------------------------------------------------------------------------------

	if (count($errors) > 1) { $report .= $theme->arrayToHtmlTable($errors, true, true); }

	$report .= "<b>Records Checked:</b> $recordCount<br/>\n";
	$report .= "<b>Errors Found:</b> $errorCount<br/>\n";
	if ($errorCount > 0) {
		$report .= "<b>Errors Fixed:</b> $fixCount<br/>\n";
	}


	//----------------------------------------------------------------------------------------------
	//	check threads
	//----------------------------------------------------------------------------------------------

	$recordCount = 0;
	$errorCount = 0;
	$fixCount = 0;

	$report .= "<h2>Checking threads...</h2>";

	$errors = array();
	$errors[] = array('UID', 'Title', 'error');

	$sql = "SELECT * from Forums_Thread";
	$result = $db->query($sql);

	while ($row = $db->fetchAssoc($result)) {
		$row = $db->rmArray($row);
		$raAll = $aliases->getAll('forums', 'Forums_Thread', $row['UID']);

		//------------------------------------------------------------------------------------------
		//	check thread aliases
		//------------------------------------------------------------------------------------------
		if (false == $raAll) {
				//---------------------------------------------------------------------------------
				//	no recordAlias for this blog post, create one
				//---------------------------------------------------------------------------------
				$model = new Forums_Thread($row['UID']);
				$model->save();
	
				$error = array($row['UID'], $row['title'], $model->alias);
				$errors[] = $error;

				$fixCount++;
				$errorCount++;
		}

		//------------------------------------------------------------------------------------------
		//	check reply counts
		//------------------------------------------------------------------------------------------
		$conditions = array("thread='" . $db->addMarkup($row['UID']) . "'");
		$numReplies = $db->countRange('Forums_Reply', $conditions);

		if ($numReplies != (int)$row['replies']) { 
				$model = new Forums_Thread($row['UID']);
				$model->replies = $numReplies;
				$model->save();
	
				$error = array($row['UID'], $row['title'], 'Set reply count to ' . $numReplies);
				$errors[] = $error;

				$fixCount++;
				$errorCount++;
		}

		$recordCount++;
	}

	//---------------------------------------------------------------------------------------------
	//	compile report
	//---------------------------------------------------------------------------------------------

	if (count($errors) > 1) { $report .= $theme->arrayToHtmlTable($errors, true, true); }

	$report .= "<b>Records Checked:</b> $recordCount<br/>\n";
	$report .= "<b>Errors Found:</b> $errorCount<br/>\n";
	if ($errorCount > 0) {
		$report .= "<b>Errors Fixed:</b> $fixCount<br/>\n";
	}

	return $report;
}

?>