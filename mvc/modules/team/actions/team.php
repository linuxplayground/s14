<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ('modules/common/model.class.php');

class TeamAction extends Model {

	public $teamDataMap;
	
	/*
	* Describe the team fields and map them to english names.
	* teamDataMap = array ( Real Name => Team Name )
	*/
	
	public function __Construct ( ) {
		
		$this->teamDataMap = array (
			"id" => "team_id",
			"Name" => "team_name",
			"City" => "team_city"
		);
	}

	public function listTeam( $oMsg ) {
	
		
		
		/* Check the request parameters for ordering information */
		
		if ( (array_key_exists ('o', $oMsg->request ) ) && (array_key_exists ( $oMsg->request['o'], $this->teamDataMap ) ) ) {
			$order = $this->teamDataMap[$oMsg->request['o']];
			$orderName = $oMsg->request['o'];
		} else {
			$order = 'team_name';
			$orderName = 'Name';
		}
		
		
		$sql = sprintf("SELECT * FROM team order by %s ASC", $order );
		$rs = $oMsg->conn->query ($sql);
		
		if (PEAR::isError ($rs) ) {
			Log::getInstance()->write ("error querying database ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
			return $this->insert_error($oMsg, "<b>Framework Error:</b> error querying database <br /><b> ".$rs->getMessage()." </b><br/><b>MySQL Error:</b> ".mysql_error()." <br/><b>SQL Statement:</b> ".$sql, "error");
		} else {
			Log::getInstance()->write("queried database successfully - ".$sql, "debug");
			
			require_once("modules/common/xmlwriter.class.php");
			$libxml = new XmlWriterObj( );
			
			$libxml->push('page');
			if (! isset( $oMsg->message ) ) {
				$oMsg->message = 'Teams listed by '.$orderName. ' ascending.';
			}
			$libxml->element('message', $oMsg->message );
			
			$libxml->push('teams');
			
			while ($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC) ) {
				$libxml->push( 'team' );
				$libxml->element( 'id', $row['team_id'] );
				$libxml->element( 'name', $row['team_name'] );
				$libxml->element( 'city', $row['team_city'] );
				$libxml->pop();
			}
			$libxml->pop();
			$libxml->pop();
		}
		$oMsg->data['listTeam']['xml'] = $libxml->getXml();
		$oMsg->result = "display_team_list";
	}
	
	/*
	* Function generates xml data for insert team form
	* response is a view that handles the xml and renders an insert
	* team form.
	*/
	public function newTeam( $oMsg ) {
		
		
		if ($this->validateUser($oMsg)) {
		
			require_once("modules/common/xmlwriter.class.php");
			$libxml = new XmlWriterObj( );
			
			$libxml->push( 'form' );
				$libxml->element( 'action', 'index.php?action=insertTeam&module=team' );
				$libxml->element( 'method', 'POST' );
				$libxml->element( 'label', 'New Team' );
				$libxml->push( 'input' );
					$libxml->element( 'type', 'text' );
					$libxml->element( 'name', 'newTeamName' );
					$libxml->element( 'label', 'Name' );
				$libxml->pop();
				$libxml->push( 'input' );
					$libxml->element( 'type', 'text' );
					$libxml->element( 'name', 'newTeamCity' );
					$libxml->element( 'label', 'City' );
				$libxml->pop();
				$libxml->push( 'submit' );
					$libxml->element( 'name', 'newTeamSubmit' );
					$libxml->element( 'value', 'Add...' );
				$libxml->pop();
			$libxml->pop();
			
			$oMsg->data['newTeam']['xml'] = $libxml->getXml();
			$oMsg->result = 'display_new_form';
		}
	}

	/*
	* Function to insert a new team into the database.
	* This function will have to confirm the validity of user data before insertion into the database
	* This function will also have to santise the input data before insertion to prevent
	* against SQL Injection
	*/
	
