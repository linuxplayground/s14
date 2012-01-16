<?php
Log::getInstance()->write(__FILE__." loaded.", "debug");
class ClickHistory implements Filter {
	public function execute ( $oMsg ) {
		if (! isset ($oMsg->request['systemUser'])) {
			if (isset ( $_SESSION['backlink'] ) ) {
				$oMsg->data['backlink'] = $_SESSION['backlink'];
			}
			
			if (isset( $oMsg->request['action'] ) ) {
				$action = $oMsg->request['action'];
			} else {
				$action = "default";
			}
			$_SESSION['backlink'] = "index.php?action=".$action;
			if( isset( $oMsg->request['module'] ) ) {
				$_SESSION['backlink'] .= "&module=".$oMsg->request['module'];
			}
		}
		
		return true;
	}
	
	public function getError( ) {
		return "The ClickHistory filter failed.  Please contact your system administrator.";
	}
}
?>