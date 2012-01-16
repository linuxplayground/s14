<?php
	/*
	Base class for views that require output in xslt.
	*/
	
	Log::getInstance()->write(__FILE__." loaded", "debug");
	
	class TemplateXslt {
		public function __construct ( ) {
			//
		}
		
		/*
		* Transforms an xml string using an xml stylesheet
		*
		* @param xmldata string containing xml to transform
		* @param xslfile file path of xsl stylesheet to use
		* @return xhtml string
		*/
		
		public function execute ( $xmldata, $xslfile ) {
			
			$xsl = new DOMDocument;
			$xsl->load( $xslfile );
			
			$proc = new XSLTProcessor;
			$proc->importStyleSheet ($xsl);
			
			return $proc->transformToXML( DOMDocument::loadXML( $xmldata ) );
		}
	}
?>