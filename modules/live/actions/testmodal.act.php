<?php

//--------------------------------------------------------------------------------------------------
//*	test of modal windows with jQuery
//--------------------------------------------------------------------------------------------------

	if ('admin' != $user->role) { $page->do403(); }

	$page->load('modules/live/actions/testmodal.page.php');
	$page->render();

?>
