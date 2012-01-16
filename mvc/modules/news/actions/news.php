<?php
Log::getInstance()->write (__FILE__." loaded", "debug" );

require_once ('modules/common/model.class.php');

class NewsAction extends Model {

	public function listNews( $oMsg ) {
		
		
		if(!isset($oMsg->message) ) {
			$oMsg->message = "";
		}
		$sql = "select * from news order by news_date DESC";
		$rs = $oMsg->conn->query( $sql);
		if ( PEAR::isError( $rs ) ) {
			Log::getInstance()->write( "Could not query news items from the database." ,"error" );
			return $this->insert_error( $oMsg, "Could not query news items from the database.<br />".$rs->getMessage(), "error" );
		} else {
			if ( $rs->numRows() > 0 ) {
				$oMsg->data['news']['xml'] = "
						<page>
						<message>".$oMsg->message."</message>";
					while ($row = $rs->fetchRow( MDB2_FETCHMODE_ASSOC ) ) {
						$oMsg->data['news']['xml'] .= "<news>
							<title>".$row['news_title']."</title>
							<body>".nl2br($row['news_content'])."</body>
							<date>".substr($row['news_date'],0,-9)."</date>
							<id>".$row['news_id']."</id>
							</news>";
					}
				$oMsg->data['news']['xml'] .= "</page>";
			} else {
				$oMsg->data['news']['xml'] = "<page><message>There are no news items</message></page>";
			}
			$oMsg->result = "display_news";
		}
	}
	
	public function newNews( $oMsg ) {
		
		if ($this->validateUser( $oMsg ) ) {
			$oMsg->data['news']['xml'] = "<form>
				<label>New News Item</label>
				<action>index.php?action=insertNews&amp;module=news</action>
				<method>POST</method>
				<input>
					<type>text</type>
					<name>insertNewsTitle</name>
					<label>Title</label>
				</input>
				<textArea>
					<name>insertNewsBody</name>
					<label>Content</label>
				</textArea>
				<submit>
					<name>insertNewsSubmit</name>
					<value>Add...</value>
				</submit>
			</form>";
			$oMsg->result = "display_new_news_form";
		}
	}
	
	public function insertNews( $oMsg ) {
		
		if ($this->validateUser( $oMsg ) ) {
			$oMsg->conn->quote($oMsg->request['inertNewsTitle']);
			$oMsg->conn->quote($oMsg->request['inertNewsBody']);
			$sql = sprintf("INSERT INTO news (news_title, news_content) values ('%s', '%s')",
				$oMsg->request['insertNewsTitle'],
				htmlspecialchars( $oMsg->request['insertNewsBody'] ) );
			$rs = $oMsg->conn->exec ($sql);
			if (PEAR::isError($rs) ) {
				Log::getInstance()->write("Could not insert data to news table.".$rs->getMessage(), "error");
				return $this->insert_error("Could not insert data into news table. ".$rs->getMessage(), "error");
			} else {
				$oMsg->message = "1 news item added successfully.";
				$oMsg->result = "listNews";
			}
		}
	}
	
