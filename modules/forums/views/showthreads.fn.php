<?

	require_once($kapenta->installPath . 'modules/forums/models/board.mod.php');
	require_once($kapenta->installPath . 'modules/forums/models/reply.mod.php');
	require_once($kapenta->installPath . 'modules/forums/models/thread.mod.php');

//--------------------------------------------------------------------------------------------------
//|	list threads in a forum
//--------------------------------------------------------------------------------------------------
//arg: forumUID - UID of a forum [string]
//opt: pageno - page number (default is 1) [string]
//opt: num - number of threads per page (default is 20) [string]
//opt: pagination - set to 'no' to disable paginated nav [string]

function forums_showthreads($args) {
	global $db;
	global $page;
	global $theme;

	$pageno = 1; 			//%	current page number [int]
	$num = 20; 				//%	number of threads per page [int]
	$html = '';				//%	return value [string]
	$pagination = 'yes';	//%	show HTML pagination (yes|no) [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('forumUID', $args)) { return ''; }
	if (true == array_key_exists('pageno', $args)) { $pageno = (int)$args['pageno']; }
	if (true == array_key_exists('num', $args)) { $num = (int)$args['num']; }
	if (true == array_key_exists('pagination', $args)) { $pagination = $args['pagination']; }				

	$model = new Forums_Board($args['forumUID']);
	if (false == $model->loaded) { return ''; }

	//----------------------------------------------------------------------------------------------
	//	count all threads on this forum
	//----------------------------------------------------------------------------------------------
	//TODO: re-arrage this to skip pagination step if not rendered
	$conditions = array("board='" . $model->UID . "'");
	$totalItems = $db->countRange('forums_thread', $conditions);
	$totalPages = ceil($totalItems / $num);

	//----------------------------------------------------------------------------------------------
	//	show the current page
	//----------------------------------------------------------------------------------------------
	$start = (($pageno - 1) * $num);
	$range = $db->loadRange('forums_thread', '*', $conditions, 'updated DESC', $num, $start);

	//$sql = "select * from Forums_Thread where forum='" . $fUID . "' order by updated DESC " . $limit;	
	$rowBlock = $theme->loadBlock('modules/forums/views/threadrow.block.php');

	$html .= "<table noborder>";
	foreach ($range as $row) {
		$thisThread = new Forums_Thread();
		$thisThread->loadArray($row);
		$html .= $theme->replaceLabels($thisThread->extArray(), $rowBlock);
	}

	$html .= "</table>";

	$link = '%%serverPath%%forums/show/' . $model->alias . '/';

	$pagination = "[[:theme::pagination::page=" . $db->addMarkup($pageno) 
				. "::total=" . $totalPages . "::link=" . $link . ":]]\n";

	if (0 == $totalItems) { $html = "(no threads in this forum as yet)";	}

	if ('yes' == $pagination) { $html = $pagination . $html . $pagination; }

	return $html;
}

//--------------------------------------------------------------------------------------------------

?>