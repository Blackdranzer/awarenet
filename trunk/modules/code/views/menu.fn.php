<?

//--------------------------------------------------------------------------------------------------
//*	menu for repository, no arguments
//--------------------------------------------------------------------------------------------------

function code_menu($args) {
	global $theme;
	$labels = array();
	
	$html = $theme->loadBlock('modules/code/views/menu.block.php');
	return $html;	
}

//--------------------------------------------------------------------------------------------------

?>