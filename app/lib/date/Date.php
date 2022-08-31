<?php

namespace App\lib\date;

use DateTime;

class Date {

	private static $months = [
		'janeiro',
		'fevereiro',
		'março',
		'abril',
		'maio',
		'junho',
		'julho',
		'agosto',
		'setembro',
		'outubro',
		'novembro',
		'dezembro'
	];

	private static $centuries = [
		'I',
		'II',
		'III',
		'IV',
		'V',
		'VI',
		'VII',
		'VIII',
		'IX',
		'X',
		'XI',
		'XII',
		'XIII',
		'XIV',
		'XV',
		'XVI',
		'XVII',
		'XVIII',
		'XIX',
		'XX',
		'XXI',
		'XXII',
		'XXIII'
	];

	public static function formatedDate($date){

		$dateFunction = new Date();
		$d = $dateFunction->formatDate($date);
		return $d;
	}
	public function formatDate($date) {
		if ( $this->isFormatted($date) ) {
			return $date;
		}
		$formattedDate = DateTime::createFromFormat('d/m/Y', $date);
		if ($formattedDate &&
			DateTime::getLastErrors()["warning_count"] == 0 &&
			DateTime::getLastErrors()["error_count"] == 0)
				return $formattedDate->format('Y-m-d');

		return null;
	}

	public function formatDatePortugues($dateTime) {
		$formattedDate = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);

		if ($formattedDate &&
			DateTime::getLastErrors()["warning_count"] == 0 &&
			DateTime::getLastErrors()["error_count"] == 0)
				return $formattedDate->format('d/m/Y');

		return null;
	}

	public function translate($date) {

		if ($date == null) return null;

		//verifica se é um intervalo
		if ( $this->isInterval($date) ) {
			return ucfirst($this->translateInterval($date));
		}
		if ( $this->isCentury($date) ) {
			return ucfirst($this->translateCentury($date));
		}
		if ( $this->isDecade($date) ) {
			return ucfirst($this->translateDecade($date));
		}
		return ucfirst($this->translateDate($date));
	}

	public function translateInterval($date) {
		$dates = explode('/', $date);

		if ( $this->isCentury($dates[0]) && $this->isCentury($dates[1]) )
			return 'entre o ' . $this->translateCentury($dates[0]) . ' e o ' . $this->translateCentury($dates[1]);

		if ( $this->isDecade($dates[0]) && $this->isDecade($dates[1]) )
			return 'entre a ' . $this->translateDecade($dates[0]) . ' e a ' . $this->translateDecade($dates[1]);

		return 'entre ' . $this->translateDate($dates[0]) . ' e ' . $this->translateDate($dates[1]);
	}

	public function translateDate($date) {
		if (preg_match('#(\d{4,})-(\d{2,})-(\d{2,})#', $date, $match)) {
			$year = $match[1];
			$month = self::$months[( intval($match[2]) ) - 1];
			$day = $match[3];
			return $day . ' de ' . $month . ' de ' . $year;
		}

		if (preg_match('#(\d{4,})-(\d{2,})#', $date, $match)) {
			$year = $match[1];
			$month = self::$months[( intval($match[2]) ) - 1];
			return $month . ' de ' . $year;
		}

		if (preg_match('#\d{4,}#', $date)) {
			return $date; //date = year, neste caso
		}

		return null;
	}

	public function translateCentury($century) {
		return 'século ' . self::$centuries[intval($century)];
	}

	public function translateDecade($decade) {
		return 'década de ' . (intval($decade) * 10);
	}

	public function isCentury($date) {
		return strlen($date) == 2 && preg_match('#\d{2,}#', $date);
	}

	public function isDecade($date) {
		return strlen($date) == 3 && preg_match('#\d{3,}#', $date);
	}

	public function isYear($date) {
		return strlen($date) == 4 && preg_match('#(\d{4,})#', $date);
	}

	public function isFullDate($date) {
		return strlen($date) == 10 && preg_match('#(\d{4,})-(\d{2,})-(\d{2,})#', $date);
	}

	public function isPartialDate($date) {
		if ( strlen($date) == 7 && preg_match('#(\d{4,})-(\d{2,})#', $date) ) {
			return true;
		}
		return $this->isYear($date);
	}

	public function isDate($date) {
		return $this->isFullDate($date) ?: $this->isPartialDate($date);
	}

	public function isInterval($date) {
		return strpos($date, '/') !== false;
	}

	public function isFormatted($date) {
		if ( $this->isInterval($date) && count($dates = explode('/', $date)) == 2 ) {
			return $this->isFormatted($dates[0]) && $this->isFormatted($dates[1]);
		}
		return ( $this->isCentury($date) || $this->isDecade($date)
			|| $this->isDate($date) );
	}

	public function dateDiff($start, $end = false){
		$stringDate = array();

   		try {
      			$start = new DateTime($start);
      			$end = new DateTime($end);
      			$form = $start->diff($end);
   			} catch (\Exception $e){
      			return $e->getMessage();
   			}

   			$display = array('y'=>'ano',
               'm'=>'mês',
               'd'=>'dia',
               'h'=>'hora',
               'i'=>'minuto');
               //'s'=>'seg');

   			foreach($display as $key => $value){
      			if($form->$key > 0){
         			$stringDate[] = $form->$key.' '.($form->$key > 1 ? $value.'s' : $value);
      			}
   			}

   			return implode($stringDate, ', ');
	}

	public function formatToWorkDate($date,$type){

		if($date != null && $type != null){
			if ($type == "year"){
				return "Ano de ".$date;
			}
			if ($type == "decade"){
				if($date =="BD"){
					return "Anterior ao ano de 1401";
				}else{
					$date = explode(" ", $date);
					return "Entre ".$date[0]." e ".$date[2];
				}
			}
			if ($type == "century"){
				return "Século ".$date;
			}
		}elseif($date != null && $type == null){
			return $this->translate($date);
		}else{
			return null;
		}

	}

	public function formatToDataCriacao($date,$type){

		if($date != null && $type != null){
			if ($type == "date"){
				return $this->translateDate($date);
			}
			if ($type == "decade"){
				if($date =="BD"){
					return "Anterior ao ano de 1401";
				}else{
					$date = explode(" ", $date);
					return "Entre ".$date[0]." e ".$date[2];
				}
			}
			if ($type == "century"){
				return "Século ".$date;
			}
		}elseif($date != null && $type == null){
			return $this->translate($date);
		}else{
			return null;
		}

	}


}
