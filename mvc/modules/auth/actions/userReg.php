<?php
/*
	All action methods for Authorisation in this model.
	
*/
require_once ("modules/common/model.class.php");
Log::getInstance()->write (__FILE__." loaded", "debug");

class UserRegAction extends Model {
	
	public function __construct ( ) {
		//Empty construct
	}
	
	/**
	 * Generate a call to the Register As New User form.
	 *
	 * @param Message $oMsg
	 */
	public function newRegistrationForm ( Message $oMsg ) {
		if ( !isset( $_SESSION['token'] ) ) {
			$oMsg->data['xml'] = "<form>";
			if ( ! isset( $oMsg->message ) ) {
				$oMsg->data['xml'] .= "<message>Register New User</message>";
			}
//Debug::getInstance()->showStack($oMsg->data['postData'],"Form values.");
			if ( isset ( $oMsg->data['postData'] ) ) {
				foreach ($oMsg->data['postData'] as $key => $value ) {
					$oMsg->data['xml'] .= "<".$key.">".$value."</".$key.">";
				}
			}
			
			if ( isset ( $oMsg->data['errorMsg'] ) ) {
				foreach ( $oMsg->data['errorMsg'] as $key => $value ) {
					$oMsg->data['xml'] .= "<errorMsg>".$value."</errorMsg>";
				}
			}
			
			$oMsg->data['xml'] .= "</form>";
			
			$oMsg->result = "showNewUserRegistration";
		} else {
			return $this->insert_error( $oMsg, "You are already logged in as. ".$_SESSION['token'], "error");
		}
	}
	
	/**
	 * Insert new user into database.  Checks for existing details first and returns an error object if there are problems.
	 *
	 * @param Message $oMsg
	 */
	public function insertNewUser ( Message $oMsg ) {
		
		$error = false;
		$errorMsg = array();
		
		//Check that each field is filled in.
		foreach ($oMsg->request as $key => $value) {
			if ( $value == "" ) {
				$error = true;
				$errorMsg[] = "You have not filled in a value for ".$key;
			}
		}
		
		//Check that username does not already exist in database.
		//Usernames must be stored all in lower case.  Even if the user entered in an uppercase value.
		$sql = sprintf("select user_name from auth_user where user_name = '%s'", strtolower($oMsg->request['regUserName']));
		$rs = $oMsg->conn->query($sql);
		if (PEAR::isError($rs)) {
			Log::getInstance()->write("User Registration Error : Checking uniqe Username : ".mysql_error(), "error");
			return $this->insert_error($oMsg, "An error has occurred.  Probably fatal.  Please contact the administrator.  ERROR: REGISTRATION ERROR CHECKING UNIQE USERNAME", "error");
		} else {
			if ($rs->numRows() > 0) {
				$error = true;
				$errorMsg[] = "The username you selected has already been picked.";
			}
		}
		
		//Check that passwords match.
		$p1 = $oMsg->request['regPassword1'];
		$p2 = $oMsg->request['regPassword2'];
		
		if ($p1 != $p2) {
			$error = true;
			$errorMsg[] = "You have not entered in the same password twice.";
			
		}
		
		//Check that user firstname + lastname has not registered before.
		$sql = sprintf("select * from auth_user where user_first_name = '%s' and user_last_name='%s'",
			$oMsg->request['regFirstName'], $oMsg->request['regLastName']);
		$rs = $oMsg->conn->query($sql);
		if (PEAR::isError($rs)) { 
			Log::getInstance()->write("User Registration error ; Checking first and last name combination : ".mysql_error(), "error");
			return $this->insert_error($oMsg, "User Registration error.  Probably fatal.  Contact your system admin.", "error");
		} else {
			if ( $rs->numRows() > 0 ) {
				$error = true;
				$errorMsg[] = "A user with your first AND last name already registered.";
			}
		}
		
		//Check format of email address.
		$e = $oMsg->request['regEmailAddress'];
		if (!preg_match("/^[a-z0-9._-]+@[a-z0-9._-]+\.([a-z]{2,4})($|\.([a-z]{2,4})$)/i" , $e)) {
  			$error = true;
  			$errorMsg[] = "Your email address seems to be invalid in some way.  Check that it is formatted correctly.  Otherwise let the administrator know about it.";
 		}

 		if ($error == true) {
 			$oMsg->result = "insertFail";
 			$oMsg->data['errorMsg'] = $errorMsg;
 			$oMsg->data['postData'] = $oMsg->request;
 		} else {
 			$sql = sprintf("insert into auth_user (user_name, user_pass, user_first_name, user_last_name, user_email) values('%s', '%s', '%s', '%s', '%s' )",
 				$oMsg->request['regUserName'],
 				$oMsg->request['regPassword2'],
 				$oMsg->request['regFirstName'],
 				$oMsg->request['regLastName'],
 				$oMsg->request['regEmailAddress']);
 			$rs = $oMsg->conn->exec($sql);
 			if (PEAR::isError($rs)) {
 				Log::getInstance()->write("Error inserting user details into database. ".mysql_error(), "error");
 				return $this->insert_error($oMsg,"Error inserting user data into database. Please let the administrator know.", "error");	
 			}
 			$id = $oMsg->conn->lastInsertId("auth_user");
 			$sql = sprintf("insert into auth_user_group (ug_user_id, ug_group_id) values ('%s', (select group_id from auth_group where group_name = 'Users') )", $id);
 			$rs = $oMsg->conn->exec ( $sql );
 		 	if (PEAR::isError($rs)) {
 				Log::getInstance()->write("Error inserting user details into database. ".mysql_error(), "error");
 				return $this->insert_error($oMsg,"Error inserting user data into database. Please let the administrator know.", "error");	
 			}
 			
			$oMsg->result = "insertSuccess";
			$oMsg->message = "Thank you ".$oMsg->request['regFistName']. " your details have been added to the system.  Please log in now.";

 		}
	}
}
?>