<?

	require_once($kapenta->installPath . 'modules/calendar/models/entry.mod.php');

//--------------------------------------------------------------------------------------------------
//|	menu for calendar, no arguments
//--------------------------------------------------------------------------------------------------

function calendar_menu($args) {
	global $theme, $user;

	$labels = array();
	$now = time();

	$labels['thisYear'] = 'year_' . date('Y');
	$labels['thisMonth'] = 'month_' . date('Y') . '_' . date('m');
	$labels['today'] = 'day_' . date('Y') . '_' . date('m') . '_' . date('j');
	
	$labels['nextMonth'] = 'month_' . date('Y') . '_' . (date('m') + 1);
	if ((date('m') + 1) > 12) { $labels['nextMonth'] = 'month_' . (date('Y') + 1) . '_01';	}

	//TODO: fix up permissions check for editing calendar items, add submenu item for it
	$labels['newEntry'] = '[[:theme::submenu::label=Add Calendar Entry::link=/calendar/new/:]]';
	if (false == $user->authHas('calendar', 'Calendar_Entry', 'new')) { $labels['newEntry'] = ''; }
	
	$block = $theme->loadBlock('modules/calendar/views/menu.block.php');
	$html = $theme->replaceLabels($labels, $block);

	return $html;	
}

//--------------------------------------------------------------------------------------------------

?>