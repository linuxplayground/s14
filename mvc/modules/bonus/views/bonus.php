<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class BonusView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function listBonus( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['bonus']['xml'], "modules/bonus/views/templates/listBonus.xsl");
	}
	
	public function editBonus( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['bonus']['xml'], "modules/bonus/views/templates/editBonus.xsl");
	}
	
	public function confirmDeleteBonus( Message $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['bonus']['xml'], "modules/bonus/views/templates/confirmDeleteBonus.xsl");
	}
}
?>