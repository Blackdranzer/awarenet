<?

	require_once($kapenta->installPath . 'modules/projects/models/project.mod.php');
	require_once($kapenta->installPath . 'modules/projects/models/membership.mod.php');

//--------------------------------------------------------------------------------------------------
//*	current user is requesting to join a project
//--------------------------------------------------------------------------------------------------
	
	if ('public' == $user->role) { $page->do403(); }	// public and banned users can't do this	

	if (false == array_key_exists('action', $_POST)) { $page->do404('Action not given.'); }
	if ('askToJoin' != $_POST['action']) { $page->do404('Action not supported.'); }
	if (false == array_key_exists('UID', $_POST)) { $page->do404('Project not specified (UID).'); }

	$project = new Projects_Project($_POST['UID']);	
	if (false == $project->loaded) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	check for an existing request
	//----------------------------------------------------------------------------------------------
	if (true == $project->memberships->hasAsked($user->UID)) {
		$session->msg('You have already asked to join this project.', 'bad');
		$page->do302('projects/' . $project->alias); 
	}

	//----------------------------------------------------------------------------------------------
	//	no existing request, make one 
	//----------------------------------------------------------------------------------------------
	$check = $project->memberships->add($user->UID, 'asked');
	if (true == $check) {
		$msg = "You have requested to join this project. "
			 . "Please be patient in waiting for a response from the people "
			 . "in charge of this project.\n";
		$session->msg($msg, 'ok');

	} else { $session->msg('Error: request could not be processed.', 'warn'); }
					
	//----------------------------------------------------------------------------------------------
	//	notify admins of request, cc to user making it  //TODO: handle with event
	//----------------------------------------------------------------------------------------------
	$ext = $project->extArray();

	$title = $user->getName() . ' would like to join project: ' . $project->title;	
	$content = 'Request via form on the project page, no message attached.';
	$url = '%%serverPath%%projects/' . $project->alias;

	if (true == array_key_exists('message', $_POST)) { 
		$content = "message attached: " . stripslashes($utils->cleanHtml($_POST['message']));
	}

	$nUID = $notifications->create(
		'projects', 'projects_project', $project->UID, 'projects_newmemberadded', 
		$title, $content, $url
	);

	$members = $project->memberships->getMembers();
	foreach($members as $userUID => $role) {
		if ('admin' == $role) { $notifications->addUser($nUID, $userUID); }
	}

	$notifications->addUser($nUID, $user->UID);

	//----------------------------------------------------------------------------------------------
	//	return to project page
	//----------------------------------------------------------------------------------------------
	$page->do302('projects/' . $project->alias);

?>