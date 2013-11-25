<?

//--------------------------------------------------------------------------------------------------
//*	summarize daily blog activity for twitter microreport
//--------------------------------------------------------------------------------------------------
//opt: date - date to be shown, default is today, YYYY-MM-DD [string]

function moblog_twitterdaily($args) {
	global $db;
	global $kapenta;

	$date = substr($kapenta->datetime(), 0, 10);
	$txt = '';

	//----------------------------------------------------------------------------------------------
	//	check arguments
	//----------------------------------------------------------------------------------------------

	if (true == array_key_exists('date', $args)) { $date = $args['date']; }

	//----------------------------------------------------------------------------------------------
	//	count new objects
	//----------------------------------------------------------------------------------------------
	$conditions = array("DATE(createdOn) = '" . $db->addMarkup($date) . "'");
	$newPosts = $db->countRange('moblog_post', $conditions);

	//----------------------------------------------------------------------------------------------
	//	make the snippet
	//----------------------------------------------------------------------------------------------
	if ($newPosts > 0) { $txt .= " Blog posts: " . $newPosts; }

	return $txt;
}

?>
