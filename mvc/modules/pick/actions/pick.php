<?php
Log::getInstance()->write(__FILE__." loaded", "debug");

require_once ("modules/common/model.class.php");
require_once ("modules/pick/functions/pearDate.class.php");
require_once("HTTP/Request.php");

class PickAction extends Model {
	public function getPicksForUser(Message $oMsg ) {
		if( $this->validateUser($oMsg) ) {
			/**
			 * Here we need to find the match types available for games
			 * played over the next weekend.  Then for every type of game
			 * a call back to the server is made so a new MVC can produce and return
			 * the xml data we need.
			 */
			$dm = new DateHandler();
			$req =& new HTTP_Request($_SERVER['PHP_SELF']);
			$req->setMethod(HTTP_REQUEST_METHOD_POST);
			
			$defaultParams = array(
				"requestFilter"=>"logIn",
				"loginUserName"=>"SYSTEM",
				"loginUserPass"=>"SysteM",
				"action" => "getPickGames",
				"module" => "pick",
				"userId" => $_SESSION['token']);
			
			//Get a list of upcomming rounds.
			$rounds = array();
			$rSql = "select distinct g_round from game where g_date > now() order by g_round asc";
			$rRs = $oMsg->conn->query($rSql);
			if (!PEAR::isError($rRs)) {
				while ($rRow = $rRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					$rounds[] = $rRow['g_round'];
				}
			} else {
				Log::getInstance()->write("Error collecting future round numbers from database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Error collecting future round numbers from database. Contact the adminstrator", "error");
			}
			
			//Get a list of previous rounds.
			$prevRounds = array();
			$prSql = "select distinct g_round from game where g_date < now() order by g_round desc";
			$prRs = $oMsg->conn->query($prSql);
					if (!PEAR::isError($prRs)) {
				while ($prRow = $prRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					$prevRounds[] = $prRow['g_round'];
				}
			} else {
				Log::getInstance()->write("Error collecting previous round numbers from database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Error collecting previous round numbers from database. Contact the adminstrator", "error");
			}
			
			//Have we received a request for a specific round or not.
			if (isset($oMsg->request['round'])) {
				$sql = sprintf("select distinct g.mtid, m.mt_name, b.bonus_name, g.roundnum
from games_list g left join bonus b on g.roundnum = b.bonus_round, matchtype m
where g.mtid = m.mt_id and g.gdate > '%s' and g.roundnum = '%s'", $dm->getNextFridayMysql(), $oMsg->request['round']);
			} else {
				$sql = sprintf("select distinct g.mtid, m.mt_name, b.bonus_name, g.roundnum
from games_list g left join bonus b on g.roundnum = b.bonus_round, matchtype m
where g.mtid = m.mt_id and g.gdate between '%s' and '%s' order by g.mtid",
				$dm->getNextFridayMysql(), $dm->getNextSundayMysql() );
			}
			
			
			$rs = $oMsg->conn->query($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not query database for matchtypes.".mysql_error(),"error");
				return $this->insert_error($oMsg, "Error with query. </br>SQL::>".$sql."</br>Mysql Error::".mysql_error(), "error");
			} else {
				if($rs->numRows()<1) {
					$oMsg->message = "No games were found for your filter.";
				}
				$oMsg->data['text'] = "<?xml version='1.0'?>";
				$oMsg->data['text'] .= "<picksTable>";
				if (!isset($oMsg->message)) {
					$oMsg->data['text'] .= "<message>Please select your picks.</message>";
				} else {
					$oMsg->data['text'] .= "<message>".$oMsg->message."</message>";
				}
				while ($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC)) {

					foreach( $defaultParams as $k => $v ) {
						$req->addPostData($k, $v);
					}
					$req->addPostData("matchTypeId", $row['mtid']);
					$req->addPostData("matchTypeName", $row['mt_name']);
					$req->addPostData("roundNumber", $row['roundnum']);
					$req->addPostData("bonusName", $row['bonus_name']);
					
					if(!PEAR::isError($req->sendRequest()) ) {
						$oMsg->data['text'] .= substr($req->getResponseBody(),23);
					} else {
						Log::getInstance()->write("Could not create HTTP_Connection to server. ", "error");
						return $this->insert_error($oMsg,"Could not create http connection to server. ","error");
					}
					$req->clearPostData();
				}
			}
			foreach ($rounds as $r => $v) {
				$oMsg->data['text'] .= "<round>".$v."</round>";
			}
			foreach ($prevRounds as $r => $v) {
				$oMsg->data['text'] .= "<prevRound>".$v."</prevRound>";
			}
			$oMsg->data['text'] .= "</picksTable>";
			$oMsg->result = 'listPicks';
//Debug::showStack($oMsg->data['text'], "XML");
		}
	}
	
