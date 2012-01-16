<?php
Log::getInstance()->write(__FILE__." loaded", "debug");
require_once ("modules/common/model.class.php");

class GetPickGamesAction extends Model {
	
	public function getGamesForMatchType( Message $oMsg ) {
		
		if ( $this->validateUser($oMsg)) {
			
			if( array_key_exists( "matchTypeId", $oMsg->request ) ) {
				
				//require_once("modules/pick/functions/pearDate.class.php");
				require_once("modules/common/xmlwriter.class.php");
				$matchTypeId = $oMsg->request['matchTypeId'];
				$matchTypeName = $oMsg->request['matchTypeName'];
				$roundNumber = $oMsg->request['roundNumber'];
				$bonusName = $oMsg->request['bonusName'];
				
				$userId = $oMsg->request['userId'];
				
				$sql = sprintf("SELECT g.*, p.p_margin_id AS pickedMargin FROM games_list g
					LEFT OUTER JOIN pick AS p
					ON g.id = p.p_game_id AND p.p_user_id = %d 
					WHERE g.roundnum = %d and g.mtid = %d order by g.gdate ASC", $userId, $roundNumber, $matchTypeId);
				Log::getInstance()->write($sql, "debug");
				$rs = $oMsg->conn->query($sql);
				if( PEAR::isError($rs)) {
					Log::getInstance()->write("Could not query picks for matchtpye. ", "error");
					return $this->insert_error($oMsg,"Could not query picks for matchtype. <br/><b>".mysql_error()."</b>", "error");
				}
				
				$xml = new XMLWriterObj( );
				$xml->push("matchTypePicks");
				$xml->element("matchTypeName", strtoupper($matchTypeName));
				$xml->element("roundNumber", $roundNumber);
				$xml->element("bonusName", $bonusName);
				
				$c = 1;
				while ($row[$c] = $rs->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
					$xml->push("game");
						$xml->element('hometeam', $row[$c]['home']);
						$xml->element('awayteam', $row[$c]['away']);
						$xml->element('date', substr($row[$c]['gdate'],0,16));
						$xml->element('picked', $row[$c]['pickedmargin']);
						$xml->element('gameId', $row[$c]['id']);
						$xml->element('matchTypeId', $row[$c]['mtid']);
					$xml->pop();
					$c++;
				}
				//Get margin names for the headings.
				$sql = sprintf("select m.m_name, m.m_id from margin m, matchtype_margin mtm where m.m_id = mtm.m_id and mtm.mt_id = %d order by m.m_l_value DESC",$matchTypeId);
				$rsM = $oMsg->conn->query($sql);
				if(PEAR::isError($rsM)){
					Log::getInstance()->write("Could not query database for margins. ".$rs->getMessage(),"debug");
					return $this->insert_error($oMsg, "Could not query database for margins. ".$rs->getMessage(),"error");
				}
				while($rowM = $rsM->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
					$xml->push("margin");
						$xml->element("name", $rowM['m_name']);
						$xml->element("id",$rowM['m_id']);
					$xml->pop();
				}
				$xml->pop();
				
				$oMsg->data['xml'] = $xml->getXml();
				$oMsg->result = "returnXmlData";
				
			} else {
				Log::getInstance()->write("No match type id passed to getGames Action", "error");
				return $this->insert_error($oMsg,"No match type id was passed to the getGamesForMatchType action", "error");
			}
			
		}
	}
	
}
?>