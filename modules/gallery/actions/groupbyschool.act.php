<?

	require_once($kapenta->installPath . 'modules/gallery/models/gallery.mod.php');
	
//--------------------------------------------------------------------------------------------------
//*	temporary development / admin / upgrade action to reset the schoolUID field on all galleries
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	admins only
	//----------------------------------------------------------------------------------------------
	if ('admin' != $kapenta->user->role) { $kapenta->page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	query database
	//----------------------------------------------------------------------------------------------

	$sql = "select * from gallery_gallery";
	$result = $kapenta->db->query($sql);
	while ($row = $kapenta->db->fetchAssoc($result)) {
		$item = $kapenta->db->rmArray($row);		
		$creator = $kapenta->db->getObject('users_user', $item['createdBy']);

		if ($creator['school'] != $item['schoolUID']) {
			$model = new Gallery_Gallery($item['UID']);
			$model->schoolUID = $creator['school'];
			$check = $model->save();
			if (false == $check) {
				$kapenta->session->msg("Reset school for gallery: " . $item['title'], 'ok');
			} else {
				$kapenta->session->msg("Could not set school for gallery: " . $item['title'], 'bad');
			}
		}
	}

	//----------------------------------------------------------------------------------------------
	//	redirect back to admin console
	//----------------------------------------------------------------------------------------------
	$kapenta->page->do302('admin/');

?>
