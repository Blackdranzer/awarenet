<?

	require_once($kapenta->installPath . 'modules/files/models/file.mod.php');

//--------------------------------------------------------------------------------------------------
//*	delete a Files_File object
//--------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------------------
	//	delete file passed in GET request as argument
	//----------------------------------------------------------------------------------------------

	if (true == array_key_exists('rmfile', $req->args)) {

		$model = new Files_File($req->args['rmfile']);
		if (false == $model->loaded) { $page->do404('File not found.'); }

		$authorized = false;

		if (true == $user->authHas($model->refModule, $model->refModel, 'files-delete', $model->refUID)) 
			{ $authorized = true; }

		if (true == $user->authHas('files', 'Files_File', 'deleteall')) { $authorized = true; }

		if (false == $authorized) { $page->do403(); }

		$model->delete();

		// dangerous, consider replacing this with something else
		if (array_key_exists('HTTP_REFERER', $_SERVER)) {
			$return = str_replace($serverPath, '', $_SERVER['HTTP_REFERER']);
			$page->do302($return);
		}

		$page->do302('/files/');
		
	}

	//----------------------------------------------------------------------------------------------
	//	delete file passed in POST request as form var
	//----------------------------------------------------------------------------------------------

	if (true == array_key_exists('UID', $_POST)) {
	
		$model = new Files_File($_POST['UID']);
		if (false == $model->loaded) { $page->do404(); }

		$authorized = false;

		if (true == $user->authHas($model->refModule, $model->refModel, 'files-delete', $model->refUID)) 
			{ $authorized = true; }

		if (true == $user->authHas('files', 'Files_File', 'deleteall')) { $authorized = true; }

		if (false == $authorized) { $page->do403(); }

		$model->delete();
	
		if (array_key_exists('return', $_POST)) { $page->do302($_POST['return']); }
	
		// TODO: 302 back to wherever the request came from, user may not have permission
		// to view files and could be redirected to a 403.  Confusing.

		$page->do302('/files/');
		
	} else { $page->do404(); }

?>