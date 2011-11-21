<?

	require_once($kapenta->installPath . 'modules/comments/models/comment.mod.php');

//--------------------------------------------------------------------------------------------------
//*	add a comment to something
//--------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------------------
	//	check request vars 
	//----------------------------------------------------------------------------------------------

	if (false == array_key_exists('refModule', $_POST)) { $page->do404('refModule not given'); }
	if (false == array_key_exists('refModel', $_POST)) { $page->do404('refModel not given'); }
	if (false == array_key_exists('refUID', $_POST)) { $page->do404('refUID not given'); }
	if (false == array_key_exists('return', $_POST)) { $page->do404('no return url'); }

	$refModule = $_POST['refModule'];
	$refModel = $_POST['refModel'];
	$refUID = $_POST['refUID'];
	$return = $_POST['return'];

	//----------------------------------------------------------------------------------------------
	//	check permissions, valid module
	//----------------------------------------------------------------------------------------------
	//TODO: check that model exists
	if (false == in_array($refModule, $kapenta->listModules())) { $page->do404(); }
	if (false == $user->authHas($refModule, $refModel, 'comments-add', $refUID)) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	dont save blank comments
	//----------------------------------------------------------------------------------------------
	if ('' == trim($_POST['comment'])) { 
		$session->msg('No comment entered', 'bad');
		$page->do302($return); 
	}

	//----------------------------------------------------------------------------------------------
	//	save the record
	//----------------------------------------------------------------------------------------------

	$model = new Comments_Comment();
	$model->refModule = $refModule;
	$model->refModel = $refModel;
	$model->refUID = $refUID;
	$model->comment = $utils->cleanHtml($_POST['comment']);
	$ext = $model->extArray();
	$report = $model->save();

	//----------------------------------------------------------------------------------------------
	//	raise 'comment_added' event on refModule
	//----------------------------------------------------------------------------------------------
	$args = array(	'refModule' => $refModule, 
					'refModel' => $refModel, 
					'refUID' => $refUID, 
					'commentUID' => $ext['UID'], 
					'comment' => $ext['comment']    );

	$kapenta->raiseEvent($refModule, 'comments_added', $args);
	
	//----------------------------------------------------------------------------------------------
	//	return to whence the comment came
	//----------------------------------------------------------------------------------------------
	
	if ('none' == $return) { echo '#COMMENT ADDED\n'; }	//TODO: XML option
	else { $page->do302($return); }

?>