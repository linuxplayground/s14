<?php
	Log::getInstance()->write(__FILE__." loaded.", "debug");
	require_once ("Date.php"); // from PEAR
	
	class DateHandler extends Date_Calc {
		
		private $dateAr;
		
		public function __construct( ) {
			$this->dateAr = getdate(time());
		}
		
		/**
		 * Check to see if we need Friday this week or next week
		 * depending on the time of day to the hour and
		 * the current date
		 *
		 * @return String YYYYMMDD
		 */
		public function getNextFridayMysql() {
			switch($this->dayOfWeek()) {
				case 5:
					if ($this->dateAr['hours'] > 17 ) {
						return $this->nextDayOfWeek(5);
					} else {
						return date('Ymd', time());
					}
					break;
				case 6:
				case 0:
					return $this->nextDayOfWeek(5);
					break;
				default:
					return date('Ymd', time());
			}
		}
		
		/**
		 * Find out if the current day is a friday and return next sunday
		 * if the time is past cut off time OR
		 * if the current day is a saturday or sunday return next sunday OR
		 * if the current day is not a friday, saturday or sunday return this sunday
		 * 
		 * @return String YYYYMMDD
		 */
		public function getNextSundayMysql() {
			switch ($this->dayOfWeek()) {
				case 5:
					if ($this->dateAr['hours'] > 17) {
						return $this->nextDayOfWeek(14);
					} else {
						return $this->nextDayOfWeek(0);
					}
					break;
				case 6:
					return $this->nextDayOfWeek(15);
					break;
				case 0:
					return $this->nextDayOfWeek(8);
					break;
				default:
					return $this->nextDayOfWeek(1);
			}
		}
		
		/**
		 * Calculate and return the date a given number of days from now.
		 *
		 * @param int $days number of days.
		 * @return string yyyymmddd formated date
		 */
		public function dateShift($days=0) {
			$date = time();
			return date(
				'Ymd', mktime(
					date("H",$date),
					date("i",$date),
					date("s",$date),
					date("m",$date),
					date("d",$date) + $days,
					date("Y",$date)
					)
				);
		}
	}
?>