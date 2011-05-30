<?

//--------------------------------------------------------------------------------------------------
//*	action to search for friends
//--------------------------------------------------------------------------------------------------

	$search = '';		//%	search query, if any [string]

	//----------------------------------------------------------------------------------------------
	//	check permissions and arguments
	//----------------------------------------------------------------------------------------------
	if (false == $user->authHas('users', 'users_user', 'show'))
		{ $page->do403('Please log in.', true); }

	if (true == array_key_exists('q', $_POST)) { $search = $utils->cleanString($_POST['q']); }

	//----------------------------------------------------------------------------------------------
	//	add var for search?
	//----------------------------------------------------------------------------------------------

	$add = '';
	if (true == array_key_exists('add', $req->args)) {
		$add = "<br/>[[:users::friendrequestprofilenav::"
					 . "userUID=" . $req->args['add'] . "::notitle=yes:]]";
	}

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$page->load('modules/users/actions/find.page.php');
	$page->blockArgs['fsearch'] = $search;
	$page->blockArgs['fadd'] = $add;
	$page->render();

?>
