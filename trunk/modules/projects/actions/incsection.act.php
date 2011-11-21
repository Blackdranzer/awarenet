<?

	require_once($kapenta->installPath . 'modules/projects/models/project.mod.php');
	require_once($kapenta->installPath . 'modules/projects/models/section.mod.php');
	require_once($kapenta->installPath . 'modules/projects/models/sections.set.php');

//--------------------------------------------------------------------------------------------------
//*	increment a section's weight
//--------------------------------------------------------------------------------------------------
//postarg: action - set to 'incrementSection'
//postarg: UID - UID of a Projects_Section object [string]

	//----------------------------------------------------------------------------------------------
	//	check POST vars and permissions
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('action', $_POST)) { $page->do404('action not given'); }
	if ('incrementSection' != $_POST['action']) { $page->do404('action not recognized'); }

	if (false == array_key_exists('UID', $_POST)) { $page->do404("UID not given."); }

	$model = new Projects_Section($_POST['UID']);
	if (false == $model->loaded) { $page->do404("Section not found."); }

	$project = new Projects_Project($model->projectUID);
	if (false == $project->loaded) { $page->do404('Project not found'); }

	if (false == $user->authHas('projects', 'projects_project', 'edit', $project->UID)) {
		$page->do403();
	}

	//----------------------------------------------------------------------------------------------
	//	increment the section
	//----------------------------------------------------------------------------------------------
	$check = $project->sections->incWeight($model->UID);
	if (false == $check) { $page->do404("Could not increment weight."); }

	//----------------------------------------------------------------------------------------------
	//	redirect back to project
	//----------------------------------------------------------------------------------------------
	$page->do302('projects/' . $project->alias . '#s' . $model->UID);

?>