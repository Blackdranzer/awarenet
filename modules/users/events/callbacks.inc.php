<?

//--------------------------------------------------------------------------------------------------
//	callbacks allow modules to interact with being necessarily dependant on one another
//--------------------------------------------------------------------------------------------------

require_once($kapenta->installPath . 'modules/users/models/user.mod.php');

//--------------------------------------------------------------------------------------------------
//	when a comment is posted
//--------------------------------------------------------------------------------------------------

function users__cb_comments_add($refUID, $commentUID, $comment) {
	global $db, 

	global $user;
	if ($db->objectExists('users', $refUID) == false) { return false; }
	$model = new Users_User($refUID);

	//----------------------------------------------------------------------------------------------
	//	send notifications to project members
	//----------------------------------------------------------------------------------------------

	$ext = $model->extArray();
	$link = "<a href='" . $ext['viewUrl'] . "#comment" . $commentUID ."'>wall</a>";
	$title = $user->getNameLink() . " commented on " . $model->getName() . "'s " . $link;
	$url = $ext['viewUrl'] . '#comment' . $commentUID;
	$imgUID = '';
	$imgUID = imgGetDefaultUID('projects', $refUID);

	if ($user->UID == $refUID) 
		{ $title = $user->getNameLink() . " commented on their own " . $link; }

	notifyFriends($refUID, $commentUID, 
				  $user->getName(), $user->getUrl(),
				  $title, $comment, $url, $imgUID );


	$title = $user->getNameLink() . " commented on your " . $link;
	if ($user->UID == $refUID) 
		{ $title = "You commented on their own " . $link; }

	notifyUser($refUID, $commentUID, 
				  $user->getName(), $user->getUrl(),
				  $title, $comment, $url, $imgUID );

	return true;
}

?>