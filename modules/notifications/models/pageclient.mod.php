<?

//--------------------------------------------------------------------------------------------------
//*	object for revieving page notifications
//--------------------------------------------------------------------------------------------------
//+	when a page subscribes to a notification channel, the channel ID is stored in [channels]
//+	when a page checks its inbox, its timestamp is updated
//+	if a timestamp has not been updated in over 5 minutes, the record is killed
//+
//+	messages are stored one per line and consist of base64_encoded strings separated by pipes
//+	eg: channelID|event|data
//+
//+	what the data is and how it is formatted is up to the module which sent the notification
//+	timestamp is not updated every thime the inbox is checked, only if 3 minutes since last updated

require_once($installPath . 'modules/notifications/models/pagechannel.mod.php');

class PageClient {

	//----------------------------------------------------------------------------------------------
	//	member variables (as retieved from database)
	//----------------------------------------------------------------------------------------------

	var $data;				// currently loaded record [array]
	var $dbSchema;			// database structure [array]
	var $old = false;		// is this old [bool]
	var $oldTime = 180;		// number of seconds at which page is considered old [int]

	//----------------------------------------------------------------------------------------------
	//.	constructor
	//----------------------------------------------------------------------------------------------
	//opt: pageUID - UID of a client page's inbox [string]

	function PageClient($pageUID = '') {
		$this->dbSchema = $this->initDbSchema();
		$this->data = dbBlank($this->dbSchema);
		$this->data['timestamp'] = time();
		if ('' != $pageUID) { $this->load($pageUID); }
	}

	//----------------------------------------------------------------------------------------------
	//.	load a record by pageUID, create it if it does not yet exist
	//----------------------------------------------------------------------------------------------
	//arg: UID - UID of a client page's inbox [string]

	function load($UID) {
		if (dbRecordExists('pageclients', $UID) == false) {
			// object does not exist, create it
			$this->data['UID'] = $UID;
			$this->save();
		} else {
			// load it
			$ary = dbLoad('pageclients', $UID);
			$this->loadArray($ary);
		}
		return true;
	}

	//----------------------------------------------------------------------------------------------
	//.	load a record provided as an associative array
	//----------------------------------------------------------------------------------------------
	//arg: ary - associative array of fields and values [array]

	function loadArray($ary) { 
		$this->data = $ary; 
		$this->old = ((time() - $this->data['timestamp']) > $this->oldTime);
	}

	//----------------------------------------------------------------------------------------------
	//.	save the current record
	//----------------------------------------------------------------------------------------------

	function save() {
		$verify = $this->verify();
		if ($verify != '') { return $verify; }
		dbSave($this->data, $this->dbSchema); 
	}

	//----------------------------------------------------------------------------------------------
	//.	verify - check that a record is correct before allowing it to be stored in the database
	//----------------------------------------------------------------------------------------------
	//,	nothing to check as yet

	function verify() { return ''; }

	//----------------------------------------------------------------------------------------------
	//.	sql information
	//----------------------------------------------------------------------------------------------
	//returns: database table layout [array]

	function initDbSchema() {
		$dbSchema = array();
		$dbSchema['table'] = 'pageclients';
		$dbSchema['fields'] = array(
			'UID' => 'VARCHAR(30)',	
			'channels' => 'TEXT',	
			'inbox' => 'TEXT',
			'timestamp' => 'VARCHAR(20)'
			 );

		$dbSchema['indices'] = array('UID' => '10', 'timestamp' => '8');
		$dbSchema['nodiff'] = array('UID', 'channels', 'inbox', 'timestamp');
		return $dbSchema;
	}

	//----------------------------------------------------------------------------------------------
	//.	serialize this object to an array
	//----------------------------------------------------------------------------------------------
	//returns: associative array of all variables which define this instance [array]

	function toArray() { return $this->data; }

	//----------------------------------------------------------------------------------------------
	//.	make array of messages, ordered by time
	//----------------------------------------------------------------------------------------------
	//,	no use for this as yet

	function extArray() {
		// TODO
	}

	//----------------------------------------------------------------------------------------------
	//.	install this module
	//----------------------------------------------------------------------------------------------
	//returns: html report lines [string]
	//, deprecated, this should be handled by ../inc/install.inc.inc.php

	function install() {
		$report = "<h3>Installing PageClients Table</h3>\n";

		//------------------------------------------------------------------------------------------
		//	create notices table if it does not exist
		//------------------------------------------------------------------------------------------

		if (dbTableExists('pageclients') == false) {	
			dbCreateTable($this->dbSchema);	
			$this->report .= 'created pageclients table and indices...<br/>';
		} else {
			$this->report .= 'pageclients table already exists...<br/>';	
		}

		return $report;
	}
	
	//----------------------------------------------------------------------------------------------
	//.	delete the current record
	//----------------------------------------------------------------------------------------------

	function delete() {
		//------------------------------------------------------------------------------------------
		//	remove from all channels
		//------------------------------------------------------------------------------------------
		$channels = explode("\n", $this->data['channels']);
		foreach($channels as $channelID) {
			$channel = new PageChannel($channelID);
			$channel->removeClient($this->data['UID']);
		}

		//------------------------------------------------------------------------------------------
		//	remove record
		//------------------------------------------------------------------------------------------
		dbDelete('pageclients', $this->data['UID']);
	}

	//----------------------------------------------------------------------------------------------
	//.	subscribe this page to a channel
	//----------------------------------------------------------------------------------------------	
	//arg: channelID - ID of a page channel [string]	

	function subscribe($channelID) {
		//echo "#pageclient->data['UID'] = '" . $this->data['UID'] . "'\n";
		//echo "#pageclient->subscribe('" . $channelID . "')\n";
		//------------------------------------------------------------------------------------------
		//	let the channel know
		//------------------------------------------------------------------------------------------
		$channel = new PageChannel($channelID);
		$channel->addClient($this->data['UID']);

		//------------------------------------------------------------------------------------------
		//	check if already subscribed to this channel
		//------------------------------------------------------------------------------------------
		$found = false;
		$channels = explode("\n", $this->data['channels']);
		foreach($channels as $channel) { if ($channel == $channelID) { $found = true; }	}

		if (false == $found) {
			$this->data['channels'] .= $channelID . "\n";
			$this->save();	
		} 
		return !$found;
	}

	//----------------------------------------------------------------------------------------------
	//.	update timestamp
	//----------------------------------------------------------------------------------------------	

	function updateTimeStamp() {
		$this->data['timestamp'] = time();
		$this->save();
	}

	//----------------------------------------------------------------------------------------------
	//.	add message to this page's inbox
	//----------------------------------------------------------------------------------------------	
	//arg: channel - ID of a page channel [string]
	//arg: event - event type [string]
	//arg: msg - details of event [string]

	function addMessage($channel, $event, $msg) {
		$this->data['inbox'] .= $channel . '|' . $event . '|' . $msg . "\n";
		$this->save();
	}

	//----------------------------------------------------------------------------------------------
	//.	destroy any pageclients which are timed out
	//----------------------------------------------------------------------------------------------	

	function bringOutYourDead() {
		// construct a timestamp which was 10 minutes ago
		$minAge = (time() - (60 * 10));
		$sql = "select UID from pageclients where timestamp < $minAge ";
		//TODO: dbLoadRange
		$result = dbQuery($sql);
		while ($row = dbFetchAssoc($result)) {
			$row = sqlRMArray($row);
			$model = new PageClient($row['UID']);
			$model->delete();
			//echo "removed dead PageClient: " . $row['UID'] . "<br/>\n";
		}
	}

}

?>
