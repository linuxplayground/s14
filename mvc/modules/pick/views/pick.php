<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class PickView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function listPick( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['text'], "modules/pick/views/templates/selectPicks.xsl");
	}
	
	public function listPrevPick( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['text'], "modules/pick/views/templates/listPrevPicks.xsl");
	}
	public function testHttp( $oMsg ) {
		$oMsg->response = $oMsg->data['text'];
	}
	
	public function returnXmlData( $oMsg ) {
		$oMsg->response = $oMsg->data['xml'];
	}
	
	public function confirmDeletePicks( $oMsg ) {
		$oMsg->response = $this->execute( $oMsg->data['xml'], "modules/pick/views/templates/confirmDeletePicks.xsl");
	}
	
}
?>