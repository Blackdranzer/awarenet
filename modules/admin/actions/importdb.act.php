<?php

//--------------------------------------------------------------------------------------------------
//*	Directly import a database
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	admins only
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $kapenta->page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	set up the drivers
	//----------------------------------------------------------------------------------------------
	$dbFrom = 'KDBDriver_MySQL';
	$dbTo = 'KDBDriver_SQLite';

	$drvFrom = new $dbFrom();
	$drvTo = new $dbTo();

	//----------------------------------------------------------------------------------------------
	//	copy the database
	//----------------------------------------------------------------------------------------------
	
	

?>
