<?

	require_once($kapenta->installPath . 'modules/videos/models/video.mod.php');

//--------------------------------------------------------------------------------------------------
//*	play an flv or mp4 video using flowplayer 
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if ('' == $req->ref) { $page->do404('Video not specified.'); }

	$model = new Videos_Video($req->ref);
	if (false == $model->loaded) { $page->do404('Video not found.'); }

	if (('public' == $user->role) && ('public' != $model->category)) { $page->do403(); }

	// temporarily disabled while permissions are in flux  TODO: add this back in when stable
	//if (false == $user->authHas('videos', 'videos_video', 'show', $model->UID)) { 
	//	$page->do403(); 
	//}

	//----------------------------------------------------------------------------------------------
	//	block for inline editing if permitted
	//----------------------------------------------------------------------------------------------
	$editAuth = $user->authHas('videos', 'videos_video', 'edit', $model->UID);
	$editBlock = '';

	if (($model->createdBy == $user->UID) || (true == $editAuth)) {
		$editBlock = ''
		 . '[[:theme::navtitlebox::label=Edit Video Details::toggle=divEVD::hidden=yes:]]'
		 . "<div id='divEVD' style='visibility: hidden; display: none;'>"
		 . '[[:videos::editvideoform::raUID=' . $model->UID . '::return=player:]]'
		 . '</div><br/>';
	}

	if ('swf' == $model->format) { $page->do302('videos/animate/' . $model->alias); }

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------

	$page->load('modules/videos/actions/play.page.php');
	$page->blockArgs['UID'] = $model->UID;
	$page->blockArgs['title'] = $model->title;
	$page->blockArgs['caption'] = $model->caption;
	$page->blockArgs['raUID'] = $model->alias;
	$page->blockArgs['editBlock'] = $editBlock;
	$page->render();

?>
