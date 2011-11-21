<?

	require_once($kapenta->installPath . 'modules/users/models/user.mod.php');

//--------------------------------------------------------------------------------------------------
//*	creates a web shell iframe
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check reference and permissions
	//----------------------------------------------------------------------------------------------
	//if ('public' == $user->role) { $page->do403('Please log in to use the shell.', true); }	

	//----------------------------------------------------------------------------------------------
	//	render the page  //TODO: make a generic window template
	//----------------------------------------------------------------------------------------------

	$raw = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<head>
<title>:: kapenta :: live :: shell</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<link href='" . $kapenta->serverPath . "themes/clockface/css/windows.css' rel='stylesheet' type='text/css' />
<script src='" . $kapenta->serverPath . "core/utils.js'></script>
<script src='" . $kapenta->serverPath . "modules/live/js/shellwindow.js'></script>
<style type='text/css'>

body {
	padding: 0;
	margin: 0;
	background: #eeeeee;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: smaller;
	color: #303030;
}

a { text-decoration: none; color: #365a10 }

a h1 {
	color: #303030;
}

a h2 {
	color: #303030;
}

.style1 {font-size: 9px}
</style>

<script src='" . $kapenta->serverPath . "core/utils.js'></script>
<script language='javascript'>

	jsServerPath = '" . $kapenta->serverPath . "';
	jsUserUID = '" . $user->UID . "';
	jsUserName = \"" . $user->getName() . "\";
	
	windowUID = '';
	windowIdx = 0;

	kshellwindow = 0;

	//----------------------------------------------------------------------------------------------
	//	onLoad
	//----------------------------------------------------------------------------------------------

	function kPageInit() {
		//------------------------------------------------------------------------------------------
		//	create shell window object
		//------------------------------------------------------------------------------------------
		windowUID = window.name.replace('ifc', '');
		windowIdx = window.parent.kwindowmanager.getIndex(windowUID)

		kshellwindow = new Live_ShellWindow(
			jsServerPath, 
			jsUserName, 
			windowIdx
		);

		//TODO: figure out how to get rid of this
		//kshellwindow.taPrompt.onkeyup = kshellwindow.taKeyUp;

		//------------------------------------------------------------------------------------------
		//	register events (TODO: should ony be registered during drag)
		//------------------------------------------------------------------------------------------
		document.onmousemove = function(e) {
			var wndThis = window.parent.kwindowmanager.windows[windowIdx];
			var ifThis = window.parent.document.getElementsByName(window.name);	//%	this iframe
			var txtBox = document.getElementById('content');					//% textarea

			docX = e.clientX + ifThis[0].offsetLeft + wndThis.left;
			docY = e.clientY + ifThis[0].offsetTop + wndThis.top;

			// only these properties neede by the window manager
			var newEvt = function() { 
				this.clientX = 0;	
				this.clientY = 0; 
			}

			newEvt.clientX = docX;
			newEvt.clientY = docY;

			//txtBox.value = (docX) + ', ' + (docY) + ' -- ' + wndThis.top + ', ' + wndThis.left;
			window.parent.kmouse.onMouseMove(newEvt);
		}

		//------------------------------------------------------------------------------------------
		//	resize controls
		//------------------------------------------------------------------------------------------
		resizeWindow();
	}

	//----------------------------------------------------------------------------------------------
	//	re/scale controls to fit inside iFrame
	//----------------------------------------------------------------------------------------------

	function resizeWindow() {
		//------------------------------------------------------------------------------------------
		//	resize controls
		//------------------------------------------------------------------------------------------
		var divCPS = document.getElementById('divSessionSummary');			//%	div
		var divH = document.getElementById('divHistory');					//%	div
		var txtBox = document.getElementById('content');					//% textarea
		
		txtBox.cHeight = extractNumberCW(txtBox.style.height)
		txtBox.style.top = (window.innerHeight - txtBox.cHeight - 8) + 'px';
		txtBox.style.width = (window.innerWidth - 6) + 'px';

		divH.style.height = (window.innerHeight - divCPS.clientHeight - txtBox.cHeight - 25) + 'px';
		divH.style.width = (window.innerWidth - 6) + 'px';
	}

	//----------------------------------------------------------------------------------------------
	//	remove 'px' from numbers //TODO: add this to /core/utils.js
	//----------------------------------------------------------------------------------------------
	
	function extractNumberCW(value) { 
		var n = parseInt(value); 
		return n == null || isNaN(n) ? 0 : n; 
	} 

</script>

</head>

<body onLoad='kPageInit();' onResize='resizeWindow();'> 
<div id='divSessionSummary' style='background-color: #ffffff;'>

</div>
<hr/>
<div id='divHistory' 
	style='width: 200px; height: 60px; position: absolute; overflow: auto;' >
<div id='histWelcome' class='chatmessagered'>
<b>Kapenta Web Shell v0.0 alpha</b><br/>
Type <tt>live.help</tt> for more a list of available commands and utilities.<br/>
</div>
</div>

<textarea name='content' id='content'
	style='width: 200px; height: 60px; left: 0px; top: 100px; position:absolute;'></textarea><br/>
</body>
</html>";

	$raw = $theme->expandBlocks($raw, '');
	echo $raw;

?>