<?

//--------------------------------------------------------------------------------------------------
//|	show iframe console for editing tags, or list of tags if no permissions
//--------------------------------------------------------------------------------------------------
//arg: refModule - name of a kapenta module [string]
//arg: refModel - type of object which may have tags [string]
//arg: refUID - UID of object which may have tags [string]

function tags_edittags($args) {
		global $kapenta;
		global $kapenta;
		global $theme;
		global $kapenta;

	$html = '';		//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('refModule', $args)) { return '(no refModule)'; }
	if (false == array_key_exists('refModel', $args)) { return '(no refModel)'; }
	if (false == array_key_exists('refUID', $args)) { return '(no refUID)'; }
	
	$refModule = $args['refModule'];
	$refModel = $args['refModel'];
	$refUID = $args['refUID'];

	if (false == $kapenta->moduleExists($refModule)) { return '(no such module)'; }
	if (false == $kapenta->db->objectExists($refModel, $refUID)) { return '(no such owner)'; }

	if (false == $kapenta->user->authHas($refModule, $refModel, 'tags-manage', $refUID)) 
		{ return "[[:tags::showtags::refModule=$refModule::refModel=$refModel::refUID=$refUID:]]"; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$ifUrl = '%%serverPath%%tags/edittags/'  
			. 'refModule_' . $refModule . '/'
			. 'refModel_' . $refModel . '/'
			. 'refUID_' . $refUID;

	$html = "[[:theme::navtitlebox::label=Tags::toggle=divET" . $refUID . ":]]
		<div id='divET" . $refUID . "'>
		<iframe name='editTags" . $refUID . "' id='ifEdittags" . $refUID . "'
			src='" . $ifUrl . "'
			width='100%' height='300' frameborder='no'>
		</iframe>
		</div>
		";

	return $html;
}

?>
