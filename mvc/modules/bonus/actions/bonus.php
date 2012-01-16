<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ('modules/common/model.class.php');

class BonusAction extends Model {
	public function __Construct( ) {
		//empty construct
	}
	public function listBonus( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			$sql = "SELECT * FROM bonus ORDER BY bonus_name";
			$rs = $oMsg->conn->query($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not query database for bonuses. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not query database for bonuses. ".$rs->getMessage(), "error");
			} else {
				$xml = "<page>";
				if (!isset($oMsg->message)) {
					$xml .= "<message>Bonuses listed in alphabetical order.</message>";
				} else {
					$xml .= "<message>".$oMsg->message."</message>";
				}
				while ($row=$rs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					$xml .= "
	<bonus>
		<name>".$row['bonus_name']."</name>
		<round_number>".$row['bonus_round']."</round_number>
		<multiplier>".$row['bonus_multiplier']."</multiplier>
		<id>".$row['bonus_id']."</id>
	</bonus>";
				}
				$xml .= "
</page>";
				$oMsg->data['bonus']['xml'] = $xml;
				$oMsg->result = "listBonus";
			}
		}
	}
	
	public function newBonus( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			$oMsg->data['bonus']['xml'] = "<form>
	<action>index.php?action=insertBonus&amp;module=bonus</action>
	<method>post</method>
	<label>Insert New bonus.  Picks for games falling on or between selected dates will score points multplied by \"Multiplier\"</label>
</form>";
			$oMsg->result="newBonusForm";
		}
	}
	
	public function insertBonus( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			$sql = sprintf("INSERT INTO bonus VALUES (null, '%s', '%s', %d)",
				$oMsg->request['bonusName'],$oMsg->request['bonusMultiplier'], $oMsg->request['roundNumber']);
			$rs = $oMsg->conn->query($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not add bonus entry to database. ".mysql_error(),"error");
				return $this->insert_error($oMsg, "Could not add new bonus to database. ".$rs->getMessage(), "error");
			} else {
				$oMsg->result = "insertBonusSuccess";
				$oMsg->message = "One bonus week added.";
			}
		}
	}
	
	public function editBonus( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			settype($oMsg->request['id'], "integer");
			$sql = sprintf("SELECT * FROM bonus WHERE bonus_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->query($sql);
			if (PEAR::isError($rs)) {
				Log::getInstance()->write("Could not query database for bonus to edit. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Error querying database for information on bonus to edit. ".$rs->getMessage(),"error");
			} else {
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				$oMsg->data['bonus']['xml'] = "<form>
	<label>Edit Bonus</label>
	<action>index.php?action=updateBonus&amp;module=bonus&amp;id=".$oMsg->request['id']."</action>
	<method>post</method>
		<name>".$row['bonus_name']."</name>
		<roundNumber>".$row['bonus_round']."</roundNumber>
		<multiplier>".$row['bonus_multiplier']."</multiplier>
</form>";
				$oMsg->result = "editBonusForm";
			}
		}
	}
	
	public function updateBonus( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			settype($oMsg->request['id'], "integer");
			settype($oMsg->request['multiplier'], "integer");
			$sql = sprintf("UPDATE bonus SET bonus_name='%s', bonus_round = '%s', bonus_multiplier=%d WHERE bonus_id=%d",
				$oMsg->request['bonusName'], $oMsg->request['roundNumber'], $oMsg->request['bonusMultiplier'], $oMsg->request['id']
				);
			$rs = $oMsg->conn->exec($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not update bonus. ".mysql_error(),"error");
				return $this->insert_error($oMsg,"Could not update bonus. ".$rs->getMessage(),"error");
			} else {
				$oMsg->message = "One bonus updated successfully.";
				$oMsg->result = "updateBonusSuccess";
			}
		}
	}
	
	public function confirmDeleteBonus( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			settype($oMsg->request['id'], "integer");
			$sql = sprintf("SELECT * FROM bonus WHERE bonus_id = %d",$oMsg->request['id']);
			$rs = $oMsg->conn->query($sql);
			if (PEAR::isError($rs)) {
				Log::getInstance()->write("Could not find data to confirm delete ".$mysql_error, "error");
				return $this->insert_error($oMsg, "Could not find bonus data to confirm delete. ".$rs->getMessage(), "error");
			} else {
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				$xml = "<form>
	<action>index.php?action=deleteBonus&amp;module=bonus&amp;id=".$row['bonus_id']."</action>
	<method>post</method>
	<label>Confirm delete: ".$row['bonus_name']."</label>
	<submit>
		<name>confirmDeleteBonusSubmit</name>
		<value>YES</value>
	</submit>
	<submit>
		<name>confirmDeleteBonusSubmit</name>
		<value>NO</value>
	</submit>
</form>";
				$oMsg->data['bonus']['xml'] = $xml;
				$oMsg->result = "confirmDeleteBonusForm";
			}
		}
	}
	
	public function deleteBonus( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			if($oMsg->request['confirmDeleteBonusSubmit']=="YES") {
				settype($oMsg->request['id'],"integer");
				$sql = sprintf("DELETE FROM bonus WHERE bonus_id = %d", $oMsg->request['id']);
				$rs = $oMsg->conn->exec($sql);
				if(PEAR::isError($rs)) {
					Log::getInstance()->write($oMsg, "Could not delete bonus due to a database error. ".mysql_error(),"error");
					return $this->insert_error($oMsg,"Could not delete bonus due to a database error. ".$rs->getMessage(),"error");
				} else {
					$oMsg->message = "One bonus deleted successfully.";
					$oMsg->result = "deleteBonusSuccess";
				}
			} else {
				$oMsg->message = "You decided not to proceed with deleting the bonus at this time.";
				$oMsg->result = "deleteBonusSuccess";
			}
		}
	}
}
?>