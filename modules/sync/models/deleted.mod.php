<?

//--------------------------------------------------------------------------------------------------
//*	object for recording deleted items, so that they are not respawned by the sync
//--------------------------------------------------------------------------------------------------
//+	note that file/image deletions are also stored in this table, with 'refTable' set to
//+	'localfile', and the refUID to relative filename.

class DeletedItem {

	//----------------------------------------------------------------------------------------------
	//	member variables (as retieved from database)
	//----------------------------------------------------------------------------------------------

	var $data;			// currently loaded record [array]
	var $dbSchema;		// database structure [array]

	//----------------------------------------------------------------------------------------------
	//.	constructor
	//----------------------------------------------------------------------------------------------
	//opt: UID - UID of object recording deleted item (not the UID of the item itself) [string]

	function DeletedItem($UID = '') {
		$this->dbSchema = $this->initDbSchema();
		$this->data = dbBlank($this->dbSchema);
		$this->data['UID'] = createUID();
		if ($UID != '') { $this->load($UID); }
	}

	//----------------------------------------------------------------------------------------------
	//.	load an object given its UID
	//----------------------------------------------------------------------------------------------
	//arg: UID - UID of obect recording deleted item (not the UID of the item itself) [string]

	function load($UID) {
		$ary = dbLoad('delitems', $UID, 'true');
		if ($ary == false) { return false; }
		$this->data = $ary;
		return true;
	}

	//----------------------------------------------------------------------------------------------
	//.	load an object provided as an associative array
	//----------------------------------------------------------------------------------------------
	//arg: ary - associative array of fields and values [array]

	function loadArray($ary) { $this->data = $ary; }

	//----------------------------------------------------------------------------------------------
	//.	save the current object to database
	//----------------------------------------------------------------------------------------------

	function save() {
		$verify = $this->verify();
		if ($verify != '') { return $verify; }

		$d = $this->data;
		dbSave($this->data, $this->dbSchema);	 // consider alternate save mechanism
	}

	//----------------------------------------------------------------------------------------------
	//.	verify - check that a object is valid before allowing it to be stored in the database
	//----------------------------------------------------------------------------------------------
	//returns: null string if object passes, warning message if not [string]

	function verify() {
		$verify = '';
		$d = $this->data;

		if (strlen($d['UID']) < 5) 
			{ $verify .= "UID not present.\n"; }

		return $verify;
	}

	//----------------------------------------------------------------------------------------------
	//.	sql information
	//----------------------------------------------------------------------------------------------
	//returns: database table layout [array]

	function initDbSchema() {
		$dbSchema = array();
		$dbSchema['table'] = 'delitems';
		$dbSchema['fields'] = array(
			'UID' => 'VARCHAR(30)',
			'refTable' => 'VARCHAR(50)',
			'refUID' => 'VARCHAR(255)',	
			'detail' => 'TEXT',
			'timestamp' => 'VARCHAR(20)'
		);

		$dbSchema['indices'] = array('UID' => '10', 'refUID' => '10', 'refTable' => '6');
		$dbSchema['nodiff'] = array('UID', 'table', 'refUID', 'timestamp');
		return $dbSchema;
	}

	//----------------------------------------------------------------------------------------------
	//.	serialize this object as an array
	//----------------------------------------------------------------------------------------------
	//returns: associative array of all variables which define this instance [array]

	function toArray() { return $this->data; }

	//----------------------------------------------------------------------------------------------
	//.	make an extended array of all data a view will need
	//----------------------------------------------------------------------------------------------
	//returns: extended array of member variables and metadata [array]

	function extArray() {
		$ary = $this->data;			
		return $ary;
	}

	//----------------------------------------------------------------------------------------------
	//.	install this module
	//----------------------------------------------------------------------------------------------
	//returns: html report lines [string]
	//, deprecated, this should be handled by ../inc/install.inc.php

	function install() {
		$report = "<h3>Installing Deleted Items Table</h3>\n";

		//------------------------------------------------------------------------------------------
		//	create blog table if it does not exist
		//------------------------------------------------------------------------------------------
		if (dbTableExists('delitems') == false) {	
			dbCreateTable($this->dbSchema);	
			$this->report .= 'created deleted items table and indices...<br/>';
		} else {
			$this->report .= 'deleted items table already exists...<br/>';	
		}

		return $report;
	}

	//----------------------------------------------------------------------------------------------
	//.	delete the current object from the database
	//----------------------------------------------------------------------------------------------

	function delete() {
		dbDelete('delitems', $this->data['UID']);
	}
	
}
?>
