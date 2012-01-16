<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ('modules/common/model.class.php');
require_once( "modules/common/xmlwriter.class.php" );

	class MarginAction extends Model {
	
		public function listMargin( $oMsg ) {
			
			
			
			if( $this->validateUser( $oMsg ) ) {
				if( isset ($oMsg->request['filter'])) {
					settype($oMsg->request['filter'], "integer");
					$filter = $oMsg->request['filter'];
					$sql =sprintf( "SELECT m.*, mt.mt_name FROM margin m, matchtype_margin mtm, matchtype mt where m.m_id = mtm.m_id and mtm.mt_id = mt.mt_id and mtm.mt_id = %d ORDER BY mtm.mt_id ASC, m.m_l_value DESC", $filter);
				} else {
					$sql = "SELECT m.*, mt.mt_name FROM margin m, matchtype_margin mtm, matchtype mt where m.m_id = mtm.m_id and mtm.mt_id = mt.mt_id ORDER BY mtm.mt_id ASC, m.m_l_value DESC";
				}
				$rs = $oMsg->conn->query( $sql );
				if ( PEAR::isError( $rs ) ) {
					Log::getInstance()->write( "Error selecting data from margin table. ".$rs->getMessage(), "error");
					$this->insert_error( $oMsg, "Error selecting data from the margin table. ".$rs->getMessage(), "error");
				} else {
					
					$xmlObj = new XMLWriterObj( );
					$xmlObj->push('page');
					if (! isset( $oMsg->message ) ) {
						$xmlObj->element('message', 'Margins listed in points difference range order');
					} else {
						$xmlObj->element('message', $oMsg->message);
					}
					while ($row = $rs->fetchRow( MDB2_FETCHMODE_ASSOC ) ) {
						$xmlObj->push('margin');
							$xmlObj->element('id', $row['m_id']);
							$xmlObj->element('name', $row['m_name']);
							$xmlObj->element('lower_value', $row['m_l_value']);
							$xmlObj->element('upper_value', $row['m_u_value']);
							$xmlObj->element('matchtype',$row['mt_name']);
						$xmlObj->pop();
					}
					
					$xmlObj->pop();
					
					$oMsg->data['margin']['xml'] = $xmlObj->getXml();
					$oMsg->result = 'listMargin';
				}
			}
		}
		
		public function newMargin( $oMsg ) {
			$xml = "
<form>
	<action>index.php?action=insertMargin&amp;module=margin</action>
	<method>post</method>
	<label>New Margin</label>
	<input>
		<label>Name</label>
		<type>text</type>
		<name>insertMarginName</name>
	</input>
	<input>
		<label>Lower Value</label>
		<type>text</type>
		<name>insertMarginLowerValue</name>
	</input>
	<input>
		<label>Upper Value</label>
		<type>text</type>
		<name>insertMarginUpperValue</name>
	</input>
	<select>
		<name>matchType</name>
		<label>Match Type</label>";
			$sql = "SELECT * FROM matchtype ORDER BY mt_name ASC";
			$rs = $oMsg->conn->query($sql);
			if (PEAR::isError($rs)) {
					Log::getInstance()->write("Could not select matchtypes for populating dropdown list. ".mysql_error(),"error");
					return $this->insert_error($oMsg, "Could not find match types for dropdown list. ".$rs->getMessage(), "error");
			}
			while ($row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$xml .= "
			<option>
				<value>".$row['mt_id']."</value>
				<label>".$row['mt_name']."</label>
			</option>";
			}
			$xml .= "
	</select>
	<submit>
		<name>insertMarginSubmit</name>
		<value>Add...</value>
	</submit>
</form>
";
			
			$oMsg->result="insertMargin";
			$oMsg->data['margin']['xml'] = $xml;
		}
		
		public function insertMargin( $oMsg ) {
			if( $this->validateUser( $oMsg ) ) {
				
				
				if ( ! settype( $oMsg->request['insertMarginLowerValue'], "integer") ) {
					Log::getInstance()->write("insertMarginLowerValue is not an integer", "error");
					return $this->insert_error( $oMsg, "insertMarginLowerValue is not an integer", "error" );
				}
				if ( ! settype( $oMsg->request['insertMarginUpperValue'], "integer") ) {
					Log::getInstance()->write( "insertMarginUpperValue is not an integer", "error");
					return $this->insert_error( $oMsg, "insertMarginUpperValue is not an integer", "error" );
				}
				
				$sql = sprintf("INSERT INTO margin ( m_name, m_l_value, m_u_value) values ('%s', %d, %d )",
					$oMsg->request['insertMarginName'],
					$oMsg->request['insertMarginLowerValue'],
					$oMsg->request['insertMarginUpperValue'] );
				
				$rs = $oMsg->conn->exec( $sql );
				if ( PEAR::isError ( $rs ) ) {
					Log::getInstance()->write("Error adding new margin to database. ".mysql_error(), "error" );
					return $this->insert_error( $oMsg, "Error adding new margin to database. ".$rs->getMessage(), "error" );
				}
				$marginInsertId = $oMsg->conn->lastInsertId();
				
				$sql = sprintf("INSERT INTO matchtype_margin VALUES(null, %d, %d)", $oMsg->request['matchType'], $marginInsertId);
				$rs = $oMsg->conn->exec($sql);
				if(PEAR::isError($rs)) {
					Log::getInstance()->write("Error adding new matchtype_margin to database. ".mysql_error(), "error" );
					return $this->insert_error( $oMsg, "Error adding new match type - margin to database. ".$rs->getMessage(), "error" );
				}
				$oMsg->message = "Added one margin successfully.";
				$oMsg->result = "insertMarginSuccess";
			}
		}
		
		public function editMargin( $oMsg ) {
			if ( $this->validateUser ($oMsg) ) {
				settype ($oMsg->request['id'], "integer");
				$sql = sprintf( "SELECT m.*, mt.* FROM margin m, matchtype_margin mtm, matchtype mt where m.m_id = mtm.m_id and mtm.mt_id = mt.mt_id and m.m_id = %d",
					$oMsg->request['id'] );
				$rs = $oMsg->conn->query( $sql );
				if (PEAR::isError( $rs ) ) {
					Log::getInstance()->write( "Error fetching details of margin for editMargin. ".mysql_error(), "error" );
					return $this->insert_error( $oMsg, "Error fetching details of margin for editMargin. ".$rs->getMessage(), "error" );
				}
				$row = $rs->fetchRow( MDB2_FETCHMODE_ASSOC );
				$xml = "<form>
<label>Edit Margin</label>
<action>index.php?action=updateMargin&amp;module=margin&amp;id=".$row['m_id']."</action>
<method>post</method>
	<input>
		<type>text</type>
		<name>updateMarginName</name>
		<label>Name</label>
		<value>". $row['m_name']."</value>
	</input>
	<input>
		<type>text</type>
		<name>updateMarginLowerValue</name>
		<label>Lower Value</label>
		<value>".$row['m_l_value']."</value>
	</input>
	<input>
		<type>text</type>
		<name>updateMarginUpperValue</name>
		<label>Upper Value</label>
		<value>".$row['m_u_value']."</value>
	</input>
	<select>
		<name>matchType</name>
		<label>Match Type</label>
		<defaultValue>".$row['mt_id']."</defaultValue>";
			$sql2 = "SELECT * FROM matchtype ORDER BY mt_name ASC";
			$rs2 = $oMsg->conn->query($sql2);
			if (PEAR::isError($rs2)) {
					Log::getInstance()->write("Could not select matchtypes for populating dropdown list. ".mysql_error(),"error");
					return $this->insert_error($oMsg, "Could not find match types for dropdown list. ".$rs2->getMessage(), "error");
			}
			while ($row2 = $rs2->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$xml .= "
			<option>
				<value>".$row2['mt_id']."</value>
				<label>".$row2['mt_name']."</label>
			</option>";
			}
			$xml .= "
	</select>
	<submit>
		<name>updateMarginSubmit</name>
		<value>Update...</value>
	</submit>
</form>";
				$oMsg->data['margin']['xml'] = $xml;
				$oMsg->result = "editMarginForm";
			}
		}
		public function updateMargin( $oMsg ) {
			if ($this->validateUser( $oMsg ) ) {
				
				settype($oMsg->request['id'], "integer");
				settype($oMsg->request['updateMarginLowerValue'], "integer");
				settype($oMsg->request['updateMarginUpperValue'], "integer");
				
				$sql = sprintf( "UPDATE margin SET m_name = '%s', m_l_value = %d, m_u_value = %d where m_id = %d",
					$oMsg->request['updateMarginName'],
					$oMsg->request['updateMarginLowerValue'],
					$oMsg->request['updateMarginUpperValue'],
					$oMsg->request['id'] );
				$rs = $oMsg->conn->exec( $sql );
				if( PEAR::isError( $rs ) ) {
					Log::getInstance()->write( "Error updating database for margin. ".mysql_error(), "error" );
					return $this->insert_error( $oMsg, "Error updating database for margin. ".$rs->getMessage(), "error" );
				} else {
					$oMsg->message = "Updated one margin successfully";
					$oMsg->result = "updateMarginSuccess";
				}
			}
		}
		public function confirmDeleteMargin( $oMsg ) {
			if ($this->validateUser( $oMsg ) ) {
				
				settype($oMsg->request['id'], "integer");
				$sql = sprintf("SELECT m_name FROM margin where m_id = %d", $oMsg->request['id']);
				$rs = $oMsg->conn->query( $sql );
				if (PEAR::isError( $rs ) ) {
					Log::getInstance()->write("Error finding margin to delete. ".mysql_error(), "error");
					return $this->insert_error( $oMsg, "Error finding margin to delete. ".$rs->getMessage(), "error" );
				} else {
					$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
					$xml = "<form>
	<label>Are you sure you wish to delete ".$row['m_name']." ?</label>
	<action>index.php?action=deleteMargin&amp;module=margin</action>
	<method>post</method>
	<submit>
		<name>confirmDeleteMargin</name>
		<value>YES</value>
	</submit>
	<submit>
		<name>confirmDeleteMargin</name>
		<value>NO</value>
	</submit>
	<input>
		<type>hidden</type>
		<name>id</name>
		<value>".$oMsg->request['id']."</value>
	</input>
</form>";
					$oMsg->data['margin']['xml'] = $xml;
					$oMsg->result="confirmDeleteMarginForm";
				}
			}
		}
		public function deleteMargin( $oMsg ) {
			if ($this->validateUser( $oMsg ) ) {
				
				settype( $oMsg->request['id'], "integer" );
				if ( $oMsg->request['confirmDeleteMargin'] == 'YES' ) {
					$sql = sprintf( "DELETE FROM margin WHERE m_id = %d", $oMsg->request['id'] );
					$rs = $oMsg->conn->exec( $sql );
					if (PEAR::isError( $rs ) ) {
						Log::getInstance()->write("Error deleting a margin. ".mysql_error(), "error");
						return $this->insert_error( $oMsg, "Error deleting margin. ".$rs->getMessage(), "error" );
					} else {
						$sql = sprintf("DELETE FROM matchtype_margin WHERE m_id = %d", $oMsg->request['id']);
						$rs = $oMsg->conn->exec($sql);
						if(PEAR::isError($rs)) {
								Log::getInstance()->write("Could not delete match type margins for margin that was deleted. ".mysql_error(), "error");
								return $this->insert_error($oMsg, "Could not delete match type margins for ", "error");
						}
						$oMsg->message = "Deleted one margin and the accosiated matchtype_margin entry successfully.";
						$oMsg->result = "deleteMarginSuccess";
					}
				} else {
					$oMsg->message = "You decided not to proceed with the delete";
					$oMsg->result = "didNotConfirmDelete";
				}
			}
		}
	}
?>