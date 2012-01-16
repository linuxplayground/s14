<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ('modules/common/model.class.php');

class GameAction extends Model {
	public function __Construct( ) {
		//empty construct
	}
	
	public function listAllGame( $oMsg ) {
		if ($this->validateUser( $oMsg ) ) {
			$dateAr = getdate(time());
			$curYear = $dateAr['year'];

			$sql = "SELECT * FROM games_list WHERE 1 = 1 ";
			$oMsg->message = "Listing of all games by:";
			if (isset($oMsg->request['year']) && $oMsg->request['year'] != 'all') {
				$sql .= sprintf("AND year(gdate) = '%s' ",$oMsg->request['year']);
				$oMsg->message .= 'Year: '.$oMsg->request['year'];
			}
			if (isset($oMsg->request['filter'])) {
				$sql .= sprintf("AND mtype = '%s' and year(gdate) = '%s' ",$oMsg->request['filter'], $curYear);
				$oMsg->message .= ' AND Matchtype: '.$oMsg->request['filter'];
			}
			if (isset($oMsg->request['round']) && $oMsg->request['round'] != 'all') {
				$sql .= sprintf("AND roundnum = '%s' AND year(gdate) = '%s' ",$oMsg->request['round'], $curYear);
				$oMsg->message .= ' AND Round: '.$oMsg->request['round'];
			}
			$sql .= " ORDER BY roundnum, gdate";
			$rs = $oMsg->conn->query($sql);
			if (PEAR::isError($rs) ) {
				Log::getInstance()->write( "Could not retrieve list of games from database. ".mysql_error(), "error" );
				return $this->insert_error( $oMsg, "Could not retrieve list of games from database. ".$rs->getMessage(), "error" );
			} else {
				require_once( "modules/common/xmlwriter.class.php" );
				$xmlObj = new XMLWriterObj( );
				$xmlObj->push('page');
				if (! isset( $oMsg->message) ) {
					$message = "Games listed by date descending.";
				} else {
					$message = $oMsg->message;
				}
				if (isset($oMsg->request['year'])) {
					$xmlObj->element('defaultYear', $oMsg->request['year']);
				}
				$xmlObj->element( 'message', $message );
				$showUpdateButton = 0; //Disable the update scores button for now.
				while( $row = $rs->fetchRow( MDB2_FETCHMODE_ASSOC ) ) {
					$xmlObj->push ('game');
					$xmlObj->element( 'id', $row['id'] );
					$xmlObj->element( 'hometeam', $row['home'] );
					$xmlObj->element( 'awayteam', $row['away'] );
					$xmlObj->element( 'homescore', $row['hscore'] );
					$xmlObj->element( 'awayscore', $row['ascore'] );
					$xmlObj->element( 'matchtype', $row['mtype'] );
					$xmlObj->element( 'round', $row['roundnum']);
					$xmlObj->element( 'date',strftime("%a %d %b %H:%M",strtotime( $row['gdate'] ) ) );
					$xmlObj->element( 'scored', $row['scored'] );
					$xmlObj->element('played', $row['played'] );
					$xmlObj->pop();
					
					//Check to see if we need an update button.
					if ($row['scored'] == 0 ) {
						$showUpdateButton = 1;
					}
				}
					$xmlObj->element( 'showUpdateButton', $showUpdateButton);

					$xmlObj->push("years");
						$yrSql = "Select year(gdate) as yr from games_list GROUP BY yr";
						$yrRs = $oMsg->conn->query($yrSql);
						if(PEAR::isError($yrRs)) {
							Log::getInstance()->write("Could not select years from database. ".mysql_error(), "error");
							return $this->insert_error($oMsg, "Could not select years from games table. ".$yrRs->getMessage(),"error");
						}
						while ($yrRow = $yrRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
							$xmlObj->element('value', $yrRow['yr']);
						}
					$xmlObj->pop();
					//url for pdf
					$url = $_SERVER['REQUEST_URI'];
					$pdf_url = str_replace('action=listAllGame', 'action=listAllGamePdf', $url);
					$xmlObj->element('pdfurl',$pdf_url);
					$rRs = $oMsg->conn->query("SELECT g_round from game group by g_round order by g_round");
					while ($rRow = $rRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
						$xmlObj->element("round", $rRow['g_round']);
					}
					if(isset($oMsg->request['round'])) {
						$xmlObj->element("defaultRound", $oMsg->request['round']);
					}
				$xmlObj->pop();
//echo "<pre>";print_r($xmlObj->getXml());echo "</pre>";exit;
				$oMsg->data['game']['xml'] = $xmlObj->getXml();
				$oMsg->result = 'listAllGame';
			}
		}
	}
	
