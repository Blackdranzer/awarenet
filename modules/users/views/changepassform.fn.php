<?

	require_once($installPath . 'modules/users/models/friendship.mod.php');
	require_once($installPath . 'modules/users/models/user.mod.php');

//--------------------------------------------------------------------------------------------------
//|	form to change password
//--------------------------------------------------------------------------------------------------
//arg: raUID - recordAlias or UID of a user record [string]

function users_changepassform($args) {
	if (array_key_exists('raUID', $args) == false) { return false; }
	$u = new User($args['raUID']);
	return replaceLabels($u->extArray(), loadBlock('modules/users/views/changepassform.block.php'));
}

//--------------------------------------------------------------------------------------------------

?>
