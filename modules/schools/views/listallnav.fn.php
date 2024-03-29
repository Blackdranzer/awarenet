<?

	require_once($kapenta->installPath . 'modules/schools/models/school.mod.php');

//--------------------------------------------------------------------------------------------------
//|	list all formatted for nav (300 px wide)
//--------------------------------------------------------------------------------------------------
//arg: hidden - show hidden schools (yes|no) [string]

function schools_listallnav($args) {
		global $kapenta;
		global $kapenta;

	$html = '';

	//$sql = "select * from Schools_School order by name";
	$conditions = array("(hidden='no' OR hidden='')");

	if ((true == array_key_exists('hidden', $args)) && ('yes' == $args['hidden']))
		{ $conditions = ''; }

	$range = $kapenta->db->loadRange('schools_school', '*', $conditions, 'name');

	foreach($range as $row) 
		{ $html .= "[[:schools::summarynav::schoolUID=" . $row['UID'] . ":]]"; }

	return $html;
}


//--------------------------------------------------------------------------------------------------

?>
