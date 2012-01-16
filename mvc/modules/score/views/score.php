<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class ScoreView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function listScore  ( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['score']['xml'], "modules/score/views/templates/listScore.xsl");
	}
	
	public function editScore  ( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['score']['xml'], "modules/score/views/templates/editScore.xsl");
	}
}
?>