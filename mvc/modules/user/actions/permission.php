<?php
/*
	All action methods for Authorisation in this model.
*/
require_once ("modules/common/model.class.php");
Log::getInstance()->write (__FILE__." loaded", "debug");

class PermissionAction extends Model {

	public function __construct ( ) {
		//
	}
	
	public function listPermission( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			
			$sql = "SELECT * FROM auth_action ORDER BY action_name";
			$rs = $oMsg->conn->query( $sql );
			
			if(PEAR::isError($rs) ) {
				Log::getInstance()->write("error retreiving data for permissions from database", "error");
				return $this->insert_error($oMsg, "Error retrieving data for permissions from database.<br />".$rs->getMessage()."<br />".mysql_error()."<br />".$sql);
			} else {
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$libxml->push('page');
					if (!$oMsg->message) {
						$libxml->element('message', 'Permissions listed by name ascending');
					} else {
						$libxml->element('message', $oMsg->message);
					}
						$libxml->push('permissions');
						
						while ($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
							$libxml->push('permission');
								$libxml->element('id', $row['action_id']);
								$libxml->element('name', $row['action_name']);
							$libxml->pop();
						}
					$libxml->pop();
				$libxml->pop();
				
				$oMsg->data['listPermissions']['xml'] = $libxml->getXml( );
				$oMsg->result = "display_permission_list";
			}
		}
	}
	
	public function getGroupPermissionData( $oMsg ) {
		
		if ($this->validateUser($oMsg) ) {
			settype( $oMsg->request['id'], "integer");
			
			require_once("modules/common/xmlwriter.class.php");
			$libxml = new XmlWriterObj( );
			$libxml->push('page');
			$libxml->element('message', $oMsg->message);
			
			//USER SPECIFIC DATA
			$uSql = sprintf("select action_name from auth_action where action_id = %d", $oMsg->request['id']);
			$uRs = $oMsg->conn->query( $uSql );
			if(PEAR::isError( $uRs ) ) {
				Log::getInstance()->write("error querying action from database");
				return $this->insert_error( $oMsg, mysql_error(), "error" );
			} else {
				$row = $uRs->fetchRow(MDB2_FETCHMODE_ASSOC);
				$libxml->element('permission_name', $row['action_name']);
				$libxml->element('permission_id', $oMsg->request['id']);
			}
			
			$sql = sprintf("select group_id, group_name, if(group_id = p_group_id, 1, 0) as member from auth_group left join auth_permission on (group_id = p_group_id AND p_action_id = %d)", $oMsg->request['id']);
			$rs = $oMsg->conn->query( $sql );
			If (PEAR::isError($rs) ) {
				Log::getInstance()->write("Error retreiving data from database for getGroupPermissionData", "error");
				return $this->insert_error( $oMsg, "Error retreiving data from database for getGroupPermissionData<br />".$rs->getMessage()."<br />".mysql_error."<br />".$sql, "error");
			} else {
				while($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
					$libxml->push('permission');
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
					$libxml->element('name', 'selectPermissionGroupsSubmit');
				$libxml->pop();
			}
			$libxml->pop(); //PAGE
			$oMsg->data['getPermissionGroupData']['xml'] = $libxml->getXml();
			$oMsg->result = 'showPermissionGroupSelectPage';
		}
	}
	
	public function updatePermissionGroup ( $oMsg ) {
	
		
		if ($this->validateUser($oMsg) ) {
		
			$sGroups = $oMsg->request['group'];
			
			//DELETE ALL
			$uSql = sprintf("DELETE FROM auth_permission WHERE p_action_id = %d", $oMsg->request['id']);
			$uRs = $oMsg->conn->exec($uSql);
			if(PEAR::isError($uRs)) {
				Log::getInstance()->write("Error deleting all groups for action","error");
				return $this->insert_error($oMsg, "Error deleting all groups for action <br />".$rs->getMessage()."<br />MYSQL ERROR: ".mysql_error()."<br />SQL: ".$uSql, "error");
			}
			
			//INSERT
			if (is_array($sGroups) ) {
				foreach ($sGroups as $g) {
					$iSql = sprintf("INSERT INTO auth_permission (p_action_id, p_group_id) VALUES (%d, %d)", $oMsg->request['id'], $g);
					$iRs = $oMsg->conn->exec($iSql);
					if(PEAR::isError($iRs)) {
						Log::getInstance()->write("Error inserting group for permission.","error");
						return $this->insert_error($oMsg, "Error inserting group for permission <br />".$iRs->getMessage()."<br />MYSQL ERROR: ".mysql_error()."<br />SQL: ".$iSql, "error");
					}

				}
			}
			
			$oMsg->message = "Groups updated successfully";
			$oMsg->result="success";
		}
	}
}

?>