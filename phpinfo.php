<?php

class DateOperator {
	private static function convertDateToNumDays($date) {
		//Calculate number of days up to but including the current year. The number of days
		//elapsed in the current year will be calculated via the 'z' date format
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
		
		$numSecondsDays = self::convertToUnit($numDaysYear, "s");
		
		$hourComponent = $date->format("H");
		$minuteComponent = $date->format("i");
		$secondComponent = $date->format("s");
		
		$timeComponent = ($hourComponent * 60 * 60) + ($minuteComponent * 60) + $secondComponent;
		
		return $numDaysYear + ($timeComponent / 60 / 60 / 24);
	}
	
	private static function convertToUnit($numDaysValue, $unit) {
		switch($unit) {
			case "y":
				return $numDaysValue / 365;
			case "h":
				return $numDaysValue * 24;
			case "m":
				return $numDaysValue * 24 * 60;
			case "s":
				return $numDaysValue * 24 * 60 * 60;
			default:
				return $numDaysValue;
		}
	}
	
	public static function calcNumDays($date1, $date2, $unit) {
		$numDaysDate1 = self::convertDateToNumDays($date1);
		$numDaysDate2 = self::convertDateToNumDays($date2);
		
		return self::convertToUnit(abs($numDaysDate1 - $numDaysDate2), $unit);
	} 
	
	public static function calcNumWeekdays($date1, $date2, $unit) {
	    //Determine the larger of the date values to ensure correct processing
		$minDate = $date1 >= $date2 ? clone $date2 : clone $date1;
		$maxDate = $date1 >= $date2 ? clone $date1 : clone $date2;
		 
		//Get the day number for the smaller date. This will be used to determine
		//how many weekdays there are remaining for the first week
		$dayOfMinDate = $minDate->format("w");
		$remainingDaysFirstWeekDate1 = 0;
		$tempDate = clone $minDate;
		
		//Day number value of 0 corresponds to a Sunday, which is the start of the week.
		//If the Day number isn't 0, we can determine that for the first week that there
		//are 6 - day number days until the next Sunday (6 because we ignore the Sunday;
		//it signifies the start of a new week)
		if($dayOfMinDate != 0) {
		    //6 - day number to get the date of the first Sunday. This also gives us
		    //the remaining number of weekdays for the first week
			$remainingDaysFirstWeekDate1 = 6 - $dayOfMinDate;
			$tempDate->modify("+". ($remainingDaysFirstWeekDate1 + 1). " days");
		}
		
		//Total number of week days is calculated as:
		//Remaining number of days of first week + (number of complete weeks * 5 days) + day number of date2
		//NOTE: if the day number date2 is on a Saturday, this means that 5 weekdays have passed for date2 anyway
		
		$numWeekdays = $remainingDaysFirstWeekDate1;
		
		$numCompleteWeeks = self::calcNumCompleteWeeks($tempDate, $maxDate, null);
		$numWeekdays += ($numCompleteWeeks * 5);
		
		$elapsedDaysDate2 = $maxDate->format("w");
		
		if($elapsedDaysDate2 == 6) {
			$elapsedDaysDate2--;
		}   
		
		$numWeekdays += $elapsedDaysDate2;
		
		return self::convertToUnit($numWeekdays, $unit);
	}

	public static function calcNumCompleteWeeks($date1, $date2, $unit) {
		$numDays = self::calcNumDays($date1, $date2, null);
		
		return self::convertToUnit(floor($numDays / 7), $unit);
	}
	
	public static function setDateTimeZone(&$date, $timeZone) {
		if(!is_null($timeZone)) {
			if(in_array($timeZone, timezone_identifiers_list())) {
				date_timezone_set($date, timezone_open($timeZone));
			}
		}
	}
}
?>

