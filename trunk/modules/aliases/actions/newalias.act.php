<?

	require_once($kapenta->installPath . 'modules/alias/models/alias.mod.php');

//--------------------------------------------------------------------------------------------------
//*	create a new Alias object
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions and any POST variables
	//----------------------------------------------------------------------------------------------
	if (false == $user->authHas('aliases', 'Aliases_Alias', 'new'))
		{ $page->do403('You are not authorized to create new aliases.'); }

	if (false == array_key_exists('module', $_POST))
		{ $page->do404('reference module not specified', true); }
	if (false == array_key_exists('model', $_POST))
		{ $page->do404('reference model not specified', true); }
	if (false == array_key_exists('UID', $_POST))
		{ $page->do404('reference object UID not specified', true); }
	if (false == moduleExists($_POST['module'])) { $page->do404(); }
	if (false == $db->objectExists($_POST['model'], $_POST['UID'])) { $page->do404('no model specified', true); }


	//----------------------------------------------------------------------------------------------
	//	create the object
	//----------------------------------------------------------------------------------------------
	//TODO: use a switch
	$model = new Aliases_Alias();
	if (true == array_key_exists('refModule', $_POST)) { $model->refModule = $_POST['refModule']; }
	if (true == array_key_exists('refModel', $_POST)) { $model->refModel = $_POST['refModel']; }
	if (true == array_key_exists('refUID', $_POST)) { $model->refUID = $_POST['refUID']; }
	if (true == array_key_exists('aliaslc', $_POST)) { $model->aliaslc = $_POST['aliaslc']; }
	if (true == array_key_exists('alias', $_POST)) { $model->alias = $_POST['alias']; }
	$report = $model->save();

	//----------------------------------------------------------------------------------------------
	//	check that object was created and redirect
	//----------------------------------------------------------------------------------------------
	if ('' == $report) {
		//$session->msg('New Alias.');
		$page->do302('/alias/editalias/' . $model->UID);
	} else {
		$session->msg('Could not create new Alias:<br/>' . $report);
	}



?>
