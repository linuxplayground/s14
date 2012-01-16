<?php
/*
	All action methods for Authorisation in this model.
	
*/
require_once ("modules/common/model.class.php");
Log::getInstance()->write (__FILE__." loaded", "debug");

class UserAction extends Model {

	public function __construct ( ) {
		//
	}
	
	public function newUser( $oMsg ) {
		if($this->validateUser( $oMsg ) ) {
			$oMsg->data['newUserFormXml'] = "
				<form>
					<label>Add User</label>
					<action>index.php?action=insertUser&amp;module=user</action>
					<method>POST</method>
					<input>
						<type>text</type>
						<name>addUserUsername</name>
						<label>Username</label>
					</input>
					<input>
						<type>password</type>
						<name>addUserPassword</name>
						<label>Password</label>
					</input>
					<input>
						<type>text</type>
						<name>addUserFirstName</name>
						<label>First Name</label>
					</input>
					<input>
						<type>text</type>
						<name>addUserLastName</name>
						<label>Last Name</label>
					</input>
					<submit>
						<name>addUserSubmit</name>
						<value>Add...</value>
					</submit>
				</form>
			";
			
			$oMsg->result = 'display_new_user_form';
		}
	}
	
	public function insertUser( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			$sql = sprintf("insert into auth_user (user_name, user_pass, user_first_name, user_last_name) values ('%s', '%s', '%s', '%s')",
				$oMsg->request['addUserUsername'], $oMsg->request['addUserPassword'],
				$oMsg->request['addUserFirstName'], $oMsg->request['addUserLastName']);
			$rs = $oMsg->conn->exec($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write ("error inserting data into database ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
				return $this->insert_error($oMsg, "<b>Framework Error:</b> error inserting data into database <br /><b> ".$rs->getMessage()." </b><br/><b>MySQL Error:</b> ".mysql_error()." <br/><b>SQL Statement:</b> ".$sql, "insert_data_error");
			} else {
				$oMsg->message = $rs. " rows inserted successfully.";
				$oMsg->result = 'insert_data_success';
			}
		}
	}
	
