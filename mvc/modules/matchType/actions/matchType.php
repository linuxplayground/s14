<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ('modules/common/model.class.php');

class MatchTypeAction extends Model {
	public function __Construct( ) {
		//empty construct
	}

	public function listMatchType( Message $oMsg ) {
		
		if ($this->validateUser($oMsg)) {
			$sql = "SELECT * from matchtype ORDER BY mt_name ASC";
			$rs = $oMsg->conn->query($sql);
			if (PEAR::isError($rs)) {
				Log::getInstance()->write("Could not query database for match types. ".mysql_error(),"error");
				return $this->insert_error($oMsg, "Could not query database for match types. <br/>".$rs->getMessage(),"error");
			}
			if (!isset($oMsg->message)) {
				$message = "Match types listed in alphabetical order.";
			} else {
				$message = $oMsg->message;
			}
			$xml = "<page>
	<message>".$message."</message>
";
			while ($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$xml.="
	<matchtype>
		<name>".$row['mt_name']."</name>
		<id>".$row['mt_id']."</id>
	</matchtype>";
			}
			$xml.="</page>";
			$oMsg->data['matchtype']['xml'] = $xml;
			$oMsg->result = "listMatchType";
		}
	}
	
	public function editMatchType( Message $oMsg ) {
		
		if ($this->validateUser($oMsg)) {
			settype($oMsg->request['id'], "integer");
			$sql = sprintf("SELECT * FROM matchtype WHERE mt_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->query($sql);
			if( PEAR::isError($rs)) {
				Log::getInstance()->write("Could not select from matchtype for edit. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not find data to edit from match type. ".$rs->getMessage(), "error");
			}
			$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
			$xml = "<form>
	<action>index.php?action=updateMatchType&amp;module=matchType&amp;id=".$row['mt_id']."</action>
	<method>post</method>
	<label>Edit Match Type</label>
	<input>
		<type>text</type>
		<name>updateMatchTypeName</name>
		<value>".$row['mt_name']."</value>
	</input>
	<submit>
		<name>updateMatchTypeSubmit</name>
		<value>Update...</value>
	</submit>
</form>";
			$oMsg->data['matchtype']['xml'] = $xml;
			$oMsg->result = "editMatchTypeForm";
		}
	}
	
	public function updateMatchType( Message $oMsg ) {
		
		if ($this->validateUser($oMsg)) {
			settype($oMsg->request['id'],"integer");
			$sql = sprintf("UPDATE matchtype SET mt_name = '%s' WHERE mt_id = %d", $oMsg->request['updateMatchTypeName'], $oMsg->request['id']);
			$rs = $oMsg->conn->exec($sql);
			if( PEAR::isError($rs)) {
				Log::getInstance()->write("Could not update match type. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not update the match type. ".$rs->getMessage(), "error");
			}
			$oMsg->message = "One match type updated successully.";
			$oMsg->result = "updateMatchTypeSuccess";
		}
	}
	
	public function newMatchType( Message $oMsg ) {
		
		if ($this->validateUser($oMsg)) {
			$xml = "<form>
	<action>index.php?action=insertMatchType&amp;module=matchType</action>
	<method>post</method>
	<label>New Match Type</label>
	<input>
		<name>insertMatchTypeName</name>
		<label>Match Type Name</label>
		<type>text</type>
	</input>
	<submit>
		<name>insertMatchTypeSubmit</name>
		<value>Add...</value>
	</submit>
</form>";
			$oMsg->data['matchtype']['xml'] = $xml;
			$oMsg->result="newMatchTypeForm";
		}
	}
	
	public function insertMatchType( Message $oMsg ) {
		
		if ($this->validateUser($oMsg)) {
			$sql = sprintf("INSERT INTO matchtype values(null, '%s' )", $oMsg->request['insertMatchTypeName']);
			$rs = $oMsg->conn->exec($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not add match type. ".mysql_error(),"error");
				return $this->insert_error($oMsg, "Could not add match type to database. ".$rs->getMessage(), "error");
			}
			
			$oMsg->result = "insertMatchTypeSuccess";
			$oMsg->message = "1 match type added successfully.";
		}
	}
	
	public function confirmDeleteMatchType( Message $oMsg ) {
		
		if ($this->validateUser($oMsg)) {
			settype($oMsg->request['id'], "integer");
			$sql = sprintf("SELECT * FROM matchtype WHERE mt_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->query($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not find match type to confirm delete. ".mysql_error(),"error");
				return $this->insert_error($oMsg, "Could not find match type to confirm delete. ".$rs->getMessage(), "error");
			}
			$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
			
			$oMsg->data['matchtype']['xml'] = "<form>
	<action>index.php?action=deleteMatchType&amp;module=matchType&amp;id=".$row['mt_id']."</action>
	<method>post</method>
	<label>Confirm that you would like to delete the match type: ".$row['mt_name']."</label>
	<submit>
		<name>confirmDeleteMatchType</name>
		<value>YES</value>
	</submit>
	<submit>
		<name>confirmDeleteMatchType</name>
		<value>NO</value>
	</submit>
</form>";
			$oMsg->result="confirmDeleteMatchTypeForm";
		}
	}
	
	public function deleteMatchType( Message $oMsg ) {
		
		if ($this->validateUser($oMsg)) {
			if($oMsg->request['confirmDeleteMatchType'] == "YES") {
				settype($oMsg->request['id'], "integer");
				$sql = sprintf("DELETE FROM matchtype WHERE mt_id = %d", $oMsg->request['id']);
				$rs = $oMsg->conn->exec($sql);
				if( PEAR::isError($rs)) {
					Log::getInstance()->write("Could not delete from the match types table. ".mysql_error(), "error");
					return $this->insert_error($oMsg, "Could not delete the match type. ".$rs->getMessage(),"error");
				}
				$sql = sprintf("DELETE FROM matchtype_margin WHERE mt_id = %d", $oMsg->request['id']);
				$rs = $oMsg->conn->exec($sql);
				if( PEAR::isError($rs)) {
					Log::getInstance()->write("Could not delete from the matchtype_margin table. ".mysql_error(), "error");
					return $this->insert_error($oMsg, "Could not delete the matchtype_margin entry. ".$rs->getMessage(),"error");
				}
				$oMsg->result="deleteMatchTypeSuccess";
				$oMsg->message = "One match type and ".$rs." matchtype_margin entries were successfully deleted.";
			} else {
				$oMsg->result="deleteMatchTypeSuccess";
				$oMsg->message = "You decided not to delete a match type this time.";
			}
		}	
	}
}
?>