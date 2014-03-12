<?

//--------------------------------------------------------------------------------------------------
//	save a folder entry
//--------------------------------------------------------------------------------------------------

	if ($kapenta->user->authHas('files', 'files_folder', 'edit', 'TODO:UIDHERE') == false) { $kapenta->page->do403(); }
	if (array_key_exists('UID', $_POST) == false) { $kapenta->page->do404(); }
	if ($kapenta->db->objectExists('folders', $_POST['UID']) == false) { $kapenta->page->do404(); }

	require_once($kapenta->installPath . 'modules/folders/models/folder.mod.php');

	//----------------------------------------------------------------------------------------------
	//	load model and check against current user
	//----------------------------------------------------------------------------------------------

	$model = new Folder($_POST['UID']);

	$authorised = false;
	if ($model->createdBy == $kapenta->user->UID) { $authorised = true; }
	if ('admin' == $kapenta->user->role) { $authorised = false; }
	if ($authorised == false) { $kapenta->page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	authorised, save any changes
	//----------------------------------------------------------------------------------------------

	//$model->parent = $_POST['UID'];				// moving folders is on hold for now
	$model->title = $_POST['title'];
	$model->description = $_POST['description'];	// not currently used
	$model->save();

	$kapenta->page->do302('folders/' . $model->alias);

?>
