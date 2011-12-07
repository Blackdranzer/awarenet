<?

	require_once($kapenta->installPath . 'modules/badges/models/badge.mod.php');

//--------------------------------------------------------------------------------------------------
//*	show a Badge 
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	authentication (no public users)
	//----------------------------------------------------------------------------------------------
	if (($user->role == 'public') || ($user->role == 'banned')) { $page->do403(); }

	//----------------------------------------------------------------------------------------------	
	//	check reference
	//----------------------------------------------------------------------------------------------
	if ('' == $req->ref) { $page->do404(); }
	$UID = $aliases->findRedirect('badges_badge');

	//----------------------------------------------------------------------------------------------	
	//	load the model
	//----------------------------------------------------------------------------------------------	
	$editUrl = '';
	$model = new Badges_Badge($UID);
	//if (true == $model->hasEditAuth($user->UID)) 
	//	{ $editUrl = '%%serverPath%%badges/edit/' . $model->alias; }

	if (false == $model->loaded) { $page->do404(); }

	//----------------------------------------------------------------------------------------------	
	//	render the page
	//----------------------------------------------------------------------------------------------	
	$page->load('modules/badges/actions/show.page.php');
	$page->blockArgs['raUID'] = $req->ref;
	$page->blockArgs['UID'] = $model->UID;
	$page->blockArgs['badgeName'] = $model->name;
	$page->blockArgs['editBadgeUrl'] = $editUrl;
	$page->render();

?>