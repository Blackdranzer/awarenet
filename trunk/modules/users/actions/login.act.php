<?

//--------------------------------------------------------------------------------------------------
//*	login form
//--------------------------------------------------------------------------------------------------
//+	TODO: make the landing page after login a setting which admins can change

	//----------------------------------------------------------------------------------------------
	//	handle submissions
	//----------------------------------------------------------------------------------------------

	if ((array_key_exists('action', $_POST)) AND ($_POST['action'] == 'login')) {

		//------------------------------------------------------------------------------------------
		//	authenticate the user
		//------------------------------------------------------------------------------------------
		$username = $db->addMarkup($_POST['user']);
		$password = $db->addMarkup($_POST['pass']);

		$range = $db->loadRange('users_user', '*', array("username='" . $username . "'"));
		// ^ "select * from Users_User where username='" . $username . "'";

		if (count($range) > 0) {
			$row = array_shift($range);									//%	first row [array]
			if ($row['password'] == sha1($password . $row['UID'])) {

				if ($row['role'] == 'banned') {
					//------------------------------------------------------------------------------
					//	details correct, but user is banhammered
					//------------------------------------------------------------------------------
					$session->msg('You have been banned, you are not logged in.', 'no');
					$page->do302(''); 									// redirect to homepage

				} else {

					//------------------------------------------------------------------------------
					//	feedback and redirect
					//------------------------------------------------------------------------------


					//$msg = "[[:theme::navtitlebox::label=Notice:]]"
					//	 . "<div class='inlinequote'><p>"
					//	 . "<img src='%%serverPath%%themes/clockface/images/info.png' "
					//	 . "class='infobutton' width='18' height='18' />&nbsp;&nbsp;"
					//	 . "You are now logged in.</p></div>\n";


					$session->user = $row['UID'];						//	set current user UID
					$session->store();									//	and save
					$session->msg('You are now logged in.', 'ok');

					$user->loadArray($row);

					if (true == array_key_exists('redirect', $_POST)) { 
						$page->do302($_POST['redirect']); 				// retry action after login
					} else {
						$user->lastOnline = $db->datetime();
						$user->save();
						$page->do302('notifications/'); 				// default landing page
					}

				}

			}
		}

		//------------------------------------------------------------------------------------------
		//	cound not authenticate
		//------------------------------------------------------------------------------------------
		
		//$msg = "[[:theme::navtitlebox::label=Notice:]]"
		//	 . "<div class='inlinequote'><p>"
		//	 . "<img src='%%serverPath%%themes/clockface/images/btn-del.png' "
		//	 . "class='infobutton' width='18' height='18' />&nbsp;&nbsp;"
		//	 . "</div>\n";

		$session->msg('Username or password not recognised, you are not logged in.', 'no');
								

	}

//--------------------------------------------------------------------------------------------------
//	display form
//--------------------------------------------------------------------------------------------------

	$page->load('modules/users/actions/login.page.php');
	$page->render();

?>