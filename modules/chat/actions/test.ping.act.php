<?

//--------------------------------------------------------------------------------------------------
//*	test/development action to test this module's response to the live_ping event
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	admins only
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	$pingArgs = array('user' => $user->UID, 'role' => $user->role);
	$kapenta->raiseEvent('chat', 'live_ping', $pingArgs);
	echo "Event raised.";

?>
