<?php
Log::getInstance()->write(__FILE__." loaded.", "debug");
require_once ("modules/common/model.class.php");


class RankingAction extends Model {

	public function leaderboard( Message $oMsg ) {
		
		//Get the leaderboard.
		$sql = "Select * from leaderboard";
		$rs = $oMsg->conn->query($sql);
		if (PEAR::isError($rs)) {
			Log::getInstance()->write("Error retreiving leaderboard from database. ".mysql_error(), "error");
			return $this->insert_error($oMsg, "There has been a database error.  Please contact your administrator.","error");
		}
		$leaderBoard = array();
		while ($r = $rs->fetchRow(MDB2_FETCHMODE_ASSOC)) { $leaderBoard[] = $r; }
		
		//Get all rounds + bonus info for each round.
		$roundsRs = $oMsg->conn->query("select g.g_round as round, b.bonus_name as bonus from game g left join bonus b on g.g_round = b.bonus_round group by round order by round");
		if (PEAR::isError($roundsRs)) {
			Log::getInstance()->write("Error retreiving round and bonus info from database. ".mysql_error(), "error");
			return $this->insert_error($oMsg, "There has been a database error.  Please contact your administrator.","error");
		}

		//Get takes
		$takeRs = $oMsg->conn->query("select g_round as round, count(distinct p_user_id) * 2 as take from pick right join game on p_game_id = g_id group by round");
		if (PEAR::isError($takeRs)) {
			Log::getInstance()->write("Error retreiving take info from database. ".mysql_error(), "error");
			return $this->insert_error($oMsg, "There has been a database error.  Please contact your administrator.","error");
		}
				
		//Get last round.
		$lastRoundRs = $oMsg->conn->query("select max(roundnum) as lastRound from games_list where scored = 1");
		if (PEAR::isError($lastRoundsRs)) {
			Log::getInstance()->write("Error retreiving last round from database. ".mysql_error(), "error");
			return $this->insert_error($oMsg, "There has been a database error.  Please contact your administrator.","error");
		}
		$lastRoundRow = $lastRoundRs->fetchRow(MDB2_FETCHMODE_ASSOC);
		$lastRound = $lastRoundRow['lastround'];
		
		//Get all users from leaderboard
		$usersRs = $oMsg->conn->query("select distinct p.p_user_id as userId, concat(u.user_first_name, ' ', u.user_last_name) as userName from pick p, auth_user u where p.p_user_id = u.user_id and u.user_id > 1"); //User can not be system administrator
		if (PEAR::isError($usersRs)) {
			Log::getInstance()->write("Error retreiving user info from database. ".mysql_error(), "error");
			return $this->insert_error($oMsg, "There has been a database error.  Please contact your administrator.","error");
		}
		$users = array();
		while ($u = $usersRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
			$users[$u['userid']] = $u['username'];
		}
//Debug::showStack($leaderBoard, "Users Array");
		//Build xml.
		require_once("modules/common/xmlwriter.class.php");
		$xmlObj = new XMLWriterObj();
		$xmlObj->push("rank"); //Root node
		
		//Iterate through rounds
		$rounds = array();
		while ($r = $roundsRs->fetchRow(MDB2_FETCHMODE_ASSOC)) { 
			$rounds[] = $r;
			$xmlObj->push("round");
				$xmlObj->element("number", $r['round']);
				$xmlObj->element("bonusName", $r['bonus']);
			$xmlObj->pop();
		}
		while ($t = $takeRs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
			$xmlObj->element("take", $t['take'], array('round'=>$t['round']));
		}
		
		$xmlObj->element("numberofrounds", count($rounds));
		
		$roundScores = array();
		foreach ($leaderBoard as $l => $d) {
			$roundScores[$d['user_id']][$d['round']] = $d['score'];
		}
		
		foreach ($users as $u => $d) {
			$xmlObj->push("user");
				$xmlObj->element("name", ucwords($d));
				for ( $lp = 0; $lp < count($rounds); $lp++ ) {
					$xmlObj->push("round");
						$xmlObj->element("number", $lp+1);
						$xmlObj->element("score", ($roundScores[$u][$lp+1] ) ? $roundScores[$u][$lp+1]:0);
						$xmlObj->element("score_colour", (isset($roundScores[$u][$lp+1])) ? "#000000" : "#ff0000"); //We need 0's for users who did not make picks to be in red.
					$xmlObj->pop();
				}
				$xmlObj->push("total");
					$xmlObj->element("score", array_sum($roundScores[$u]) );
				$xmlObj->pop();
				
			$xmlObj->pop();
		}
		if ( isset ( $oMsg->request['sortround'] ) ) {
			if ( $oMsg->request['sortround'] == 'total' ) {
				$xmlObj->element('lastRound', 'total' );
				$xmlObj->element('message', 'Sorted by TOTAL score');
			} else {
				$xmlObj->element('lastRound', $oMsg->request['sortround'] );
				$xmlObj->element('message', 'Sorted by scores on round '.$oMsg->request['sortround']);
			}
		} else {
			$xmlObj->element('lastRound', $lastRound );
			$xmlObj->element('message', 'Sorted by scores on round '.$lastRound);
		}
		
		$xmlObj->pop(); //Close root node.
		
//Debug::showStack($xmlObj, "XML Object");
		$oMsg->data['xml'] = $xmlObj->getXml();
		$oMsg->result = "displayLeaderboard";
	}
}

?>