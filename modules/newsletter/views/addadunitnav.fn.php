<?

	require_once($kapenta->installPath . 'modules/newsletter/models/adunit.mod.php');

//--------------------------------------------------------------------------------------------------
//|	form to add a new Adunit object, formatted for nav
//--------------------------------------------------------------------------------------------------

function newsletter_addadunitnav($args) {
		global $user;
		global $theme;


	$html = '';					//% return value [string]

	//----------------------------------------------------------------------------------------------
	//	check permissions and args
	//----------------------------------------------------------------------------------------------
	if (false == $user->authHas('newsletter', 'Newsletter_Adunit', 'new')) { return ''; }

	//----------------------------------------------------------------------------------------------
	//	load the block
	//----------------------------------------------------------------------------------------------
	$html = $theme->loadBlock('modules/newsletter/views/addadunitnav.block.php');

	return $html;
}

?>