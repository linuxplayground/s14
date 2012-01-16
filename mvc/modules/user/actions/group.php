<?php
/*
	All action methods for Authorisation in this model.
	
*/
require_once ("modules/common/model.class.php");
Log::getInstance()->write (__FILE__." loaded", "debug");

class GroupAction extends Model {

	public function __construct ( ) {
		//
	}
	
	public function listGroup( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			
			$sql = "SELECT * FROM auth_group ORDER BY group_name";
			$rs = $oMsg->conn->query( $sql );
			
			if(PEAR::isError($rs) ) {
				Log::getInstance()->write("error retreiving data for groups from database", "error");
				return $this->insert_error($oMsg, "Error retrieving data for groups from database.<br />".$rs->getMessage()."<br />".mysql_error()."<br />".$sql);
			} else {
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$libxml->push('page');
					if (!$oMsg->message) {
						$libxml->element('message', 'Groups listed by group name ascending');
					} else {
						$libxml->element('message', $oMsg->message);
					}
						$libxml->push('groups');
						
						while ($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
							$libxml->push('group');
								$libxml->element('id', $row['group_id']);
								$libxml->element('name', $row['group_name']);
							$libxml->pop();
						}
					$libxml->pop();
				$libxml->pop();
				
				$oMsg->data['listGroup']['xml'] = $libxml->getXml( );
				$oMsg->result = "display_group_list";
			}
		}
	}
	
	public function newGroup( $oMsg ) {
		if ($this->validateUser($oMsg) ) {
			$oMsg->data['newGroupForm']['xml'] = <<<EXML
	<form>
		<label>New Group</label>
		<action>index.php?action=insertGroup&amp;module=user</action>
		<method>POST</method>
		<input>
			<name>newGroupName</name>
			<label>Name</label>
			<type>text</type>
		</input>
		<submit>
			<name>newGroupSubmit</name>
			<value>Add...</value>
		</submit>
	</form>
EXML;
			$oMsg->result="display_new_group_form";
		}
	}
	
	public function insertGroup( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			$sql = sprintf("INSERT INTO auth_group (group_name) values ('%s')", $oMsg->request['newGroupName'] );
			$rs = $oMsg->conn->exec($sql);
			if (PEAR::isError($rs) ) {
				Log::getInstance()->write("Error inserting group in database.", "error");
				return $this->insert_error($oMsg, "Error inserting group in database<br />".$rs->getMessage()."<br />".mysql_error(), "error");
			} else {
				$oMsg->message=$rs." Group inserterted successfully";
				$oMsg->result="display_group_list";
			}
		}
	}
	
	public function editGroup( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			settype($oMsg->request['id'], 'integer');
			
			$sql = sprintf("SELECT * FROM auth_group WHERE group_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->query( $sql );
			if( PEAR::isERROR( $rs ) ) {
				Log::getInstance()->write("Error retrieving data from database for edit group", "error");
				return $this->insert_error( $oMsg, "Error retreiving data from database for edit group<br />".$rs->getMessage()."<br />".mysql_error()."<br />". $sql, "error");
			} else {
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				$libxml->push('form');
					$libxml->element('action', 'index.php?action=updateGroup&module=user');
					$libxml->element('method', 'POST');
					$libxml->element('label', 'Edit Group');
					$libxml->push('input');
						$libxml->element('type', 'text');
						$libxml->element('name', 'editGroupName');
						$libxml->element('value', $row['group_name']);
					$libxml->pop();
					$libxml->push('input');
						$libxml->element('type', 'hidden');
						$libxml->element('name', 'id');
						$libxml->element('value', $oMsg->request['id']);
					$libxml->pop();
					$libxml->push('submit');
						$libxml->element('name', 'editGroupSubmit');
						$libxml->element('value', 'Update...');
					$libxml->pop();
				$libxml->pop();
				
				$oMsg->data['editGroupForm']['xml'] = $libxml->getXml();
				$oMsg->result = 'display_edit_group_form';
			}
		}
	}
	
	public function updateGroup( $oMsg ) {
		
		
		if ($this->validateUser($oMsg) ) {
			settype($oMsg->request['id'], 'integer');
			
			$sql = sprintf("UPDATE auth_group set group_name = '%s' WHERE group_id = %d",
				$oMsg->request['editGroupName'], $oMsg->request['id']);
				
			$rs = $oMsg->conn->exec($sql);
			if (PEAR::isError($rs)) {
				Log::getInstance()->write("Error updating groups table in database", "error");
				return $this->insert_error($oMsg, "Error updating groups table<br />".$rs->getMessage()."<br />".$mysql_error()."<br />SQL: ".$sql, "error");
			} else {
				$oMsg->result = "success";
				$oMsg->message = $rs." group updated successfully";
			}
		}
	}
	public function confirmDeleteGroup( $oMsg ) {
		
		
		if ($this->validateUser($oMsg) ) {
			settype($oMsg->request['id'], 'integer');
			
			$sql = sprintf("SELECT * FROM auth_group WHERE group_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->query( $sql );
			if (PEAR::isError($rs) ) {
			
			} else {
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				$libxml->push( 'form' );
					$libxml->element( 'action', 'index.php?action=deleteGroup&module=user' );
					$libxml->element( 'method', 'POST' );
					$libxml->element( 'label', 'Confirm Delete Group '.$row['group_name'] );
					
					$libxml->push( 'submit' );
						$libxml->element( 'name', 'deleteGroupConfirmation' );
						$libxml->element( 'value', 'YES' );
					$libxml->pop();
					$libxml->push( 'submit' );
						$libxml->element( 'name', 'deleteGroupConfirmation' );
						$libxml->element( 'value', 'NO' );
					$libxml->pop();
					$libxml->push( 'input' );
						$libxml->element( 'type', 'hidden' );
						$libxml->element( 'name', 'id' );
						$libxml->element( 'value', $oMsg->request['id'] );
					$libxml->pop();
				$libxml->pop();
				
				$oMsg->data['showDeleteGroupConfirmationForm']['xml'] = $libxml->getXml();
				$oMsg->result='display_delete_group_confirmation_form';
			}
		}
	}
	
	function deleteGroup ( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			if ($oMsg->request['deleteGroupConfirmation'] == 'NO' ) {
				$oMsg->result="delete_confirmation_no";
				$oMsg->message = "You decided not to proceed with the delete action.";
			} elseif ($oMsg->request['deleteGroupConfirmation'] == 'YES' ) {
				settype ($oMsg->request['id'], 'integer');
				
				$sql1 = sprintf('DELETE FROM auth_group WHERE group_id = %d', $oMsg->request['id']);
				$sql2 = sprintf('DELETE FROM auth_user_group WHERE ug_group_id = %d', $oMsg->request['id']);
				$sql3 = sprintf('DELETE FROM auth_permission WHERE p_group_id = %d', $oMsg->request['id']);

				$rs = $oMsg->conn->exec($sql1);
				if (PEAR::isError($rs)) {
					Log::getInstance()->write("Could not delete group", "error");
					return $this->insert_error($oMsg, "Error deleting group from database<br />".$rs->getMessage()."<br/>".mysql_error()."<br />".$sql, "error");
				}
				
				$rs = $oMsg->conn->exec($sql2);
				if (PEAR::isError($rs)) {
					Log::getInstance()->write("Could not delete group", "error");
					return $this->insert_error($oMsg, "Error deleting group from database<br />".$rs->getMessage()."<br/>".mysql_error()."<br />".$sql, "error");
				}
				
				$rs = $oMsg->conn->exec($sql3);
				if (PEAR::isError($rs)) {
					Log::getInstance()->write("Could not delete group", "error");
					return $this->insert_error($oMsg, "Error deleting group from database<br />".$rs->getMessage()."<br/>".mysql_error()."<br />".$sql, "error");
				}
				
				$oMsg->message = $rs." group deleted successfully";
				$oMsg->result = "success";
			} else {
				Log::getInstance()->write ("bad confirmation string deleting groups", "error");
				return $this->insert_error($oMsg, "Bad confirmation.  Hack Attempt?", "error");
			}
		}
	}
	
	public function getPermissionsForGroup( $oMsg ) {
		
		
		if ($this->validateUser($oMsg) ) {
			settype($oMsg->request['id'], 'integer');
			
			require_once("modules/common/xmlwriter.class.php");
			$libxml = new XmlWriterObj( );
			$libxml->push('page');
				if (!$oMsg->message) {
					$libxml->element('message', 'Permissions for group listed by name ascending');
				} else {
					$libxml->element('message', $oMsg->message);
				}
			$sql = sprintf("select group_name from auth_group where group_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->query($sql);
			$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
			$libxml->element('group_name', $row['group_name']);
			$libxml->element('group_id', $oMsg->request['id']);
			
			$rs->free();
			
			$sql = sprintf("select action_id, action_name, if(action_id = p_action_id, 1, 0) as member from auth_action left join auth_permission on p_action_id = action_id and p_group_id = %d ORDER BY auth_action.action_name",
				$oMsg->request['id']);
			$rs = $oMsg->conn->query( $sql );
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Error retrieving permission data from database", "error");
				return $this->insert_error($oMsg, "Error retreiving permission data from database<br />".$rs->getMessage(), "error");
			} else {
				while($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
					$libxml->push('permission');
					$libxml->element('name', $row['action_name']);
					$libxml->element('value', $row['action_id']);
					if($row['member'] > 0 ) {
						$libxml->element('checked', 'Y');
					} else {
						$libxml->element('checked', 'N');
					}
					$libxml->pop();
				}
			}
			$libxml->push('submit');
				$libxml->element('value', 'Update permissions...');
				$libxml->element('name', 'permissionsForGroupSubmit');
			$libxml->pop();
			$libxml->pop();
			
			//echo $libxml->getXml(); exit;
			$oMsg->data['permissionsForGroup']['xml'] = $libxml->getXml();
			$oMsg->result = 'displayPermissionsSelect';
		}
	}
	
	public function updateGroupPermission( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			settype($oMsg->request['id'], 'integer');
			
			$sGroups = $oMsg->request['permission'];

			//DELETE ALL
			$uSql = sprintf("DELETE FROM auth_permission WHERE p_group_id = %d", $oMsg->request['id']);
			$uRs = $oMsg->conn->exec($uSql);
			if(PEAR::isError($uRs)) {
				Log::getInstance()->write("Error deleting all permissions for group","error");
				return $this->insert_error($oMsg, "Error deleting all permissions for group <br />".$rs->getMessage()."<br />MYSQL ERROR: ".mysql_error()."<br />SQL: ".$uSql, "error");
			}
			
			//INSERT
			if (is_array($sGroups) ) {
				foreach ($sGroups as $g) {
					$iSql = sprintf("INSERT INTO auth_permission (p_action_id, p_group_id) VALUES (%d, %d)", $g, $oMsg->request['id']);
					$iRs = $oMsg->conn->exec($iSql);
					if(PEAR::isError($iRs)) {
						Log::getInstance()->write("Error inserting permission for group.","error");
						return $this->insert_error($oMsg, "Error inserting permission for group <br />".$iRs->getMessage()."<br />MYSQL ERROR: ".mysql_error()."<br />SQL: ".$iSql, "error");
					}

				}
			}
			
			$oMsg->message = "Permissions updated successfully";
			$oMsg->result="success";
		}
	}
	
	public function getGroupUserData( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {		
			settype ($oMsg->request['id'], 'integer');
			
			require_once("modules/common/xmlwriter.class.php");
			$libxml = new XmlWriterObj( );
			
			$libxml->push('page');
			if (!$oMsg->message) {
				$libxml->element('message', 'Users for group listed by name ascending');
			} else {
				$libxml->element('message', $oMsg->message);
			}
			
			$sql = sprintf("select group_name from auth_group where group_id = %d",$oMsg->request['id']);
			$rs = $oMsg->conn->query($sql);
			if (PEAR::isError($rs) ) {
				Log::getInstance()->write("error retreiving group name from database", "error");
				return $this->insert_error( $oMsg, "Error retrieving group name from database<br />".$rs->getMessage(), "error");
			} else {
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				$libxml->element('group_name', $row['group_name']);
				$libxml->element('group_id', $oMsg->request['id']);
			}
			$rs->free();
			
			$sql = sprintf("select user_id, user_name, if(user_id = ug_user_id, 1, 0) as member from auth_user left join auth_user_group on ug_user_id = user_id and ug_group_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->query($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Error fetching user / group information from database", "error");
				return $this->insert_error($oMsg, "Error fetching user / group information from database<br />".$rs->getMessage(), "error");
			} else {
				while ($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
					$libxml->push('user');
					$libxml->element('name', $row['user_name']);
					$libxml->element('value', $row['user_id']);
					
					if ($row['member'] > 0 ) {
						$libxml->element('checked', 'Y');
					} else {
						$libxml->element('checked', 'N');
					}
					$libxml->pop();
				}
				$libxml->push('submit');
					$libxml->element('value', 'Update users...');
					$libxml->element('name', 'updateUsersForGroupSubmit');
				$libxml->pop();
			}
			$libxml->pop();
			
			$oMsg->data['userGroupData']['xml'] = $libxml->getXml();
			$oMsg->result = 'success';
		}
	}
	
	public function updateGroupUser( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			settype($oMsg->request['id'], 'integer');
			
			$sUsers = $oMsg->request['user'];

			//DELETE ALL
			$uSql = sprintf("DELETE FROM auth_user_group WHERE ug_group_id = %d", $oMsg->request['id']);
			$uRs = $oMsg->conn->exec($uSql);
			if(PEAR::isError($uRs)) {
				Log::getInstance()->write("Error deleting all users for group","error");
				return $this->insert_error($oMsg, "Error deleting all users for group <br />".$rs->getMessage()."<br />MYSQL ERROR: ".mysql_error()."<br />SQL: ".$uSql, "error");
			}
			
			//INSERT
			if (is_array($sUsers) ) {
				foreach ($sUsers as $u) {
					$iSql = sprintf("INSERT INTO auth_user_group (ug_group_id, ug_user_id) VALUES (%d, %d)", $oMsg->request['id'], $u);
					$iRs = $this->conn->exec($iSql);
					if(PEAR::isError($iRs)) {
						Log::getInstance()->write("Error inserting user for group.","error");
						return $this->insert_error($oMsg, "Error inserting user for group <br />".$iRs->getMessage()."<br />MYSQL ERROR: ".mysql_error()."<br />SQL: ".$iSql, "error");
					}

				}
			}
			
			$oMsg->message = "users updated successfully";
			$oMsg->result="success";
		}
	}
}

?>