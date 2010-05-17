<?

	require_once($installPath . 'modules/projects/models/membership.mod.php');
	require_once($installPath . 'modules/projects/models/projectrevision.mod.php');
	require_once($installPath . 'modules/projects/models/project.mod.php');

//--------------------------------------------------------------------------------------------------
//|	summarise
//--------------------------------------------------------------------------------------------------
//arg: raUID - recordAlias or UID or projects entry [string]

function projects_summary($args) {
	if (array_key_exists('raUID', $args) == false) { return false; }
	$g = new project(sqlMarkup($args['raUID']));	
	return replaceLabels($g->extArray(), loadBlock('modules/projects/views/summary.block.php'));
}

//--------------------------------------------------------------------------------------------------

?>

