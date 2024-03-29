<?

	require_once($kapenta->installPath . 'modules/comments/models/comment.mod.php');

//-------------------------------------------------------------------------------------------------
//|	fired when an object is deleted ($kapenta->db->delete())
//-------------------------------------------------------------------------------------------------
//arg: module - module which owned the deleted record [string]
//arg: model - type of object which was deleted [string]
//arg: UID - UID of deleted object [string]

function comments__cb_object_deleted($args) {
	global $kapenta;
	global $kapenta;

	if (false == array_key_exists('module', $args)) { return false; }
	if (false == array_key_exists('UID', $args)) { return false; }

	//----------------------------------------------------------------------------------------------
	//	delete any comments owned by this record
	//----------------------------------------------------------------------------------------------
	$conditions = array();
	$conditions[] = "refUID='" . $kapenta->db->addMarkup($args['UID']) . "'";
	$condiiions[] = "refModule='" . $kapenta->db->addMarkup($args['module']) . "'";
	$range = $kapenta->db->loadRange('comments_comment', '*', $conditions, '', '', '');

	foreach($range as $item) {
		$model = new Comments_Comment();
		$model->loadArray($item);
		$model->delete();
	}

	return true;
}

//-------------------------------------------------------------------------------------------------
?>
