<?

//-------------------------------------------------------------------------------------------------
//	uploading local installation from repository on kapenta.org.uk
//-------------------------------------------------------------------------------------------------

	if ($user->data['ofGroup'] != 'admin') { do403(); }
	require_once($installPath . 'modules/admin/models/repository.mod.php');

	//---------------------------------------------------------------------------------------------
	//	set up repository access
	//---------------------------------------------------------------------------------------------

	$projectUID = '106665425118526027';
	$repository = 'http://kapenta.org.uk/code/';

	$repository = new CodeRepository($repository, $projectUID);

	$repository->addExemption("setup.inc.php");				// dynamically generated on install
	$repository->addExemption("uploader/");					// this module (CONTAINS KEY)
	$repository->addExemption("install/");					// defunct
	$repository->addExemption(".svn");						// sybversion files and directories
	//$repository->addExemption("data/log/");					// logs
	$repository->addExemption("/chan/");					// not part of awareNet
	$repository->addExemption('svnadd.sh');					// development SVN script
	$repository->addExemption('svndelete.sh');				// development SVN script
	$repository->addExemption('data/log/e');				// ?
	$repository->addExemption('.log.php');					// log files
	$repository->addExemption('~');							// gedit revision files
	$repository->addExemption('/drawcache/');				// dynamically generated images

	for ($i = 0; $i < 10; $i++) {
		$repository->addExemption("data/images/" . $i);		// user images
		$repository->addExemption("data/files/" . $i);		// user files
	}

	//---------------------------------------------------------------------------------------------
	//	get list of files from respoitory
	//---------------------------------------------------------------------------------------------

	echo "[i] getting repository list: " . $repository->listUrl . "... "; flush();
	$rList = $repository->getRepositoryList();
	echo "done <br/>\n"; flush();

	//---------------------------------------------------------------------------------------------
	//	check that all files in repository exist on local server
	//---------------------------------------------------------------------------------------------

	foreach($rList as $rUID => $item) { 
		$absFile = str_replace('//', '/', $installPath . $item['relfile']);
		if (file_exists($absFile) == false) {
			echo "[*] $absFile is missing.<br/>\n";
			downloadFileFromRepository($item);	
		}
	}


	//---------------------------------------------------------------------------------------------
	//	get local list
	//---------------------------------------------------------------------------------------------

	$skipList = array();

	//---------------------------------------------------------------------------------------------
	//	list local files, compare to repository
	//---------------------------------------------------------------------------------------------

	$raw = shell_exec("find $installPath");
	$lines = explode("\n", $raw);

	foreach($lines as $line) {
		//-----------------------------------------------------------------------------------------
		//	decide which ones to skip
		//-----------------------------------------------------------------------------------------

		$skip = false;
		if (trim($line) == '') { $skip = true; }						// must not be blank	
		$line = str_replace($installPath, '/', $line);					// relative position
		$fileName = basename($line);									// get filename
		if (strpos(' ' . $fileName, '.') == false) { $skip = true; }	// filename must contain .

		// search for exemptions
		$exempt = $repository->getExemptions();
		foreach ($exempt as $find) { if (strpos(' ' . $line, $find) != false) { $skip = true; } }

		//-----------------------------------------------------------------------------------------
		//	compare hash with local file
		//-----------------------------------------------------------------------------------------

		if ($skip == false) {
			$itemUID = ''; 
			$sha1 = sha1(implode(file($installPath . $line)));

			//--------------------------------------------------------------------------------------
			//	compare to repository
			//--------------------------------------------------------------------------------------
			foreach($rList as $rUID => $item) 
				{ if ($item['relfile'] == $line) { $itemUID = $rUID; } }

			if ($itemUID == false) {
				//----------------------------------------------------------------------------------
				//	is not in repository, note this to user
				//----------------------------------------------------------------------------------
				echo "[>] not in repository: $line <br/>\n"; flush();
				

			} else {
				if ($sha1 != $rList[$itemUID]['hash']) {
					//------------------------------------------------------------------------------
					//	is different to version in repository, update it
					//------------------------------------------------------------------------------
					$relFile = rList[$itemUID]['relfile'];
					echo "[i] this file should be updated: " . $relFile . " <br/>\n";
					downloadFileFromRepository($rList[$itemUID]);

				} else {
					//------------------------------------------------------------------------------
					//	files match
					//------------------------------------------------------------------------------
					//echo "[>] hashes match: " . $rList[$itemUID]['relfile'] . " <br/>\n";
					
				}

			}	// end if itemUID == false		

		} else { $skipList[] = $line; }

	} // end foreach line

	echo "<h1>Skipped Files</h1>\n";
	foreach ($skipList as $path) { echo $path . "<br/>\n"; }

//==================================================================================================
//	utility functions
//==================================================================================================

//--------------------------------------------------------------------------------------------------
// download a single file from the repository
//--------------------------------------------------------------------------------------------------

function downloadFileFromRepository($item) {
	global $repository;
	global $installPath;
	//----------------------------------------------------------------------------------------------
	//	create all folders
	//----------------------------------------------------------------------------------------------
	

	//----------------------------------------------------------------------------------------------
	//	download the file
	//----------------------------------------------------------------------------------------------
	$outFile = $installPath . $item['relfile'];
	$outFile = str_replace('//', '/', $outFile);

	if (file_exists($outFile) == true) { echo "[|] Replacing $outFile (already present)<br/>\n"; }
	else { echo "[|] Downloading $outFile (not present in local installation)<br/>\n"; }

	//----------------------------------------------------------------------------------------------
	//	download from repository door
	//----------------------------------------------------------------------------------------------
	$content = @file($repository->doorUrl . $item['uid']);

	if ($content == false) 
		{ echo "[*] Error: could not download $outFile (UID:" . $item['uid'] . ")<br/>\n";	} 
	else {
		//------------------------------------------------------------------------------------------
		//	content is base64 encoded
		//------------------------------------------------------------------------------------------
		$content = base64_decode(implode($content));

		//------------------------------------------------------------------------------------------
		//	save it :-)
		//------------------------------------------------------------------------------------------
		$check = filePutContents($outFile, $content, 'w+');
		if ($check == false) {
			echo "[*] Error: could not open $outFile for writing.<br/>\n";	flush();
			return false;
		} else {
			echo "[>] Saving $outFile (UID:" . $item['uid'] . ") "
				 . "(type:" . $item['type'] . ")<br/>\n";	flush();
		} // end if cant write

	} // end if bad download

	return true;
}

?>
