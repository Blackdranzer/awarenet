<?

//-------------------------------------------------------------------------------------------------
//	shows the current download queue of this peer
//-------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//	only admins can do this
	//---------------------------------------------------------------------------------------------
	if ($user->data['ofGroup'] != 'admin') { do403(); }

	//---------------------------------------------------------------------------------------------
	//	load and render the page
	//---------------------------------------------------------------------------------------------
	$page->load($installPath . 'modules/sync/actions/downloads.page.php');
	$page->render();

?>
