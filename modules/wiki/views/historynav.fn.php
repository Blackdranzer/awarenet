<?

	require_once($kapenta->installPath . 'modules/wiki/models/article.mod.php');
	require_once($kapenta->installPath . 'modules/wiki/models/revision.mod.php');

//--------------------------------------------------------------------------------------------------
//|	history summary formatted for nav // TODO: pagination
//--------------------------------------------------------------------------------------------------
//arg: UID - UID of a wiki article [string]
//opt: num - number of recent entries to show, default is 30 (int) [string]
//opt: pageNo - results page to show, from 1, default is 1 (int)  [string]

function wiki_historynav($args) {
	global $user, $db, $theme;
	$pageNo = 1;			//%	default results page to start from (first) [int]
	$num = 30;				//%	default number of items to show per page [int]
	$totalItems = 0;		//%	total number of revisions to this item [int]
	$html = '';				//%	return value [string]

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (false == array_key_exists('UID', $args)) { return '(UID not given)'; }
	if (true == array_key_exists('num', $args)) { $num = (int)$args['num']; }
	if (true == array_key_exists('pageNo', $args)) { $pageNo = (int)$args['pageNo']; }

	$model = new Wiki_Article($args['UID']);
	if (false == $model->loaded) { return '(no such article)'; }
	if (false == $user->authHas('wiki', 'Wiki_Article', 'show', $model->UID)) { return ''; }
	//TODO: more permission options

	if ($num < 1) { $num = 1; }
	if ($pageNo < 1) { $pageNo = 1; }

	//----------------------------------------------------------------------------------------------
	//	count all revisions to this article
	//----------------------------------------------------------------------------------------------

	$conditions = array();
	$conditions[] = "articleUID='" . $db->addMarkup($model->UID) . "'";
	//TODO: add any other conditions here (namespace, etc)

	$totalItems = $db->countRange('Wiki_Revision', $conditions);
	$totalPages = ceil($totalItems / $num);
	if ($pageNo > $totalPages) { $pageNo = $totalPages; }
	$start = (($pageNo - 1) * $num);

	//$sql = "select count(UID) as revcount "
	//	 . "from Wiki_Revisions where refUID='" . sqlMarkup($args['UID']) . "'";

	//----------------------------------------------------------------------------------------------
	//	load a page of results from the database and make the block
	//----------------------------------------------------------------------------------------------

	$range = $db->loadRange('Wiki_Revision', '*', $conditions, 'editedOn DESC', $num, $start);
	$block = $theme->loadBlock('modules/wiki/views/revisionsummarynav.block.php');

	//	$sql = "select * from Wiki_Revisions "
	//		 . "where refUID='" . sqlMarkup($args['UID']) . "' "
	//		 . "order by editedOn DESC limit $num";

	foreach ($range as $row) {
		$model = new Wiki_Revision();
		$model->loadArray($row);
		$ext = $model->extArray();
		if ('' == trim($ext['reason'])) { $ext['reason'] = "<i>(no reason given)</i>"; }
		$html .= $theme->replaceLabels($ext, $block);
	}

	//----------------------------------------------------------------------------------------------
	//	add meta and navigation elements
	//----------------------------------------------------------------------------------------------
	//$linkAll = "<a href='%%serverPath%%wiki/history/" . $model->articleUID . "'>[see all]</a>";
	//$html .= "<b>Total revisions:</b> $totalItems<br/>\n";

	return $html;
}

//--------------------------------------------------------------------------------------------------

?>
