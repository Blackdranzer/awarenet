<?

//--------------------------------------------------------------------------------------------------
//|	form to add a poll option to the set
//--------------------------------------------------------------------------------------------------

function polls_addanswerform($args) {
	global $theme;

	$html = '';			//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	//TODO: check user permissions
	
	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$block = $theme->loadBlock('modules/polls/views/addanswerform.block.php');
	$html = $theme->replaceLabels($args, $block);

	return $html;
}

?>