	public function insertTeam( $oMsg ) {
		
		
		if ($this->validateUser($oMsg) ) {
		
			
			$sql = sprintf ( "INSERT INTO team (team_name, team_city) VALUES ('%s', '%s' )",
				$oMsg->request['newTeamName'], $oMsg->request['newTeamCity'] );
			
			$rs = $oMsg->conn->exec($sql);
			if (PEAR::isError ($rs) ) {
				Log::getInstance()->write ("error inserting data into database ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
				return $this->insert_error($oMsg, "<b>Framework Error:</b> error inserting data into database <br /><b> ".$rs->getMessage()." </b><br/><b>MySQL Error:</b> ".mysql_error()." <br/><b>SQL Statement:</b> ".$sql, "insert_data_error");
			} else {
				$oMsg->message = $rs. " rows inserted successfully.";
				$oMsg->result = 'insert_data_success';
			}
		}
	}
	
	/*
	* Function to check that it is ok to delete the team
	* Add confirmation to the message so that the controller will do its job
	* But as a check the action will also do its job.
	* Need to add the id to the post and autosubmiit a form.
	*/
	
	public function confirmDelete ( $oMsg ) {
		
		if($this->validateUser($oMsg) ) {
			
			$sql = sprintf("SELECT team_name FROM team WHERE team_id = '%s' ",
				$oMsg->request['id'] );
				
			$rs = $oMsg->conn->query( $sql );
			
			if (PEAR::isError($rs) ) {
				Log::getInstance()->write($rs->getMessage(), "error");
				$this->insert_error( $rs->getMessage(), "confirm_delete_error" );
			} else {
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$libxml->push( 'form' );
					$libxml->element( 'action', 'index.php?action=deleteTeam&module=team' );
					$libxml->element( 'method', 'POST' );
					$libxml->element( 'label', 'Confirm Delete Team '.$row['team_name'].'Beware that any games played by this team will also be deleted.' );
					
					$libxml->push( 'submit' );
						$libxml->element( 'name', 'deleteTeamConfirmation' );
						$libxml->element( 'value', 'YES' );
					$libxml->pop();
					$libxml->push( 'submit' );
						$libxml->element( 'name', 'deleteTeamConfirmation' );
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

	/*
	* Function to delete a team
	*/
	
	public function deleteTeam( Message $oMsg ) {
		
		if($this->validateUser($oMsg) ) {
			
			if ( $oMsg->request['deleteTeamConfirmation'] == "YES" ) {
				settype($oMsg->request['id'], "integer");
				$sql = sprintf("DELETE FROM team WHERE team_id = %d ",	$oMsg->request['id'] );
				$rs = $oMsg->conn->exec( $sql );
				if (PEAR::isError ($rs) ) {
					Log::getInstance()->write ("error deleting from database ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
					return $this->insert_error($oMsg, "<b>Framework Error:</b> error deleting from database <br /><b> ".$rs->getMessage()." </b><br/><b>MySQL Error:</b> ".mysql_error()." <br/><b>SQL Statement:</b> ".$sql, "error");
				} else {
					$sql = sprintf("DELETE FROM game WHERE g_hometeam_id = %d OR g_awayteam_id = %d",
							$oMsg->request['id'], 	$oMsg->request['id'] );
					$rs = $oMsg->conn->exec($sql);
					if (PEAR::isError($rs)) {
						Log::getInstance()->write("error deleteing games played by deleted team. ".mysql_error(), "error");
						return $this->insert_error( $oMsg, "error deleting games played by deleted team. ".$rs->getMessage(),"error");
					}
					$oMsg->message = $rs. " games deleted successfully. because they were played by a team you deleted.";
					$oMsg->result = 'delete_success';
				}

			} else {
				$oMsg->result = 'delete_confirmation_no';
				$oMsg->message = "You decided not to proceed with the delete";
			}
			
		}
	}
	
	/*
	* Function to gather team information and display team edit form
	*/
	
	public function editTeam( $oMsg ) {
		
		
		if($this->validateUser($oMsg) ) {
			
			$qSql = sprintf( "SELECT * FROM team WHERE team_id = '%s' ",
				$oMsg->request['id'] );
				
			$qRs = $oMsg->conn->query( $qSql );
			
			if (PEAR::isError ($qRs) ) {
				Log::getInstance()->write ("error querying team information from database ".$qRs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
				return $this->insert_error($oMsg, "<b>Framework Error:</b> error querying team information from database<br /><b> ".$qRs->getMessage()."</b><br /><b>MYSQL Error:</b> ".mysql_error()."<br/><b>SQL Statement:</b> ".$qSql, "data_error");
			} else {
				Log::getInstance()->write ("queried database successfully", "debug");
				
				$qRow = $qRs->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				require_once("modules/common/xmlwriter.class.php");
				$libxml = new XmlWriterObj( );
				
				$libxml->push( 'form' );
					$libxml->element( 'action', 'index.php?action=updateTeam&module=team' );
					$libxml->element( 'method', 'POST' );
					$libxml->element( 'label', 'Edit Team' );
					$libxml->push( 'input' );
						$libxml->element( 'type', 'text' );
						$libxml->element( 'name', 'editTeamName' );
						$libxml->element( 'label', 'Name' );
						$libxml->element( 'value', $qRow['team_name'] );
					$libxml->pop();
					$libxml->push( 'input' );
						$libxml->element( 'type', 'text' );
						$libxml->element( 'name', 'editTeamCity' );
						$libxml->element( 'label', 'City' );
						$libxml->element( 'value', $qRow['team_city'] );
					$libxml->pop();
					$libxml->push( 'submit' );
						$libxml->element( 'name', 'editTeamSubmit' );
						$libxml->element( 'value', 'Update...' );
					$libxml->pop();
					$libxml->push( 'input' );
						$libxml->element( 'type', 'hidden' );
						$libxml->element( 'name', 'editTeamId' );
						$libxml->element( 'label', '' );
						$libxml->element( 'value', $qRow['team_id'] );
					$libxml->pop();
				$libxml->pop();
				
				$oMsg->data['editTeam']['xml'] = $libxml->getXml();
				$oMsg->result = 'data_found';
			}
		}
	}
	
	/*
	* Function to update the team information with post data.
	*/
	
	public function updateTeam( $oMsg ) {
		
		
		if($this->validateUser($oMsg) ) {
			$sql = sprintf( "update team set team_name = '%s', team_city='%s' where team_id = '%s'",
				$oMsg->request['editTeamName'], $oMsg->request['editTeamCity'], $oMsg->request['editTeamId'] );
			
			$rs = $oMsg->conn->exec ($sql);
			if ( PEAR::isError( $rs ) ) {
				Log::getInstance()->write ("error updating team ".$rs->getMessage()." MYSQL ERRROR::".mysql_error()." SQL::".$sql."\"", "error" );
				return $this->insert_error($oMsg, "<b>Framework Error:</b> error updating<br /><b> ".$rs->getMessage()."</b><br /><b>MYSQL Error:</b> ".mysql_error()."<br/><b>SQL Statement:</b> ".$sql, "update_error");
			} else {
				Log::getInstance()->write ("queried database successfully", "debug");
				$oMsg->result = "update_success";
				$oMsg->message = $rs. " records updated successfully";
			}
		}
	}
}