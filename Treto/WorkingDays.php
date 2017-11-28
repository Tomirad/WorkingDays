<?php
/*

	@Author: Tomirad Trela, tomplus@gmail.com
	@Version: AMJ 1.4

	-> startDate() - return default current day or date with set: -> setDate($date) 
	-> finalDate() - return finish date with holidays day
	-> setDate(NOW, workingDay = 5) - setting process date
	-> __construct(country = 'pl', religionSaints = true) -> set JSOn src with holidays and saints day, 
                        'pl' for Poland, for other people please init: 'prv' or create own list,
                        $religionSaints - dynamic saints for catholic religions
	-> countWorkDays() - return count WorkingDays
	-> countRealDays() -> return count true days between start and end date
	-> setFormat(@format date()) -> setting format date
	-> setFreeDays(date = '01.10') -> create holiday days ex. 1st oct
	-> getSaints(country key) -> get list of free days with json file, default

*/
namespace Treto;

class WorkingDays {
	private $date = '';
	private $startDate = '';
	private $finalDate = '';
	private $dateFormat = 'Y-m-d';
	private $dayFormat = 'd.m';
	private $objDate = '';
	private $countWorkDays = 0;
	private $countRealDays = 0;
	private $idCount = 0;
	private $workingDays = 5;
	private $freeDay = [];
	private $saintDays = [];
  private $dynamicSaints = true;
  private $jsonData = 'Data/WorkingDays-Saints.json'; //National days, religion saints or own free days ex. vacations

	public function __construct($country = 'pl', $religionSaints = true) {
		$this -> getSaints($country);
    $this -> dynamicSaints = (bool)$religionSaints;
		$this -> countingDays();
	}

	public function startDate() {
		return $this -> startDate -> format($this -> dateFormat);
	}

	public function finalDate() {
		return $this -> finalDate;
	}

	public function countWorkDays() {
		return $this -> countWorkDays;
	}

	public function countRealDays() {
		return $this -> countRealDays;
	}

	public function setFormat($format = 'Y-m-d') {
		$this -> dateFormat = $format;
	}

	public function setDate($date = null, $workingDays = 5) {
		$this -> workingDays = $workingDays;
		if($date === null) {
			$this -> date = date($this -> dateFormat);
		} else {
			$this -> date = $date;
		}
		$this -> countingDays();
	}

	public function __toString() {
		return $this -> finalDate;
	}

	public function setFreeDays($data) {
    if(is_array($data)) {
      $this -> saintDays = $this -> addFreeDays([$data]); //m-d  
    } else {
      $this -> saintDays += [$data];
    }
	}
  /*
    Add lists saints from file
  */
	public function getSaints($country) {
		$json = [];
		if(file_exists($this -> jsonData)) {
			$file = file_get_contents($this -> jsonData);
			$json = json_decode($file, true);
		}
		$this -> saintDays = $this -> addFreeDays($json[$country]);
	}

	private function countingDays() {
		$this -> idCount ++;
		$this -> debug[$this -> idCount] = '';
		$this -> startDate = new \DateTime($this -> date);
		$this -> objDate = clone $this -> startDate ;
		$this -> countWorkDays = $this -> countRealDays = 0;
		$this -> createSaints($this -> startDate -> format('Y'));
		for($day = 0; $day <= $this -> workingDays; $day ++) {
			$this -> countRealDays ++;
			$dayNumber = $this -> objDate -> format('N');
			if($dayNumber > 5 ) {
				$day --;
			} elseif( $this -> saintDay()) {
				$day --;
			} else {
				$this -> countWorkDays ++;
			}
			if($this -> countWorkDays <= $this -> workingDays) {
				$this -> objDate -> modify('+1 day');
			}
		}
		$this -> finalDate = $this -> objDate -> format($this -> dateFormat);
	}

	private function createSaints($year = false) {
		$this -> freeDay = $this -> saintDays;
    if($this -> dynamicSaints) {
      $this -> catholicSaints($year);
    }
	}

  private function addFreeDays($arr) {
    return array_merge($this -> saintDays, $arr);
  }

	private function saintDay() {
		if( in_array($this -> objDate -> format($this -> dayFormat), $this -> freeDay) ) {
			return true;
		}
		return false;
	}

	private function getDate($datetime) {
		return date($this -> dayFormat, $datetime);
	}
  
  private function catholicSaints($year) {
		$easter_1 = easter_date(empty($year) ? date('Y') : $year);
		$easter_2 = $easter_1 + 86400 * 1;
		$june_1 = $easter_1 + 86400 * 49;
		$june_2 = $easter_1 + 86400 * 60;
		$this -> freeDay[] = $this -> getDate($easter_1);
		$this -> freeDay[] = $this -> getDate($easter_2);
		$this -> freeDay[] = $this -> getDate($june_1);
		$this -> freeDay[] = $this -> getDate($june_2);
	}
}