	public function submitPicks( Message $oMsg ) {
		if( $this->validateUser($oMsg)) { //User has permission
			if ( !isset( $oMsg->request[ 'selectPicksSubmit' ] ) ) { //Must come from the select picks form and not from a backlink.
				$oMsg->message = "These picks have been submitted.  Make changes if you wish and resubmit.";
				$oMsg->result = "picksAlreadyPlaced";
			} else {
				$picks = $oMsg->request['margin']; //An array of picks identified by game id
				$paidStake = $oMsg->request['paidStake'];
				$userId = $_SESSION['token']; //So thats where the userId lives... :)
				if ($paidStake < 1) {
					Log::getInstance()->write("User with ID: ".$userId. " attempted a pick without confirming that they paid.", "error");
					return ($this->insert_error($oMsg, "You may not place picks unless you have confirmed that you have paid your $2.00. <br/>A log of your attempt to cheat has been made.", "error"));
				}
				foreach( $picks as $game => $margin) {
					$roundNumRs = $oMsg->conn->query(sprintf("select g_round from game where g_id = %d", $game));
					$roundAr = $roundNumRs->fetchRow(MDB2_FETCHMODE_ASSOC);
					$round = $roundAr['g_round'];
					$checkSql = sprintf("SELECT p_id from pick where p_game_id = %d and p_user_id = %d", $game, $userId);
					$checkRs = $oMsg->conn->query($checkSql);
					if ($checkRs->numRows() == 1) {
						$checkRow = $checkRs->fetchRow(MDB2_FETCHMODE_ASSOC);
						$sql = sprintf("UPDATE pick SET p_user_id = %d, p_game_id = %d, p_margin_id = %d, p_round_num = %d where p_id = %d", $userId, $game, $margin, $round, $checkRow['p_id']);
					} else {
						if (PEAR::isError($roundRS)) {
							Log::getInstance()->write("Could not find round info for game. ".mysql_error(), "error");
							return $this->insert_error($oMsg,"A database error has occurred. Please contact your sys admin.", "error");
						}
						$sql = sprintf("INSERT INTO pick VALUES( null, %d, %d, %d, now(), %d )", $userId, $game, $margin, $round );
					}
					$checkRs->free();
					$rs = $oMsg->conn->exec($sql);
					if (PEAR::isError($rs)) {
						Log::getInstance()->write("Error recording picks. ".mysql_error(), "error");
						return $this->insert_error($oMsg,"Error recording picks.  Please report this error to System Admin.", "error"); 
					}
				}
				$oMsg->message = "Thankyou, Your picks have been updated.";
				$oMsg->result = "good_picks_submit";
			}
			
			/**
			 * A temporary comment for testing purposes.
			 */
		}
	}

