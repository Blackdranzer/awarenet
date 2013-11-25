<?php

	require_once($kapenta->installPath . 'modules/newsletter/models/adunit.mod.php');

//--------------------------------------------------------------------------------------------------
//*	action to remove an ad unit
//--------------------------------------------------------------------------------------------------
//ref: UID - UID of the ad unit to be removed

	if ('admin' != $user->role) { $page->do403(); }

	if ('' == $kapenta->request->ref) { $page->do404('ad unit not specified'); }

	$model = new Newsletter_Adunit($kapenta->request->ref);
	if (false == $model->loaded) { $page->do404('Unknown ad unit.'); }

	$check = $model->delete();

	if (true == $check) {
		$session->msg("Ad unit deleted");
	} else {
		$session->msg("Could not delete ad unit.");
	}

	$page->do302('newsletter/');

?>
