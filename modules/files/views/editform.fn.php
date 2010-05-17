<?

	require_once($installPath . 'modules/files/models/file.mod.php');

//--------------------------------------------------------------------------------------------------
//|	form for editing file metadata
//--------------------------------------------------------------------------------------------------
//arg: raUID - recordAlias or UID of record [string]
//opt: return - return to upload dialog [string]

function files_editform($args) {
	$return = '';
	if (array_key_exists('raUID', $args) == false) { return false; }
	if (array_key_exists('return', $args)) { $return = $args['return']; }
	
	$i = new file($args['raUID']);
	if ($i->data['fileName'] == '') { return '(file not found)'; }
	
	$labels = $i->extArray();
	$labels['return'] = $return;
	$labels['returnLink'] = '';
	
	if ($return == 'uploadmultiple') { 
		$labels['returnUrl'] = '/files/uploadmultiple/refModule_' . $labels['refModule'] 
				     . '/refUID_' . $labels['refUID'] . '/';
				     
		$labels['returnLink'] = "<a href='" . $labels['returnUrl'] 
				      . "'>[&lt;&lt; return to upload form ]</a>";
	}
	
	return replaceLabels($labels, loadBlock('modules/files/views/editform.block.php'));
}

//--------------------------------------------------------------------------------------------------

?>

