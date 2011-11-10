<?

	require_once($kapenta->installPath . 'modules/projects/models/project.mod.php');

//--------------------------------------------------------------------------------------------------
//*	iframe to display and add/remove project members, reference should be a projects' alias
//--------------------------------------------------------------------------------------------------
//DEPRECATED: Remove this

	//----------------------------------------------------------------------------------------------
	//	check reference and load project
	//----------------------------------------------------------------------------------------------
	if ('' == $req->ref) { $page->do404(); }
	//$UID = $aliases->findRedirect('projects_project');

	$model = new Projects_Project($req->ref);
	if (false == $model->loaded) { $page->do404('Unkonwn project.', true); }
	$members = $model->getMembers();

	//----------------------------------------------------------------------------------------------
	//	determine if current user is authorised to administer this project
	//----------------------------------------------------------------------------------------------	
	$admin = false;
	if ('admin' == $user->role) { $admin = true; }

	foreach($members as $userUID => $urole) 
		{ if (($userUID == $user->UID) AND ($urole == 'admin')) { $admin = true; } }

	//if ($admin == false) 
	//	{ $page->do403("You cannot administer memberships of this project.<br/>\n", true); }

	//----------------------------------------------------------------------------------------------
	//	accept HTTP POSTs to add new members
	//----------------------------------------------------------------------------------------------
	if ((true == array_key_exists('action', $_POST)) AND ('addMember' == $_POST['action'])) {
		$model->addMember($_POST['user'], $_POST['role']);
		$session->msg("Added new member to project: " . $model->title . ".", 'ok');
	}

	//----------------------------------------------------------------------------------------------
	//	accept HTTP GET to remove members (user UID is given)
	//----------------------------------------------------------------------------------------------
	// eg /projects/editmembers/removemember_8237146489/Some-Project

	if (true == array_key_exists('removemember', $req->args)) {
		//AND (true == $db->objectExists('users_user', $req->args['removemember'])) ) {
		$model->removeMember($req->args['removemember']);
		$session->msg("Removed member from " . $model->title . ".", 'ok');
	}
	
	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$page->load('modules/projects/actions/editmembers.if.page.php');
	$page->blockArgs['UID'] = $model->UID;
	$page->blockArgs['raUID'] = $model->alias;
	$page->render();

?>
