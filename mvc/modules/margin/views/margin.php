<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class MarginView extends TemplateXslt {
	public function __Construct ( ) {
		//
	}
	
	public function listMargin ( $oMsg ) {
		$oMsg->response = $this->execute($oMsg->data['margin']['xml'], "modules/margin/views/templates/listMargin.xsl");
		//$oMsg->response = $oMsg->data['margin']['xml'];
	}
	public function editMargin ( $oMsg ) {
		$oMsg->response = $this->execute( $oMsg->data['margin']['xml'], "modules/margin/views/templates/editMargin.xsl");
	}
	public function confirmDeleteMargin( $oMsg ){
		$oMsg->response = $this->execute( $oMsg->data['margin']['xml'], "modules/margin/views/templates/editMargin.xsl");
	}
}
?>