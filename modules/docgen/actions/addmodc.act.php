<?

//-------------------------------------------------------------------------------------------------
//*	add documentation to models
//-------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------
	//	admin only
	//---------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	//---------------------------------------------------------------------------------------------
	//	make list of all models
	//---------------------------------------------------------------------------------------------
	$mods = $kapenta->listModules();
	foreach($mods as $mod) {
		echo "<h2>$mod</h2>\n";

		$models = $kapenta->listModels($mod);

		foreach($models as $modelFile) {
			echo $modelFile . "<br/>\n";
		}

	}

?>