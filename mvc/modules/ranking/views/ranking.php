<?php
Log::getInstance()->write (__FILE__. " loaded", "debug" );

require_once ("modules/common/template_xslt.class.php");

class RankingView extends TemplateXslt {
	var $sortRound;
	
	public function __Construct ( ) {
		//
	}
	
	public function displayLeaderboard( Message $oMsg ) {

		$oMsg->response = $this->execute($oMsg->data['xml'], "modules/ranking/views/templates/displayLeaderboard.xsl");
	}

	public function displayLeaderboardPdf ( Message $oMsg ) {
			 //Function provided by: http://keithdevens.com/software/phpxml
			include ("modules/common/XML_serialize.php");
			
			require_once ("modules/common/pdf.class.php"); //Get the pdf maker class.
			
			$data = XML_unserialize($oMsg->data['xml']);

			$pdf =& PDFMaker::factory($paper="a4",$orientation="landscape");
			/*
			* ezTable requires a two dimensional array in the form of
			* [0] => array(key1=>value1, key2=>value2);
			* if there is only one row returned the XML_unserialize function does not provide this.
			*/
			$source = $data['rank']['user'];

			$table = array();
			$c = 0; $takeTotal = 0;
			foreach ($source as $user => $details) {
				$rScore = 0;
				$table[$c]['Name:'] = $details['name'];
				foreach ($details['round'] as $round => $details) {
					$table[$c][$details['num']] = $details['score'];
					$rScore += $details['score'];
				}
				$table[$c]['Total'] = $rScore;
				$c++;
			}
			$table[$c]['Name:'] = "Take:";
			for( $lp=0; $lp < $data['rank']['numberofrounds']; $lp++ ) {
				$table[$c][$lp+1] = "$".$data['rank']['bonusandtakes']['round'][$lp]['take'].".00";
				$takeTotal += $data['rank']['bonusandtakes']['round'][$lp]['take'];
			}
			$table[$c]['Total'] = "$".$takeTotal.".00";
			
			$pdf->ezTable($table,
				"",
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