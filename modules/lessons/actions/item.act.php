<?php

	require_once($kapenta->installPath . 'modules/lessons/models/course.mod.php');

//--------------------------------------------------------------------------------------------------
//*	download an item
//--------------------------------------------------------------------------------------------------
//arg: course - UID fo an installed course
//arg: document - UID of a document belonging to this course

	//----------------------------------------------------------------------------------------------
	//	check arguments and user role
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('course', $kapenta->request->args)) { $kapenta->page->do404('Course not specified'); }
	if (false == array_key_exists('document', $kapenta->request->args)) { $kapenta->page->do404('Document not specified'); }

	$model = new Lessons_Course($kapenta->request->args['course']);
	if (false == $model->loaded) { $kapenta->page->do404('Course not found'); }
	if (false == $model->has($kapenta->request->args['document'])) { $kapenta->page->do404('Document not found'); }

	$doc = $model->documents[$kapenta->request->args['document']];

	switch($doc['type']) {
		case 'flv':
			$kapenta->page->do302('lessons/play/course_' . $kapenta->request->args['course'] . '/document_' . $kapenta->request->args['document'] . '/');
			break;

		case 'pdf':
			$kapenta->page->do302('lessons/showpdf/course_' . $kapenta->request->args['course'] . '/document_' . $kapenta->request->args['document'] . '/');
			break;

		default:
			$kapenta->page->do302($doc['file']);
			break;
	}

?>
