<?

//--------------------------------------------------------------------------------------------------
//*	show package if reference (UID) if given, list available packages if not
//--------------------------------------------------------------------------------------------------

	if ('' == $req->ref) {
		include($kapenta->installPath . 'modules/packages/actions/list.act.php');

	} else {
		include($kapenta->installPath . 'modules/packages/actions/showpackage.act.php');
	}

?>