	public function getPreviousPicks(Message $oMsg ) {
		if( $this->validateUser($oMsg) ) {
			/**
			 * Here we need to find the match types available for games
			 * played over the next weekend.  Then for every type of game
			 * a call back to the server is made so a new MVC can produce and return
			 * the xml data we need.
			 */
			$dm = new DateHandler();
			$req =& new HTTP_Request($_SERVER['PHP_SELF']);
			$req->setMethod(HTTP_REQUEST_METHOD_POST);
			
			$defaultParams = array(
				"requestFilter"=>"logIn",
				"loginUserName"=>"SYSTEM",
				"loginUserPass"=>"SysteM",
				"action" => "getPickGames",
				"module" => "pick",
				"userId" => $_SESSION['token']);
			
			//Get a list of upcomming rounds.
			$rounds = array();
			$rSql = "select distinct g_round from game where g_date < now()";
			$rRs = $oMsg->conn->query($rSql);
			if (!PEAR::isError($rRs)) {
				while ($rRow = $rRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
					$rounds[] = $rRow['g_round'];
				}
			} else {
				Log::getInstance()->write("Error collecting previous round numbers from database. ".mysql_error(), "error");
				return $this->insert_error($oMsg, "Error collecting previous round numbers from database. Contact the adminstrator", "error");
			}
			
			$sql = sprintf("select distinct g.mtid, m.mt_name, b.bonus_name, g.roundnum
from games_list g left join bonus b on g.roundnum = b.bonus_round, matchtype m
where g.mtid = m.mt_id and g.gdate < now() and g.roundnum = '%s'", $oMsg->request['round']);
						
			$rs = $oMsg->conn->query($sql);
			if(PEAR::isError($rs)) {
				Log::getInstance()->write("Could not query database for matchtypes.".mysql_error(),"error");
				return $this->insert_error($oMsg, "Error with query. </br>SQL::>".$sql."</br>Mysql Error::".mysql_error(), "error");
			} else {
				if($rs->numRows()<1) {
					$oMsg->message = "No games were found for your filter.";
				}
				$oMsg->data['text'] = "<?xml version='1.0'?>";
				$oMsg->data['text'] .= "<picksTable>";
				if (!isset($oMsg->message)) {
					$oMsg->data['text'] .= "<message>Your previous picks.</message>";
				} else {
					$oMsg->data['text'] .= "<message>".$oMsg->message."</message>";
				}
				while ($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC)) {

					foreach( $defaultParams as $k => $v ) {
						$req->addPostData($k, $v);
					}
					$req->addPostData("matchTypeId", $row['mtid']);
					$req->addPostData("matchTypeName", $row['mt_name']);
					$req->addPostData("roundNumber", $row['roundnum']);
					$req->addPostData("bonusName", $row['bonus_name']);
					
					if(!PEAR::isError($req->sendRequest()) ) {
						$oMsg->data['text'] .= substr($req->getResponseBody(),23);
					} else {
						Log::getInstance()->write("Could not create HTTP_Connection to server. ", "error");
						return $this->insert_error($oMsg,"Could not create http connection to server. ","error");
					}
					$req->clearPostData();
				}
			}
			foreach ($rounds as $r => $v) {
				$oMsg->data['text'] .= "<round>".$v."</round>";
			}
			
			$oMsg->data['text'] .= "</picksTable>";
			$oMsg->result = 'listPicks';
//Debug::showStack($oMsg->data['text'], "XML");
		}
	}
	public function confirmDeletePicks( Message $oMsg ) {
		if ( $this->validateUser( $oMsg ) ) {
			$userId = $_SESSION['token'];
			$round = $oMsg->request['round'];
			$xml = "<form>
	<label>Are you sure you wish to delete your picks for round ".$round."?</label>
	<action>index.php?action=deletePicks&amp;module=pick</action>
	<method>post</method>
	<submit>
		<name>confirmDeletePicks</name>
		<value>YES</value>
	</submit>
	<submit>
		<name>confirmDeletePicks</name>
		<value>NO</value>
	</submit>
	<input>
		<type>hidden</type>
		<name>round</name>
		<value>".$oMsg->request['round']."</value>
	</input>
</form>";
			$oMsg->data['xml'] = $xml;
			$oMsg->result = "showConfirmDeletePicks";
		}
	}
	public function deletePicks( Message $oMsg ) {
		if ( $this->validateUser($oMsg)) {
			if ( $oMsg->request['confirmDeletePicks'] == "YES" ) {
				$userId = $_SESSION['token'];
				$round = $oMsg->request['round'];
				$sql = sprintf( "Delete from pick where p_user_id = '%s' and p_round_num = '%s'", $userId, $round );
				$rs = $oMsg->conn->exec($sql);
				if( PEAR::isError($rs)) {
					Log::getInstance()->write("Error deleteing picks from database. ".mysql_error(), "error");
					return $this->insert_error($oMsg, "There was a database error. Please contact your administrator.", "error");
				}
				
				$oMsg->message = "Picks for round ".$round." deleted!";
				$oMsg->result = "picksDeleted";
			} else {
				$oMsg->message = "You chose not to delete your picks in the end.";
				$oMsg->result = "picksDeleted";
			}
		}
	}
}
?>