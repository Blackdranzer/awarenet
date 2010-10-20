<?

//--------------------------------------------------------------------------------------------------
//	delete a record
//--------------------------------------------------------------------------------------------------

	if ($user->authHas('files', 'Files_Folder', 'edit', 'TODO:UIDHERE') == false) { $page->do403(); }

	if ( (array_key_exists('action', $_POST)) 
	  AND ($_POST['action'] == 'deleteRecord') 
	  AND (array_key_exists('UID', $_POST)) 
	  AND ($db->objectExists('folder', $_POST['UID'])) ) {
	  
		require_once($kapenta->installPath . 'modules/folder/folder.mod.php');
	  
		$model = new folder();
		$model->load($_POST['UID']);
		
		$_SESSION['sMessage'] .= "Deleted folder: " . $model->title;
		
		$model->delete();
		
		$page->do302('folder/');
	  
	} else { $page->do404(); }

?>