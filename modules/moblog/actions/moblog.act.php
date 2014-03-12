<?

//--------------------------------------------------------------------------------------------------
//*	moblog, all posts of from all blogs ordered by date
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	page variables
	//----------------------------------------------------------------------------------------------
	$pageNo = 1; // if not specified

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	
	if (false == $kapenta->user->authHas('moblog', 'moblog_post', 'show', ''))
		{ $kapenta->page->do403('You are not authorized to view blog posts.'); }

	if (true == array_key_exists('page', $kapenta->request->args)) { $pageNo = floor($kapenta->request->args['page']); }

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/moblog/actions/moblog.page.php');
	$kapenta->page->blockArgs['pageno'] = $pageNo;
	$kapenta->page->render();

?>
