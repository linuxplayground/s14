<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class MatchTypeView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function listMatchType( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['matchtype']['xml'], "modules/matchType/views/templates/listMatchType.xsl");
	}
	
	public function newMatchTypeForm( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['matchtype']['xml'], "modules/matchType/views/templates/editMatchType.xsl");
	}
	
	public function confirmDeleteMatchTypeForm( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['matchtype']['xml'], "modules/matchType/views/templates/editMatchType.xsl");
	}
	
	public function editMatchTypeForm( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['matchtype']['xml'], "modules/matchType/views/templates/editMatchType.xsl");
	}
}
?>