	public function updateGameScores(Message $oMsg) {
		if ($this->validateUser($oMsg)) {
			
			$counter = 0;
			$homeScoreAr = $oMsg->request['updateHomeScore'];
			$awayScoreAr = $oMsg->request['updateAwayScore'];
			foreach ($homeScoreAr as $key => $value) {
				if( $awayScoreAr[$key] != null && $value != null ) {
					/*We can only update scores if both are filled in.*/
					$sql = sprintf("UPDATE game SET g_hometeam_score = %d, g_awayteam_score=%d WHERE g_id = %d",
						$value,
						$awayScoreAr[$key],
						$key);
					$rs = $oMsg->conn->exec($sql);
					if (PEAR::isError($rs)) {
							Log::getInstance()->write("Could not update scores to database. ".mysql_error(), "error");
							return $this->insert_error($oMsg, "Could not update scores in database. ".$rs->getMessage, "error");
					}
					$counter ++;
					}
			}
			$oMsg->message = "Updated ".$counter. "rows successfully";
			$oMsg->result = "updateGameScoreSuccess";
		}
	}
	
	public function editGame( Message $oMsg ) {
		if ($this->validateUser( $oMsg ) ) {
			
			settype($oMsg->request['id'], "integer");
			
			//Get the game details.
			$gameSql = sprintf("SELECT * FROM game WHERE g_id = %d", $oMsg->request['id']);
			$gameRs = $oMsg->conn->query($gameSql);
			if (PEAR::isError($gameRs)) {
				Log::getInstance()->write("Could not query games from database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not query game from database. ".$gameRs->getMessage(), "error");
			}
			$row = $gameRs->fetchRow(MDB2_FETCHMODE_ASSOC);
			$oMsg->data['game']['xml'] = "<form>
	<label>Edit Game</label>
	<action>index.php?action=updateGame&amp;module=game&amp;id=".$oMsg->request['id']."</action>
	<method>POST</method>

	<homeTeam>
		<id>".$row['g_hometeam_id']."</id>
		<score>".$row['g_hometeam_score']."</score>
	</homeTeam>

	<awayTeam>
		<id>".$row['g_awayteam_id']."</id>
		<score>".$row['g_awayteam_score']."</score>
	</awayTeam>
	<round>".$row['g_round']."</round>
	<date>".substr($row['g_date'],0,-3)."</date>
	<matchTypeId>".$row['g_mt_id']."</matchTypeId>";
			
			//Get a list of all teams
			$teamSql = "SELECT * FROM team ORDER BY team_name ASC";
			$teamRs = $oMsg->conn->query($teamSql);
			if (PEAR::isError($teamRs)) {
				Log::getInstance()->write("Could not query teams from database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not query team from database. ".$teamRs->getMessage(), "error");
			}
			while ($row = $teamRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$oMsg->data['game']['xml'] .= "
	<team>
		<id>".$row['team_id']."</id>
		<name>".$row['team_name']."</name>
	</team>";
			}
			
			//Get a list of all matchTypes
			$mtSql = "SELECT * FROM matchtype ORDER BY mt_name ASC";
			$mtRs = $oMsg->conn->query($mtSql);
			if (PEAR::isError($mtRs)) {
				Log::getInstance()->write("Could not query match types from database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not query match types from database. ".$mtRs->getMessage(), "error");
			}
			while ($row = $mtRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$oMsg->data['game']['xml'] .= "
	<matchType>
		<id>".$row['mt_id']."</id>
		<name>".$row['mt_name']."</name>
	</matchType>";
			}
			$oMsg->data['game']['xml'] .= "
</form>";
			$oMsg->result = "editGameForm";
			}
	}
	