	public function listUser( $oMsg ) {
		
		if($this->validateUser($oMsg)) {
			if (isset ($oMsg->request['o'] ) ) {
				switch ($oMsg->request['o']) {
					case "firstname":
						$order = "user_first_name";
						$orderName = "First Name";
						break;
					case "lastname":
						$order = "user_last_name";
						$orderName = "Last Name";
						break;
					case "username":
						$order = "user_name";
						$orderName = "Username";
						break;
					default:
						$order = "user_name";
						$orderName = "Username";
				}
			} else {
				$order = "user_name";
				$orderName = "Username";
			}
			$sql = sprintf("SELECT * FROM auth_user ORDER BY '%s' ASC", $order);
			$rs = $oMsg->conn->query($sql);
			
			if (PEAR::isError ($rs) ) {
				Log::getInstance()->write ("error querying database ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
				return $this->insert_error($oMsg, "<b>Framework Error:</b> error querying database <br /><b> ".$rs->getMessage()." </b><br/><b>MySQL Error:</b> ".mysql_error()." <br/><b>SQL Statement:</b> ".$sql, "error");
			} else {
			
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$libxml->push('page');
				if (! isset( $oMsg->message ) ) {
					$oMsg->message = 'Users listed by '.$orderName. ' ascending.';
				}
				$libxml->element('message', $oMsg->message );
				
				while($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
					$libxml->push( 'user' );
						$libxml->element('id', $row['user_id'] );
						$libxml->element('username', $row['user_name'] );
						$libxml->element('firstname', $row['user_first_name'] );
						$libxml->element('lastname', $row['user_last_name'] );
					$libxml->pop();
				}
				$libxml->pop();
			}
			$oMsg->data['listUser']['xml'] = $libxml->getXml();
			$oMsg->result = "display_user_list";
		}
	}
	
	public function confirmDelete ( $oMsg ) {
		
		
		if( $this->validateUser( $oMsg ) ) {
		
			$sql = sprintf("SELECT user_name FROM auth_user WHERE user_id = '%s' ",
				$oMsg->request['id'] );
				
			$rs = $oMsg->conn->query( $sql );
			
			if (PEAR::isError($rs) ) {
				Log::getInstance()->write($rs->getMessage(), "error");
				$this->insert_error( $oMsg, $rs->getMessage()."<br />".$sql."<br />".mysql_error(), "confirm_delete_error");
			} else {
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$libxml->push( 'form' );
					$libxml->element( 'action', 'index.php?action=deleteUser&module=user' );
					$libxml->element( 'method', 'POST' );
					$libxml->element( 'label', 'Confirm Delete User '.$row['user_name'] );
					
					$libxml->push( 'submit' );
						$libxml->element( 'name', 'deleteUserConfirmation' );
						$libxml->element( 'value', 'YES' );
					$libxml->pop();
					$libxml->push( 'submit' );
						$libxml->element( 'name', 'deleteUserConfirmation' );
						$libxml->element( 'value', 'NO' );
					$libxml->pop();
					$libxml->push( 'input' );
						$libxml->element( 'type', 'hidden' );
						$libxml->element( 'name', 'id' );
						$libxml->element( 'value', $oMsg->request['id'] );
					$libxml->pop();
				$libxml->pop();
				
				$oMsg->data['confirmDelete']['xml'] = $libxml->getXml();
				$oMsg->result = "show_confirmation_check";
			}
		}
	}

	public function deleteUser( $oMsg ) {
		
		
		if ($this->validateUser( $oMsg )) {
			if ( $oMsg->request['deleteUserConfirmation'] == "YES" ) {
				settype($oMsg->request['id'], 'integer');
				
				$sql1 = sprintf("DELETE FROM auth_user WHERE user_id = %d", $oMsg->request['id'] );
				$sql2 = sprintf("DELETE FROM auth_user_group WHERE ug_user_id = %d ", $oMsg->request['id'] );
				
				$rs = $oMsg->conn->exec( $sql1 );
				if (PEAR::isError ($rs) ) {
					Log::getInstance()->write ("error deleting from database ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
					return $this->insert_error($oMsg, "<b>Framework Error:</b> error deleting from database <br /><b> ".$rs->getMessage()." </b><br/><b>MySQL Error:</b> ".mysql_error()." <br/><b>SQL Statement:</b> ".$sql, "error");
				}
				$rs = $oMsg->conn->exec( $sql2 );
				if (PEAR::isError ($rs) ) {
					Log::getInstance()->write ("error deleting from database ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
					return $this->insert_error($oMsg, "<b>Framework Error:</b> error deleting from database <br /><b> ".$rs->getMessage()." </b><br/><b>MySQL Error:</b> ".mysql_error()." <br/><b>SQL Statement:</b> ".$sql, "error");
				}
				
				$oMsg->message = $rs. " rows deleted successfully.";
				$oMsg->result = 'delete_success';
			} else {
				$oMsg->result = 'delete_confirmation_no';
				$oMsg->message = "You decided not to proceed with the delete";
			}
		}
	}
	
	public function editUser( $oMsg ) {
		
		
		if ($this->validateUser($oMsg)) {
			settype($oMsg->request['id'], 'integer');
			
			$qSql = sprintf( "SELECT * FROM auth_user WHERE user_id = '%s' ",
				$oMsg->request['id'] );
				
			$qRs = $oMsg->conn->query( $qSql );
			
			if (PEAR::isError ($qRs) ) {
				Log::getInstance()->write ("error querying user information from database ".$qRs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
				return $this->insert_error($oMsg, "<b>Framework Error:</b> error querying user information from database<br /><b> ".$qRs->getMessage()."</b><br /><b>MYSQL Error:</b> ".mysql_error()."<br/><b>SQL Statement:</b> ".$qSql, "data_error");
			} else {
				Log::getInstance()->write ("queried database successfully", "debug");
				
				$qRow = $qRs->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$libxml->push( 'form' );
					$libxml->element( 'action', 'index.php?action=updateUser&module=user' );
					$libxml->element( 'method', 'POST' );
					$libxml->element( 'label', 'Edit User' );
					$libxml->push( 'input' );
						$libxml->element( 'type', 'text' );
						$libxml->element( 'name', 'editUserName' );
						$libxml->element( 'label', 'Userame' );
						$libxml->element( 'value', $qRow['user_name'] );
					$libxml->pop();
					$libxml->push( 'input' );
						$libxml->element( 'type', 'text' );
						$libxml->element( 'name', 'editFirstName' );
						$libxml->element( 'label', 'First Name' );
						$libxml->element( 'value', $qRow['user_first_name'] );
					$libxml->pop();
					$libxml->push( 'input' );
						$libxml->element( 'type', 'text' );
						$libxml->element( 'name', 'editLastName' );
						$libxml->element( 'label', 'Last Name' );
						$libxml->element( 'value', $qRow['user_last_name'] );
					$libxml->pop();
					$libxml->push( 'input' );
						$libxml->element( 'type', 'text' );
						$libxml->element( 'name', 'editPassword' );
						$libxml->element( 'label', 'Password' );
						$libxml->element( 'value', $qRow['user_pass'] );
					$libxml->pop();
					$libxml->push( 'submit' );
						$libxml->element( 'name', 'editUserSubmit' );
						$libxml->element( 'value', 'Update...' );
					$libxml->pop();
					$libxml->push( 'input' );
						$libxml->element( 'type', 'hidden' );
						$libxml->element( 'name', 'editUserId' );
						$libxml->element( 'label', '' );
						$libxml->element( 'value', $qRow['user_id'] );
					$libxml->pop();
				$libxml->pop();
				
				$oMsg->data['editUser']['xml'] = $libxml->getXml();
				$oMsg->result = 'data_found';
			}
		}
	}
	
	public function updateUser( $oMsg ) {
		
		
		if ($this->validateUser($oMsg) ) {
			settype($oMsg->request['editUserId'], 'integer');
			
			$sql = sprintf( "update auth_user set
						user_name = '%s',
						user_pass = '%s',
						user_first_name = '%s',
						user_last_name = '%s'
					where user_id = %d",
				$oMsg->request['editUserName'],
				$oMsg->request['editPassword'],
				$oMsg->request['editFirstName'],
				$oMsg->request['editLastName'],
				$oMsg->request['editUserId'] );
				
			$rs = $oMsg->conn->exec ($sql);
			if ( PEAR::isError( $rs ) ) {
				Log::getInstance()->write ("error updating user ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
				return $this->insert_error($oMsg, "<b>Framework Error:</b> error updating user<br /><b> ".$rs->getMessage()."</b><br /><b>MYSQL Error:</b> ".mysql_error()."<br/><b>SQL Statement:</b> ".$sql, "update_error");
			} else {
				Log::getInstance()->write ("queried database successfully", "debug");
				$oMsg->result = "update_success";
				$oMsg->message = $rs. " records updated successfully";
			}
		}
	}
	
	public function getUserGroupData( $oMsg ) {

		
		if ($this->validateUser($oMsg)) {
			settype($oMsg->request['id'], "integer"); //TYPE THE REQUEST TO PREVENT SQL INJECTION
			
			require_once("modules/common/xmlwriter.class.php");
			$libxml = new XmlWriterObj( );
			$libxml->push('page');
			$libxml->element('message', $oMsg->message);
			
			//USER SPECIFIC DATA
			$uSql = sprintf("select user_name from auth_user where user_id = %d", $oMsg->request['id']);
			$uRs = $oMsg->conn->query( $uSql );
			if(PEAR::isError( $uRs ) ) {
				Log::getInstance()->write("error querying user from database");
				return $this->insert_error( $oMsg, mysql_error(), "error" );
			} else {
				$row = $uRs->fetchRow(MDB2_FETCHMODE_ASSOC);
				$libxml->element('user_name', $row['user_name']);
				$libxml->element('user_id', $oMsg->request['id']);
			}
			
			//THANKS TO MICHAEL CHESTER FOR PUSHING ME TO FIND THIS MORE ELEGANT SOLUTION BELOW.
			//I WISH I COULD ONE DAY BECOME A MYSQL GENIUS TOO.
			
			$ugSql = sprintf("select group_id, group_name, if( group_id = ug_group_id, 1, 0 ) as member
				from auth_group
				left join auth_user_group
				on ( group_id = ug_group_id AND ug_user_id = %d )", $oMsg->request['id']);
				
			$ugRs = $oMsg->conn->query( $ugSql );
			if(PEAR::isError( $ugRs ) ) {
				Log::getInstance()->write("error querying user groups from database");
				return $this->insert_error( $oMsg, mysql_error(), "error" );
			} else {
				while ($row = $ugRs->fetchRow( MDB2_FETCHMODE_ASSOC ) ) {
					$libxml->push('group');
						$libxml->element('name', $row['group_name']);
						$libxml->element('value', $row['group_id']);
						if ($row['member']>0) {
							$libxml->element('checked', 'Y');
						} else {
							$libxml->element('checked', 'N');
						}
					$libxml->pop();
				}
				$libxml->push('submit');
					$libxml->element('value', 'Update Groups');
					$libxml->element('name', 'selectUserGroupsSubmit');
				$libxml->pop();
			}
			$libxml->pop(); //PAGE
			
			$oMsg->data['getUserGroupData']['xml'] = $libxml->getXml();
			$oMsg->result = 'showUserGroupSelectPage';
		}
	}
	
	/*
	First delete all groups for a user
	Then insert all groups selected for the user.
	NEED TO ENSURE THAT ADMINISTRATOR PERMISSIONS ARE INTACT or else it is
	possible that an administrator could remove themselves from the list and not be able to
	add themselves again.
	
	Will probably implement a hard coded super user for administrative support.
	*/
	
	public function updateUserGroup ( $oMsg ) {
	
		
		if($this->validateUser($oMsg) ) {
			
			$sGroups = $oMsg->request['group'];
			
			//DELETE ALL
			$uSql = sprintf("DELETE FROM auth_user_group WHERE ug_user_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->exec($uSql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Error deleting all groups for user.","error");
				return $this->insert_error($oMsg, "Error deleting all groups for user <br />".$rs->getMessage()."<br />MYSQL ERROR: ".mysql_error()."<br />SQL: ".$uSql);
			}
			
			//INSERT
			if (is_array($sGroups) ) {
				foreach ($sGroups as $g) {
					$iSql = sprintf("INSERT INTO auth_user_group (ug_user_id, ug_group_id) VALUES (%d, %d)", $oMsg->request['id'], $g);
					$rs = $oMsg->conn->exec($iSql);
					if(PEAR::isError($rs)) {
						Log::getInstance()->write("Error inserting group for user.","error");
						return $this->insert_error($oMsg, "Error inserting group for user <br />".$rs->getMessage()."<br />MYSQL ERROR: ".mysql_error()."<br />SQL: ".$uSql);
					}
				}
			}
			
			$oMsg->message = "Groups updated successfully";
			$oMsg->result="success";
		}
	}
}
?>