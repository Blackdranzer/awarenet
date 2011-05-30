<?

	require_once($kapenta->installPath . 'modules/projects/models/project.mod.php');

//--------------------------------------------------------------------------------------------------
//*	change a project's title
//--------------------------------------------------------------------------------------------------
//postarg: action - must be 'saveChangeTitle' [string]
//postarg: UID - UID of a Projects_Project object [string]
//postarg: title - new title of project [string]

	//----------------------------------------------------------------------------------------------
	//	check permissions and POST vars
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('action', $_POST)) { $page->do404('action not specified'); }
	if ('saveChangeTitle' != $_POST['action']) { $page->do404('action not supported'); }
	if (false == array_key_exists('UID', $_POST)) { $page->do404('UID not specified'); }

	$model = new Projects_Project($_POST['UID']);
	if (false == $model->loaded) { $page->do404('Project not found.'); }

	if (false == $user->authHas('projects', 'projects_project', 'edit', $model->UID)) {
		$page->do403('You are not authorized to edit this project.'); 
	}

	if (false == array_key_exists('title', $_POST)) { $page->do404('title not given'); }

	//----------------------------------------------------------------------------------------------
	//	note the revision
	//----------------------------------------------------------------------------------------------
	if ($model->title != $_POST['title']) { 
		$report = $model->saveRevision();
		if ('' == $report) { $session->msg('Saved revision.', 'ok'); }
		else { $session->msg('Revision not saved.', 'bad'); }
	}

	//----------------------------------------------------------------------------------------------
	//	save the record
	//----------------------------------------------------------------------------------------------
	$model->title = $utils->cleanString($_POST['title']);		//TODO: better sanitization
	$report = $model->save();
	if ('' == $report) { 
		//------------------------------------------------------------------------------------------
		//	raise 'project_saved' event
		//------------------------------------------------------------------------------------------
		$args = array(
			'UID' => $model->UID,
			'user' => $user->UID,
			'section' => 'title'
		);

		$kapenta->raiseEvent('projects', 'project_saved', $args);
		$session->msg('Changed project title to: ' . $model->title, 'ok'); 
	} else {
		$session->msg('Could not change title.', 'bad');
	}		
		
	$page->do302('projects/edit/' . $model->alias);

?>