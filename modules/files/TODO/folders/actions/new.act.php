<?

	require_once($kapenta->installPath . 'modules/folders/models/folder.mod.php');

//--------------------------------------------------------------------------------------------------
//	add a new (root) folder
//--------------------------------------------------------------------------------------------------
//TODO: fix this up, probably with autogenerated code

	if (false == $kapenta->user->authHas('files', 'files_folder', 'new')) { $kapenta->page->do403(); }

	$model = new Folder();
	$model->save();
	
	$kapenta->page->do302('folders/edit/' . $model->alias);

?>
