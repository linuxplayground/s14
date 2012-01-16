<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/pdf.class.php");

class RankingViewPdf {
	public function __Construct ( ) {
		//
	}
	
	public function displayLeaderboardPdf ( Message $oMsg ) {
			 //Function provided by: http://keithdevens.com/software/phpxml
			include ("modules/common/XML_serialize.php");
			$data = XML_unserialize($oMsg->data['xml']);
Debug::getInstance()->showStack($data, "unserialised data");
			$pdf =& PDFMaker::factory();
			/*
			* ezTable requires a two dimensional array in the form of
			* [0] => array(key1=>value1, key2=>value2);
			* if there is only one row returned the XML_unserialize function does not provide this.
			*/
			$source = $data['page']['game'];
			$table = array(
				0=> array(
					'hometeam'=>$source['hometeam'],
					'awayteam'=>$source['awayteam'],
					'hscore'=>$source['hscore'],
					'matchtype'=>$source['matchtype'],
					'date'=>$source['date']
				)
			);
			$pdf->ezTable($table,
				array(
					'hometeam' => 'Home Team',
					'awayteam' => 'Away Team',
					'hscore' => 'H Score',
					'ascore' => 'A Score',
					'matchtype' => 'Match Type',
					'date'	=> 'Date'
				),
				$data['page']['message'],
				array(
					"xPos" => "left",
					"xOrientation" => "right"
				)
			);
			$pdf->ezStream();
			$oMsg->response = null;
	}
}
?>