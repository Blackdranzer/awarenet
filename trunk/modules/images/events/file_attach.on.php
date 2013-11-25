<?

	require_once($kapenta->installPath . 'modules/images/models/image.mod.php');

//--------------------------------------------------------------------------------------------------
//|	fired when a file has been uploaded by the live module
//--------------------------------------------------------------------------------------------------
//arg: refModule - name of a kapenta module [string]
//arg: refModel - type of object which owns this image [string]
//arg: refUID - UID of object which own this image [string]
//arg: path - location of file [string]
//arg: srcName - original location of file [string]
//arg: name - original name file [string]
//arg: ext - file extension [string]
//arg: module - module which handles files of this type [string]

function images__cb_file_attach($args) {
	global $kapenta;
	global $session;
	global $utils;
	global $registry;
	global $user;

	$msg = ''
	 . "File Uploaded<br>"
	 . "refModule: " . $args['refModule'] . "<br/>"
	 . "refModel: " . $args['refModel'] . "<br/>"
	 . "refUID: " . $args['refUID'] . "<br/>"
	 . "path: " . $args['path'] . "<br/>"
	 . "srcName: " . $args['srcName'] . "<br/>"
	 . "extension: " . $args['extension'] . "<br/>";
	$session->msg($msg);

	//----------------------------------------------------------------------------------------------
	//	check arguments
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('refModule', $args)) { return false; }
	if (false == array_key_exists('refModel', $args)) { return false; }
	if (false == array_key_exists('refUID', $args)) { return false; }
	if (false == array_key_exists('module', $args)) { return false; }
	if (false == array_key_exists('path', $args)) { return false; }

	if ('images' != $args['module']) { return false; }

	//TODO: permissions check here

	//----------------------------------------------------------------------------------------------
	//	check that this is actually an image
	//----------------------------------------------------------------------------------------------
	$raw = $kapenta->fileGetContents($args['path']);	//%	contents of uploaded file [string]
	$gdh = imagecreatefromstring($raw);					//%	GD image handle [int]

	if (false == $gdh) {
		$session->msg('Uploaded file was not a valid image.', 'bad');
		return false;
	}

	//----------------------------------------------------------------------------------------------
	//	create a new images_image object attached to this gallery
	//----------------------------------------------------------------------------------------------

	$model = new Images_Image();
	$model->UID = $kapenta->createUID();
	$model->refModule = $args['refModule'];
	$model->refModel = $args['refModel'];
	$model->refUID = $args['refUID'];
	$model->title = $utils->cleanTitle($args['name']);
	$model->format = $args['extension'];
	$model->shared = 'yes';

	$model->fileName = ''
	 . 'data/images/'
	 . substr($model->UID, 0, 1) . '/'
	 . substr($model->UID, 1, 1) . '/'
	 . substr($model->UID, 2, 1) . '/'
	 . $model->UID . '.jpg';

	$kapenta->fileMakeSubdirs($model->fileName);

	$report = $model->save();

	if ('' == $report) {
		//------------------------------------------------------------------------------------------
		//	save as .jpg
		//------------------------------------------------------------------------------------------
		$check = imagejpeg($gdh, $model->fileName, 90);
		if (false == $check) {
			$report .= "Could not convert image to jpg format.";
		} else {
			$session->msg('Attached image.');
		}
	}

	if ('' == $report) {
		//------------------------------------------------------------------------------------------
		//	rescale large files
		//------------------------------------------------------------------------------------------
		$maxSize = $registry->get('images.maxsize');
		$fileSize = $kapenta->fileSize($model->fileName);

		$model->load($model->UID);
		$model->transforms->load();
		$model->transforms->loadImage();
		$mdoel->transforms->sourceFile = $model->fileName;

		if (false == $model->transforms->loaded) { $report .= "Transforms not loaded."; }

		if ($model->loaded && $model->transforms->loaded) {
			$check = $model->transforms->reduce();
		
			if (false == $check) {
				$report .= "Could not transform to smaller size:<br/>" . $model->fileName;
			} else {
				$session->msg('Attached image.');
			}
		} else {
			$report .= "Could not rescale image.";
		}
	}

	if ('' == $report) {
		//------------------------------------------------------------------------------------------
		//	image was uploaded correctly raise file_added event for this image (p2p uses it)
		//------------------------------------------------------------------------------------------
		$detail = array(
			'refModule' => 'images', 
			'refModel' => 'images_image', 
			'refUID' => $model->UID, 
			'fileName' => $model->fileName, 
			'hash' => $kapenta->fileSha1($model->fileName), 
			'size' => $kapenta->fileSize($model->fileName) 
		);

		$kapenta->raiseEvent('*', 'file_added', $detail);

		//------------------------------------------------------------------------------------------
		//	send 'images_added' event to module whose record owns this image
		//------------------------------------------------------------------------------------------
		$detail = array(	
			'refModule' => $model->refModule, 
			'refModel' => $model->refModel, 
			'refUID' => $model->refUID, 
			'imageUID' => $model->UID, 
			'imageTitle' => $model->title    
		);

		$kapenta->raiseEvent('*', 'images_added', $detail);
		$session->msgAdmin('Attached image file.');

		//------------------------------------------------------------------------------------------
		//	tag the new image with the username and file name it was added by
		//------------------------------------------------------------------------------------------

		$detail = array(
			'refModule' => 'images',
			'refModel' => 'images_image',
			'refUID' => $model->UID
		);

		$detail['tagName'] = $user->getName();
		$kapenta->raiseEvent('tags', 'tags_add', $detail);

		//$detail['tagName'] = $user->username;
		//$kapenta->raiseEvent('tags', 'tags_add', $detail);
		
	} else {
		$session->msg('Could not create image object: ' . $report);
	}

}

?>