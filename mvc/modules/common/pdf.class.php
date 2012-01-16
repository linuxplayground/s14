<?php
Log::getInstance()->write(__FILE__." loaded.");
require_once ('pdf/class.ezpdf.php');

class PDFMaker {
		private $pdf;
		/**
		 * Generate and return a new instance of the ezpdf object.
		 * @access public static
		 * @param $size Default paper size = A4
		 * @param $orientation Default orientation is "P" = Portrate
		 * @return New Cezpdf instance
		 */
		public static function factory($size = "A4", $orientation="P") {
				
			$pdf = new Cezpdf($size, $orientation);
			$pdf->ezStartPageNumbers(550,10,10);
			$pdf->selectFont("modules/common/pdf/fonts/Helvetica.afm");
			$pdf->ezImage("modules/common/images/xampp-logo-small.jpg",null,null,"none","left");
			$pdf->ezText("Super 14 - Sweepstakes", 16);
			$pdf->ezText("Printed: ".date('d:m:Y'),10);
			$pdf->setLineStyle(5);
			$pdf->line(10,720,580,720);
			$pdf->ezSetDy(-30);
			return $pdf;
		}
}
?>