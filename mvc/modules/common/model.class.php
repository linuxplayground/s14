<?php
/*
* This is an important class.  It is the BASE MODEL class that all other
* action classes are extended from.
*/
Log::getInstance()->write (__FILE__." loaded", "debug" );

class Model {
	
	public function __construct ( ) {
		//No construct
	}
	
	protected function insert_error($msg, $text, $result) {
		$msg->data['error'] = $text;
		$msg->result = $result;
		return $msg;
	}
	
	protected function validateUser($oMsg) {
		/*
			Function that checks with the database for a user's permission to access an action.
			Find the action name in the message
			Find the user id in the session.
		*/
		
		
		if (!isset($_SESSION['token']) ) {
			//No user is logged in.  This app requires users to be logged in for everything.
			$oMsg->message = "User is not logged in.";
			$oMsg->result = "user_not_logged_in";
			return false;
		} else {
			$userId = $_SESSION['token'];
			settype($userId, "integer");
			
			$sql = sprintf("
			SELECT * from auth_permission p, auth_user_group ug, auth_action a
			where p.p_group_id = ug.ug_group_id
			and ug.ug_user_id = %d
			and p.p_action_id = a.action_id
			and a.action_name = '%s';
			", $userId, $oMsg->action);

			$rs = $oMsg->conn->query($sql);
			if(PEAR::isError($rs) ) {
				Log::getInstance()->write("Could not query database for user permission to access action: ".$oMsg->action, "error");
				$this->insert_error($oMsg, "Could not query database for user permissions", "error");
				return false;
			} else {
				if ($rs->numRows( ) > 0 ) {
					//We have a result
					return true;
				} else {
					//We do not have a match.  Permission denied
					Log::getInstance()->write("User does not have permission to access action: ".$oMsg->action, "error");
					$this->insert_error($oMsg, "User does not have permission to access action: ".$oMsg->action, "error");
					return false;
				}
			}
		}
	}
}

?>