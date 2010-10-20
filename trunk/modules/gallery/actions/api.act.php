<?

//--------------------------------------------------------------------------------------------------
//	API of galleries module. No public actions.
//--------------------------------------------------------------------------------------------------

if ($user->role == 'public') { $page->doXmlError('not logged in'); }

//--------------------------------------------------------------------------------------------------
//	list records owned by the current user
//--------------------------------------------------------------------------------------------------

if ($req->ref == 'myrecords') {
	$sql = "select * from Gallery_Gallery where createdBy='" . $user->UID . "' order by title";
	$result = $db->query($sql);
	
	echo "<?xml version=\"1.0\"?>\n";
	echo "<recordset>\n";
	while ($row = $db->rmArray($db->fetchAssoc($result))) { 
		$ary = array(	'uid' => $row['UID'], 
						'module' => 'gallery',
						'title' => $row['title'],
						'recordalias' => $row['alias'],
						'files' => 'none',
						'images' => 'uploadmultiple',
						'videos' => 'none' );

		echo $utils->arrayToXml2d($ary, 'record', '  '); 
	}
	echo "</recordset>\n";	
}

?>