	public function editNews( $oMsg ) {
		
		if ( $this->validateUser( $oMsg ) ) {
			settype($oMsg->request['id'], "integer");
			$sql = sprintf( "SELECT * FROM news WHERE news_id = %d", $oMsg->request['id'] );
			$rs = $oMsg->conn->query($sql);
			if (PEAR::isError($rs) ) {
				Log::getInstance()->write("Could not query database for news item for editing.".$rs->getMessage(), "error");
				return $this->insert_error($oMsg, "Could not query database for news item for editing.".$rs->getMessage(), "error" );
			} else {
				$row = $rs->fetchRow(MDB2_FETCHMODE_ASSOC);
				
				$oMsg->data['news']['xml'] = "<form>
					<label>Edit News</label>
					<action>index.php?action=updateNews&amp;module=news</action>
					<method>POST</method>
					<input>
						<type>text</type>
						<label>Title</label>
						<name>updateNewsTitle</name>
						<value>".$row['news_title']."</value>
					</input>
					<input>
						<type>hidden</type>
						<name>updateNewsId</name>
						<value>".$row['news_id']."</value>
					</input>
					<textArea>
						<name>updateNewsBody</name>
						<label>Content</label>
						<content><![CDATA[".htmlspecialchars_decode($row['news_content'])."]]></content>
					</textArea>
					<submit>
						<name>updateNewsSubmit</name>
						<value>Update...</value>
					</submit>
				</form>";
				$oMsg->result='display_edit_news_form';
			}
		}
	}
	public function updateNews( $oMsg ) {
		
		if ($this->validateUser( $oMsg ) ) {
			settype($oMsg->request['updateNewsId'], "integer");
			$oMsg->conn->quote($oMsg->request['updateNewsTitle']);
			$oMsg->conn->quote($oMsg->request['updateNewsBody']);
			
			$sql = sprintf("UPDATE news SET news_title='%s', news_content='%s' WHERE news_id=%d",
				$oMsg->request['updateNewsTitle'],
				htmlspecialchars( $oMsg->request['updateNewsBody'] ),
				$oMsg->request['updateNewsId']);
				
			$rs = $oMsg->conn->exec ($sql);
			if (PEAR::isError($rs) ) {
				Log::getInstance()->write("Could not update data to news table.".$rs->getMessage(), "error");
				return $this->insert_error("Could not update data into news table. ".$rs->getMessage(), "error");
			} else {
				$oMsg->message = "1 news item updated successfully.";
				$oMsg->result = "listNews";
			}
		}
	}
	public function confirmDeleteNews( $oMsg ) {
		
		if( $this->validateUser( $oMsg ) ) {
			settype( $oMsg->request['id'], "integer" );
			$sql = sprintf( "Select news_title from news where news_id = %d",
				$oMsg->request['id'] );
			$rs = $oMsg->conn->query( $sql );
			if( PEAR::isError( $rs ) ) {
				Log::getInstance()->write( "Could not find data for confirm delete dialogue. ".$rs->getMessage( ), "error" );
				return $this->insert_error( $oMsg, "Could not find data for confirm delete dialogue. ".$rs->getMessage( ), "error" );
			} else {
				$row = $rs->fetchRow( MDB2_FETCHMODE_ASSOC );
				$oMsg->data['news']['xml'] = "<form>
					<action>index.php?action=deleteNews&amp;module=news</action>
					<method>POST</method>
					<label>Are you sure you want to delete ".$row['news_title']."?</label>
					<submit>
						<type>submit</type>
						<name>confirmDeleteResponse</name>
						<value>YES</value>
					</submit>
					<submit>
						<type>submit</type>
						<name>confirmDeleteResponse</name>
						<value>NO</value>
					</submit>
					<input>
						<type>hidden</type>
						<name>confirmDeleteNewsId</name>
						<value>".$oMsg->request['id']."</value>
					</input>
				</form>";
				$oMsg->result = "show_delete_confirm_form";
			}
		}
	}
	public function deleteNews( $oMsg ) {
		
		if( $this->validateUser( $oMsg ) ) {
			settype( $oMsg->request['id'], "integer" );
			switch ($oMsg->request['confirmDeleteResponse'] ) {
				case "YES":
					$sql = sprintf("DELETE FROM news WHERE news_id = %d",
						$oMsg->request['confirmDeleteNewsId'] );
					$rs = $oMsg->conn->exec( $sql );
					if (PEAR::isError( $rs ) ) {
						Log::getInstance()->write("Could not delete data from news table.".$rs->getMessage(), "error");
						return $this->insert_error($oMsg, "Could not delete data from news table. ".$rs->getMessage(), "error");
					} else {
						$oMsg->message = "Deleted 1 news item successfully.";
						$oMsg->result = "deleteSuccess";
					}
					break;
				default:
					$oMsg->message = "You decided not to proceed with the delete.";
					$oMsg->result = "deleteSuccess";
					break;
			}
		}
	}
}
?>