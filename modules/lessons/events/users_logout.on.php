<?
	require_once($kapenta->installPath . 'modules/lessons/inc/khan.inc.php');

//-------------------------------------------------------------------------------------------------
//|	fired when the user logs out.php
//-------------------------------------------------------------------------------------------------
function lessons__cb_users_logout($args) {
	return logoutKhanLite();
}

//-------------------------------------------------------------------------------------------------
?>