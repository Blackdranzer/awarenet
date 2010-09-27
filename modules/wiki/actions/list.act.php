<?

//--------------------------------------------------------------------------------------------------
//	list all pages on the wiki
//--------------------------------------------------------------------------------------------------

	//require_once($kapenta->installPath . 'modules/wiki/models/wiki.mod.php');

	//----------------------------------------------------------------------------------------------
	//	check for arguments
	//----------------------------------------------------------------------------------------------
	$pageno = 1; $num = 30;
	if (array_key_exists('page', $req->args) != false) { $pageno = $req->args['page']; }
	if (array_key_exists('num', $req->args) != false) { $num = $req->args['num']; }

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$page->load('modules/wiki/actions/list.page.php');
	$page->blockArgs['pageno'] = $pageno;
	$page->blockArgs['num'] = $num;
	$page->render();

?>