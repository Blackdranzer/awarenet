<?php

	require_once($kapenta->installPath . 'modules/files/models/file.mod.php');

//--------------------------------------------------------------------------------------------------
//|	display file summary formatted for stacking in the nav
//--------------------------------------------------------------------------------------------------
//arg: raUID - alias or UID of an Files_File object [string]
//opt: UID - overrides raUID if present [string]
//opt: fileUID - overrides raUID if present [string]
//opt: behavior - Behavior when links are clicked (link|editmodal) [string]

function files_summarynav($args) {
	global $kapenta;
	global $theme;
	global $kapenta;

	$behavior = 'link';						//%	behavior of links [string]
	$html = '';								//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permisisons
	//----------------------------------------------------------------------------------------------
	if (true == array_key_exists('UID', $args)) { $args['raUID'] = $args['UID']; }
	if (true == array_key_exists('fileUID', $args)) { $args['raUID'] = $args['fileUID']; }
	if (false == array_key_exists('raUID', $args)) { return '(raUID not given)'; }

	$model = new Files_File($args['raUID']);
	if (false == $model->loaded) { return '(could not load file: ' . $args['raUID'] . ')'; }

	//	TODO: permissions check here

	if (true == array_key_exists('behavior', $args)) { $behavior = $args['behavior']; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$block = $theme->loadBlock('modules/files/views/summarynav.block.php');
	$labels = $model->extArray();
	$labels['extra'] = '';				//	reserved
	$labels['controls'] = '';			//	reserved
	$labels['onClick'] = '';

	if ('editmodal' == $behavior) {
		$kapenta->page->requireJs('%%serverPath%%modules/files/js/editor.js');
		$labels['viewUrl'] = "javascript:Files_EditModal('" . $model->UID . "');";
		$labels['controls'] = ''
		 . "<span style='float: right;'>"
		 . "<small>"
		 . "<a href=\"javascript:Files_EditTags('" . $model->UID . "');\">[edit tags]</a>"
		 . "<a href=\"javascript:Files_Delete('" . $model->UID . "');\">[delete]</a>"
		 . "<a href=\"javascript:Files_MakeDefault('" . $model->UID . "');\">[move to top]</a>"
		 . "</small>"
		 . '';
	}

	$html = $theme->replaceLabels($labels, $block);

	return $html;
}

?>
