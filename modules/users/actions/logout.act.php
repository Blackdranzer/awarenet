<?

//--------------------------------------------------------------------------------------------------------------
//	log the user out and redirect to the homepage
//--------------------------------------------------------------------------------------------------------------

	if ('public' == $session->user) {
		//----------------------------------------------------------------------------------------------
		//	user was not logged in
		//----------------------------------------------------------------------------------------------
		$session->msg("You are already logged out.<br/>\n");
		$page->do302(''); // homepage		

	} else {
		//----------------------------------------------------------------------------------------------
		//	log them out
		//----------------------------------------------------------------------------------------------
		//$userlogin->delete();
		$session->user = 'public';
		$session->msg("You are now logged out.<br/>\n", 'ok');
		$session->store();
		$page->do302('');

	}

?>