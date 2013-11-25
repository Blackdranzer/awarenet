<?

//--------------------------------------------------------------------------------------------------
//*	display the current user's inbox
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	public user cannot check mail
	//----------------------------------------------------------------------------------------------
	if ('public' == $user->role) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	check for any arguments
	//----------------------------------------------------------------------------------------------

	$pageNo = 1;
	if (true == array_key_exists('page', $kapenta->request->args)) { $pageNo = $kapenta->request->args['page']; }

	$orderBy = 'createdOn';
	if (true == array_key_exists('orderBy', $kapenta->request->args)) {
		$orderBy = $kapenta->request->args['orderBy'];
		switch ($orderBy) {
			case 'createdOn': 	$orderBy = 'createdOn';			break;
			case 'title': 		$orderBy = 'title';				break;
			case 'fromName': 	$orderBy = 'fromName';			break;
			case 'status': 		$orderBy = 'status';			break;
			default: 			$orderBy = 'createdOn';			break;
		}
	}


	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/messages/actions/inbox.page.php');
	$kapenta->page->blockArgs['raUID'] = $user->alias;
	$kapenta->page->blockArgs['UID'] = $user->UID;
	$kapenta->page->blockArgs['orderBy'] = $orderBy;
	$kapenta->page->blockArgs['pageno'] = $pageNo;
	$kapenta->page->blockArgs['userRa'] = $user->alias;
	$kapenta->page->blockArgs['userName'] = $user->getName();
	$kapenta->page->blockArgs['owner'] = $user->UID;
	$kapenta->page->render();	

?>
