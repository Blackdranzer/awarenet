<?

	require_once($kapenta->installPath . 'modules/schools/models/school.mod.php');

//--------------------------------------------------------------------------------------------------
//|	show the edit form
//--------------------------------------------------------------------------------------------------
//arg: raUID - recordAlias or UID or schools entry [string]

function schools_editform($args) {
	global $theme, $user;
	$html = '';					//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('raUID', $args)) { return ''; }
	$model = new Schools_School($args['raUID']);
	if (false == $user->authHas('schools', 'Schools_School', 'edit', $model->UID)) { return ''; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$ext = $model->extArray();
	$ext['descriptionJs64'] = base64EncodeJs('descriptionJs64', $ext['description']);
	$block = $theme->loadBlock('modules/schools/views/editform.block.php');
	$html = $theme->replaceLabels($ext, $block);

	return $html;
}

//--------------------------------------------------------------------------------------------------

?>
