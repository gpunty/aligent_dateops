<?php

class DateOperator {
	public static function convertDateToUnit($date) {
		//Calculate number of days up to but including the current year. The number of days
		//elapsed in the current year will be calculated via the Z date format
		$yearVal = $date->format("Y") - 1;
		$numDaysYear = $yearVal * 365;
		
		//Calculate number of years that are divisible by 4
		$numYearsDivisableByFour = floor($yearVal / 4);
		$numDaysYear += $numYearsDivisableByFour;
		
		//Calculate number of years that are divisible by 100. These are not leap years
		$numYearsDivisibleByHundred = floor($yearVal / 100);
		$numDaysYear -= $numYearsDivisbleByHundred;
		
		//Calculate number of years that are divisible by 400. These correspond to the years
		//that would have been excluded from the 100 calculation
		$numYearsDivisibleBy400 = floor($yearVal / 400);
		$numDaysYear += $numYearsDivisibleBy400;
		
		//Get the number of days that have elapsed in the current year
		$numDaysThisYear = $date->format("z");
		$numDaysYear += $numDaysThisYear;
		
		return $numDaysYear;
	}
	
	public static function calcNumDays($date1, $date2) {
		$numDaysDate1 = self::convertDateToUnit($date1);
		$numDaysDate2 = self::convertDateToUnit($date2);
		
		return ($numDaysDate1 - $numDaysDate2);
	} 
	
	public static function calcNumWeekdays($date1, $date2) {
		return 0;
	}

	public static function calcNumCompleteWeeks($date1, $date2) {
		//call calcNumDays
		//divide by 7, take floor value
		return 0;
	}
}

$dateVal1 = date_create("2022-03-01");
$dateVal2 = date_create("2022-02-03");
echo DateOperator::calcNumdays($dateVal1, $dateVal2), " days";
//echo DateOperator::convertDateToUnit($dateVal1);
?>

