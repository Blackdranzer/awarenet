<?php

//--------------------------------------------------------------------------------------------------
//*	awarenet monthly activity report from twitter
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	$year = date('Y', $kapenta->time());
	$month = date('m', $kapenta->time());

	if (true == array_key_exists('year', $kapenta->request->args)) { $year = (int)$kapenta->request->args['year']; }
	if (true == array_key_exists('month', $kapenta->request->args)) { $month = (int)$kapenta->request->args['month']; }

	//----------------------------------------------------------------------------------------------
	//	make the report
	//----------------------------------------------------------------------------------------------

	$sql = ''
	 . "select * from twitter_tweet"
	 . " where content like '%$year-" . substr('00' . (string)$month, -2) . "%'";

	$result = $db->query($sql);

	header('Content-type: text/plain');
	while ($row = $db->fetchAssoc($result)) { echo $db->removeMarkup($row['content']) . "\n"; }

?>
