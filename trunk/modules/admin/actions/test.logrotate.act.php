<?php

	require_once($kapenta->installPath . 'modules/admin/inc/cron.inc.php');

//--------------------------------------------------------------------------------------------------
//*	development/administrative action to test log rotation
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	echo admin_cron_daily();

?>