	public function updateGame( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			settype($oMsg->request['id'], "integer");
			settype($oMsg->request['homeTeamId'], "integer");
			settype($oMsg->request['awayTeamId'], "integer");
			if ($oMsg->request['homeTeamScore'] != "") {
				settype($oMsg->request['homeTeamScore'], "integer");
			} else { $oMsg->request['homeTeamScore'] = 'null'; }
			if ($oMsg->request['awayTeamScore'] != "") {
				settype($oMsg->request['awayTeamScore'], "integer");
			} else { $oMsg->request['awayTeamScore'] = 'null'; }
			settype($oMsg->request['gameRoundNum'], "integer");
			settype($oMsg->request['matchTypeId'], "integer");
		
			$sql = sprintf ("UPDATE game SET
				g_hometeam_id = %d,
				g_awayteam_id = %d,
				g_hometeam_score = %s,
				g_awayteam_score = %s,
				g_mt_id = %d,
				g_round = %d,
				g_date = '%s'
				WHERE g_id = %d",
				$oMsg->request['homeTeamId'],$oMsg->request['awayTeamId'],
				$oMsg->request['homeTeamScore'], $oMsg->request['awayTeamScore'],
				$oMsg->request['matchTypeId'], $oMsg->request['gameRoundNum'],
				$oMsg->request['matchDate'], $oMsg->request['id']);
			$rs = $oMsg->conn->exec($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not update game. ".mysql_error(),"error");
				return $this->insert_error($oMsg, "Could not update game. ".$rs->getMessage(),"error");
			}
			
			$oMsg->result = "updateGameSuccess";
			$oMsg->message = "One game updated succesfully.";
		}
	}
	
	public function newGame ( Message $oMsg ) {
		if ($this->validateUser($oMsg)) {
			
			$oMsg->data['game']['xml'] = "<form>
	<label>New Game</label>
	<action>index.php?action=insertGame&amp;module=game</action>
	<method>POST</method>";
			//Get a list of all teams
			$teamSql = "SELECT * FROM team ORDER BY team_name ASC";
			$teamRs = $oMsg->conn->query($teamSql);
			if (PEAR::isError($teamRs)) {
				Log::getInstance()->write("Could not query teams from database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not query team from database. ".$teamRs->getMessage(), "error");
			}
			while ($row = $teamRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$oMsg->data['game']['xml'] .= "
	<team>
		<id>".$row['team_id']."</id>
		<name>".$row['team_name']."</name>
	</team>";
			}
			
			//Get a list of all matchTypes
			$mtSql = "SELECT * FROM matchtype ORDER BY mt_name ASC";
			$mtRs = $oMsg->conn->query($mtSql);
			if (PEAR::isError($mtRs)) {
				Log::getInstance()->write("Could not query match types from database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not query match types from database. ".$mtRs->getMessage(), "error");
			}
			while ($row = $mtRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$oMsg->data['game']['xml'] .= "
	<matchType>
		<id>".$row['mt_id']."</id>
		<name>".$row['mt_name']."</name>
	</matchType>";
			}
			$oMsg->data['game']['xml'] .= "
</form>";
			$oMsg->result = "editGameForm";
		}
	}
	
	public function insertGame( Message $oMsg ) {
		if( $this->validateUser($oMsg)) {
			
			
			
			//
			//NOTE SCORES ARE NOT TAKEN INTO ACCOUNT HERE.
			//NEED TO FIND A WAY TO REMOVE THE SCORES FROM THE EDIT XSL.
			//PROBABLY EASIEST TO JUST WRITE A FRESH XSL WITH THE RELEVANT BITS REMOVED.
			//
			
			settype($oMsg->request['homeTeamId'], "integer");
			settype($oMsg->request['awayTeamId'], "integer");
			settype($oMsg->request['matchTypeId'], "integer");
			settype($oMsg->request['gameRoundNum'], "integer");
			
			$sql = sprintf("INSERT INTO game (g_hometeam_id, g_awayteam_id, g_mt_id, g_date, g_round)
				VALUES (%d, %d, %d, '%s', %d)",
				$oMsg->request['homeTeamId'], $oMsg->request['awayTeamId'],
				$oMsg->request['matchTypeId'], $oMsg->request['matchDate'],
				$oMsg->request['gameRoundNum']);
			
			$rs = $oMsg->conn->exec($sql);
			if (PEAR::isError($rs)) {
				Log::getInstance()->write("Could not add new game to the database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Could not add new game to database. ".$rs->getMessage(), "error");
			} else {
				$oMsg->result = "insertSuccess";
				$oMsg->message = "One game inserted Successfully.";
			}
		}
	}
	
	public function confirmDeleteGame ( Message $oMsg ) {
			if ($this->validateUser( $oMsg ) ) {
				
				settype($oMsg->request['id'], "integer");
				$sql = sprintf("SELECT
t1.team_name as home,
t2.team_name as away
from
game g, team t1, team t2
where
g.g_hometeam_id = t1.team_id
AND
g.g_awayteam_id = t2.team_id
AND
g.g_id = %d", $oMsg->request['id']);
				$rs = $oMsg->conn->query( $sql );
				if (PEAR::isError( $rs ) ) {
					Log::getInstance()->write("Error finding game to delete. ".mysql_error(), "error");
					return $this->insert_error( $oMsg, "Error finding game to delete. ".$rs->getMessage(), "error" );
				} else {
					$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
					$xml = "<form>
	<label>Are you sure you wish to delete ".$row['home']." vs ".$row['away']." ?</label>
	<action>index.php?action=deleteGame&amp;module=game</action>
	<method>post</method>
	<submit>
		<name>confirmDeleteGame</name>
		<value>YES</value>
	</submit>
	<submit>
		<name>confirmDeleteGame</name>
		<value>NO</value>
	</submit>
	<input>
		<type>hidden</type>
		<name>id</name>
		<value>".$oMsg->request['id']."</value>
	</input>
</form>";
					$oMsg->data['game']['xml'] = $xml;
					$oMsg->result="confirmDeleteGameForm";
				}
			}
		}
		
	public function deleteGame( Message $oMsg ) {
		if ($this->validateUser( $oMsg )) {
			if ($oMsg->request['confirmDeleteGame'] == "YES") {
				
				settype($oMsg->request['id'], "integer");
				
				$sql = sprintf("delete from game where g_id = %d", $oMsg->request['id']);
				
				$rs = $oMsg->conn->exec($sql);
				if (PEAR::isError($rs)) {
					Log::getInstance()->write("Could not delete game. ".mysql_error(), "error");
					return $this->insert_error($oMsg, "Could not delete game. ".$rs->getMessage(), "error");
				}
				
				$oMsg->result = "deleteGameSuccess";
				$oMsg->message = "One game deleted successfully.";
			} else {
				$oMsg->result = "didNotConfirmDelete";
				$oMsg->message = "You decided not to delete anything at this time.";
			}
		}
	}
}
?>