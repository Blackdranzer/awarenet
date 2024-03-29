<?php

//--------------------------------------------------------------------------------------------------
//*	make an HTML select element listing all peers
//--------------------------------------------------------------------------------------------------

function p2p_selectpeer($args) {
	global $kapenta;
	global $kapenta;

	$html = '';

	//----------------------------------------------------------------------------------------------
	//	check arguments and user role
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { return ''; }

	//----------------------------------------------------------------------------------------------
	//	make the block
	//----------------------------------------------------------------------------------------------
	$range = $kapenta->db->loadRange('p2p_peer', '*', '', 'name');

	$html = "<select id='selPeer' name='peer'>\n";

	foreach($range as $item) {
		$html .= ''
		 . "<option value='" . $item['UID'] . "'>"
		 . $item['name'] . ' (' . $item['url'] . ')'
		 . "</option>\n";
	}

	$html .= "</select>";

	return $html;
}

?>
