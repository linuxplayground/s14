<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ('modules/common/model.class.php');

class ScoreAction extends Model {
	public function __Construct( ) {
		//empty construct
	}
	
	public function listScore( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			$sql = "SELECT * FROM score";
			$rs = $oMsg->conn->query($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not query database for scores. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not query database for scores. ".$rs->getMessage(),"error");
			} else {
				$xml = "<page>
<message>Scores</message>
";
				while($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					$xml .= "<score>";
					$xml .= "	<name>".$row['score_name']."</name>";
					$xml .= "	<id>".$row['score_id']."</id>";
					$xml .= "	<value>".$row['score_value']."</value>";
					$xml .= "</score>";
				}
				$xml .= "</page>";
				
				$oMsg->data['score']['xml'] = $xml;
				$oMsg->result = "listScore";
			}
		}
	}
	
	public function editScore( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			settype($oMsg->request['id'], "integer");
			$sql = sprintf("SELECT * FROM score WHERE score_id = %d", $oMsg->request['id']);
			$rs = $oMsg->conn->query($sql);
			if( PEAR::isError($rs)) {
				Log::getInstance()->write("Could not query database for score to edit. ".mysql_error(),"error");
				return $this->insert_error($oMsg, "Could not query database for score to edit. ".$rs->getMessage(), "error");
			} else {
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				$xml = "<form>
	<label>Edit Score Value for : ".$row['score_name']."</label>
	<action>index.php?action=updateScore&amp;module=score&amp;id=".$row['score_id']."</action>
	<method>post</method>
		<input>
			<name>updateScoreValue</name>
			<label>Score Value</label>
			<value>".$row['score_value']."</value>
	</input>
	<submit>
		<name>updateScoreSubmit</name>
		<value>Update...</value>
	</submit>
</form>";
				$oMsg->data['score']['xml'] = $xml;
				$oMsg->result = "editScoreForm";
			}
		}
	}
	
	public function updateScore( Message $oMsg ){
		if ($this->validateUser($oMsg)) {
			
			settype($oMsg->request['updateScoreValue'], "integer");
			settype($oMsg->request['id'], "integer");
			$sql = sprintf("UPDATE score SET score_value = %d WHERE score_id = %d", $oMsg->request['updateScoreValue'], $oMsg->request['id']);
			$rs = $oMsg->conn->exec($sql);
			if (PEAR::isError($rs)) {
				Log::getInstance()->write("Database Error updating score value. ".mysql_error(),"error");
				return $this->insert_error($oMsg, "Database Error updating score value. ".$rs->getMessage(), "error");
			} else {
				$oMsg->result = "updateScoreSuccess";
				$omsg->message = "Score updated successfully.";
			}
		}
	}
}
?>