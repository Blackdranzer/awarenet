<?

	require_once($kapenta->installPath . 'modules/newsletter/models/notice.mod.php');

//--------------------------------------------------------------------------------------------------
//|	form to add a new Notice object, formatted for nav
//--------------------------------------------------------------------------------------------------

function newsletter_addnoticenav($args) {
	global $user, $theme;

	$html = '';					//% return value [string]

	//----------------------------------------------------------------------------------------------
	//	check permissions and args
	//----------------------------------------------------------------------------------------------
	if (false == $user->authHas('newsletter', 'Newsletter_Notice', 'new')) { return ''; }

	//----------------------------------------------------------------------------------------------
	//	load the block
	//----------------------------------------------------------------------------------------------
	$html = $theme->loadBlock('modules/newsletter/views/addnoticenav.block.php');

	return $html;
}

?>