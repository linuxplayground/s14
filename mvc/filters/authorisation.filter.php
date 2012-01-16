<?php

Log::getInstance()->write(__FILE__." loaded.", "debug");

class AuthorisationFilter implements Filter {
	private $action;
	
	public function execute( $oMsg ) {
		if( array_key_exists("requestFilter", $oMsg->request) ) {
			$oMsg->filter = $oMsg->request['requestFilter'];
		} else {
			$oMsg->filter = "verify";
		}
		$this->initialiseVerification( $oMsg->filter );
		$method = $this->action;
		return $this->$method($oMsg);
	}
	
	public function getError( ) {
		return "There has been an authorisation error.  Please contact your system administrator.";
	}
	
	public function initialiseVerification( $name ) {
		//Sessions expire after 5 minutes.
		//session_cache_expire(5);
		session_start();
		switch ($name) {
			case "logIn":
				$this->action = "login";
				break;
			case "logOut":
				$this->action = "logout";
				break;
			default:
				$this->action = "verify";
				break;
		}
	}
	
	private function setSession( $key, $value ) {
		$_SESSION[$key] = $value;
	}
	
	private function getSession ($key) {
		return $_SESSION[$key];
	}
	
	public function verify( $oMsg ) {
		if( isset( $_SESSION['token'] ) ) {

  			/* removed this feature to prevent confusion.
			if (! isset ($_SESSION['stamp'] ) ) {
  				$_SESSION['stamp'] = time();
  			}

			$sessTime = $_SESSION['stamp'];
			$curTime = time();
			$diffTime = $curTime - $sessTime;
			
			if ( $diffTime > 360 ) {
				unset ( $_SESSION['token']);
				unset ( $_SESSION['userName']);
				unset ( $_SESSION['stamp']);
				$_SESSION = array();
				
				Log::getInstance()->write("not verified - Session expired.", "error");
				$oMsg->action="logInForm";
				$oMsg->module="auth";
				$oMsg->message="Your session has expired due to inactivity. Please log in to continue";
				return true;
			} else {
				$_SESSION['stamp'] = $curTime;
			}
			*/
			return true;
		} else {
			switch ( $oMsg->action ) {
				//Allow the following cases (actions) to proceed without verfication.
				//Default is all items require a logged in user.
				case "userRegistration":
				case "insertNewUser":
				case "display_news":
				case "default":
				case "leaderboard":
					//Allow a user to register without first being logged in.
					Log::getInstance()->write("filter bypass: ".$oMsg->action, "debug");
					return true;
					break;
				default:
					break;
			}
			Log::getInstance()->write("not verified - no session.", "debug");
			$oMsg->action="logInForm";
			$oMsg->module="auth";
			$oMsg->message="Please log in to continue";
			return true;
		}
	}
	
	public function logout( $oMsg ) {
		$oMsg->message = "Logged out";
		$userName = $_SESSION['userName'];
		unset($_SESSION['token']);
		unset($_SESSION['userName']);
		$_SESSION = array();
		session_destroy();
		Log::getInstance()->write(sprintf("Session destroyed. %s logged out.", $userName), "info");
		$oMsg->action="logInForm";
		$oMsg->module="auth";
		$oMsg->message="You have logged out successfully.";
		return true;
	}

	public function login( $oMsg ) {
		
		if ( isset ($_SESSION['token']) ) {
			$oMsg->message = "Logged in already.";
			return true;
		} else {
			$sql = sprintf( "SELECT * FROM auth_user WHERE user_name = '%s' AND user_pass = '%s'",
				$oMsg->request['loginUserName'], $oMsg->request['loginUserPass'] );
			$rs = $oMsg->conn->query( $sql );
			if (PEAR::isError ( $rs ) ) {
				Log::getInstance()->write("Could not perform query on database ".mysql_error(), "error");
				//$this->insert_error( $oMsg, "Could not perform username and password query on database.<br />".$rs->getMessage()."<br />".$sql."<br/>".mysql_error(), "login_failed" );
			} else {
				if ( $rs->numRows( ) > 0 ) {
					$row = $rs->fetchRow( MDB2_FETCHMODE_ASSOC );
					$this->setSession( 'token', $row['user_id'] );
					$this->setSession('userName', $row['user_name']);
					if(array_key_exists("systemUser", $oMsg->request) ) {
						$this->setSession('systemUser', 'true');
					}
					$oMsg->message = "Welcome Back ".$row['user_first_name'];
					Log::getInstance()->write($row['user_name']." Logged in succesfully.", "info");
					return true;
				} else {
					$oMsg->message = "Log in failed: No user / password in database.";
					Log::getInstance()->write("Login failed: No user / password in database.", "debug");
					$oMsg->action="logInForm";
					$oMsg->module="auth";
					return true;
				}
			}
		}
	}
}
?>