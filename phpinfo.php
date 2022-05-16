<?php
/**
 * Class that provides Date operations as specified in the Technical Exercise
 */
class DateOperator {
    /**
     * Private function to calculate the number of days elapsed for a date
     * 
     * @param DateTime $date Date to calculate
     * @return int Number of days elapsed
     */
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
        
        return $numDaysYear ;
    }
    
     /**
      * Calculates the number of days that have elapsed for a given date.
      *
      * @param int $value Value to convert
      * @param String $origUnit The time unit used for $value
      * @param String $unit Time unit to convert value to. Valid values for $unit are:
      *   - "y": years
      *   - "h": hours
      *   - "m": minutes
      *   - "s": seconds
      *
      * If no unit or any value other than the listed values is provided, the calculation result will be
      * in the unit of days. This calculation also incorporates the time component if specified as part of the
      * date
      * @return int The amount of time elapsed relative to the given $unit, or $value
      */
    private static function convertToUnit($value, $origUnit, $unit) {
        $convertedValue = $value;
        
        //In the code, the original time unit will be one of 2 values: days (d) and weeks (w). To
        //meet the requirements, I will only worry about these two time units as the core API functions
        //will only produce a time value either in days or weeks. In the case of weeks, convert the value
        //to days so that the conversion is consistent.
        if($origUnit == "w" && $unit != null) {
            $convertedValue *= 7;
        }
        
        switch($unit) {
            case "y": //years
                return $convertedValue / 365;
            case "h": //hours
                return $convertedValue * 24;
            case "m"://minutes
                return $convertedValue * 24 * 60;
            case "s"://seconds
                return $convertedValue * 24 * 60 * 60;
            default:
                return $convertedValue;
        }
    }
    
    /**
     * Private function to convert the time component of a date to seconds
     *
     * @param DateTime $date Date to calculate
     * @return int Total number of seconds elapsed for given time
     */
    private static function calculateTimeComponent($date) {
        $hourComponent = $date->format("H");
        $minuteComponent = $date->format("i");
        $secondComponent = $date->format("s");
        
        //Calculate the time component by converting the time-based units to seconds. Assuming
        //we only need to go down to the second
        return ($hourComponent * 60 * 60) + ($minuteComponent * 60) + $secondComponent;
    }
    
     /**
      * Calculates the number of elapsed days between 2 date values. Although this functionality can
      * be achieved via the date_diff() function, in the interests of the technical exercise, I decided
      * to implement my own function.
      *
      * @param DateTime $date1 First Date of given date range
      * @param DateTime $date2 Second Date of given date range
      * @param String $unit Time unit to convert calculation to. Valid values to $unit are:
      *   - "y": years
      *   - "h": hours
      *   - "m": minutes
      *   - "s": seconds
      *      
      * If no unit or any value other than the listed values is provided, the calculation result will be
      * in the unit of days. This calculation also incorporates the time component if specified as part of the
      * date
      * @return Float The number of days elapsed between $date1 and $date2 for the given $unit value
      */
    public static function calcNumDays($date1, $date2, $unit) {
        //I am aware that there is a built-in date_diff function in PHP that can be used
        //to calculate the difference of 2 dates. In the spirit of this exercise, I decided  
        //to programmatically calculate the difference, rather than use that function.
        //Granted, in a real-world client scenario where date differences need to occur, I
        //would be using the date_diff function
        $numDaysDate1 = self::convertDateToNumDays($date1);
        $numDaysDate2 = self::convertDateToNumDays($date2);
        
        //Extract the time components from both dates and calculate the total number of seconds
        //for each
        $timeComponentDate1 = self::calculateTimeComponent($date1);
        $timeComponentDate2 = self::calculateTimeComponent($date2);
        
        $timestampComponentElapsed = ($timeComponentDate1 - $timeComponentDate2) / 60 / 60 / 24;
        
        return self::convertToUnit(abs($numDaysDate1 - $numDaysDate2) + $timestampComponentElapsed, "d", $unit);
    } 
    
    /**
      * Calculates the number of elapsed weekdays between 2 date values.
      *
      * @param DateTime $date1 First Date of given date range
      * @param DateTime $date2 Second Date of given date range
      * @param String $unit Time unit to convert calculation to. Valid values to $unit are:
      *   - "y": years
      *   - "h": hours
      *   - "m": minutes
      *   - "s": seconds
      *      
      * If no unit or any value other than the listed values is provided, the calculation result will be
      * in the unit of days. This calculation also incorporates the time component if specified as part of the
      * date
      * @return Float The number of weekdays elapsed between $date1 and $date2 for the given $unit value
      */
    public static function calcNumWeekdays($date1, $date2, $unit) {
        //Determine the larger of the date values to ensure correct processing
        $minDate = $date1 >= $date2 ? clone $date2 : clone $date1;
        $maxDate = $date1 >= $date2 ? clone $date1 : clone $date2;
         
        //Get the day number for the smaller date. This will be used to determine
        //how many weekdays there are remaining for the first week
        $dayOfMinDate = $minDate->format("w");
        $remainingDaysFirstWeekDate1 = 0;
        $tempDate = clone $minDate;
        
        $minDateTimeComponent = 0;
        
        //Day number value of 0 corresponds to a Sunday, which is the start of the week.
        //If the Day number isn't 0, we can determine that for the first week that there
        //are 6 - day number days until the next Sunday (6 because we ignore the Sunday;
        //it signifies the start of a new week)
        if($dayOfMinDate != 0) {
            //(6 - day number) will give us the remaining number of weekdays for the first week
            
            if($dayOfMinDate >= 1 && $dayOfMinDate <= 5) {
                //Calculate the number of seconds that has already elapsed for the first day. 
                //This will be subtracted from the total calculation to account for the part of first day 
                //that remains
                $minDateTimeComponent = self::calculateTimeComponent($minDate) / 60 / 60 / 24;
            }
            
            $remainingDaysFirstWeekDate1 = 6 - $dayOfMinDate;
            $tempDate->modify("+". ($remainingDaysFirstWeekDate1 + 1). " days");
        }
        
        /*
            Total number of week days is calculated as:
        
            (Remaining number of days of first week + time that has not elapsed for date1) + 
            (Number of complete weeks * 5 days) + 
            (Day number of date2 + time that has elapsed for date2)
            (NOTE: if the day number date2 is on a Saturday, this means that 5 weekdays have passed for date2 anyway)
        */
        
        //Remaining number of days of first week + time that has not elapsed for date1
        $numWeekdays = $remainingDaysFirstWeekDate1;
        
        //The seconds component is only added to the calculation if the minDate day value
        //is not a weekend
        if($dayOfMinDate != 0 && $remainingDaysFirstWeekDate1 != 0 && $minDateTimeComponent > 0) {
            $numWeekdays += (1 - $minDateTimeComponent);
        }
        
        //Number of complete weeks * 5 days
        $numCompleteWeeks = self::calcNumCompleteWeeks($tempDate, $maxDate, null);
        $numWeekdays += ($numCompleteWeeks * 5);
        
        //Day number of date2
        $elapsedDaysDate2 = $maxDate->format("w");
        
        //(NOTE: if the day number date2 is on a Saturday, this means that 5 weekdays have passed for date2 anyway)
        if($elapsedDaysDate2 == 6) {
            $elapsedDaysDate2--;
        }
        
        $numWeekdays += $elapsedDaysDate2;
        
        //time that has elapsed for date2
        if($elapsedDaysDate2 >= 1 && $elapsedDaysDate2 <= 5) {
            //get the time component as we need to incorporate the part of the given
            //weekday that has elapsed if the end date is a weekday
            $timeComponent = self::calculateTimeComponent($maxDate) / 60 / 60 / 24;
            $numWeekdays += $timeComponent;
        }
        
        return self::convertToUnit($numWeekdays, "d", $unit);
    }

    /**
      * Calculates the number of complete weeks between 2 date values. Given the exercise asks
      * for a complete number of weeks, I have assumed the result should be an integer value
      *
      * @param DateTime $date1 First Date of given date range
      * @param DateTime $date2 Second Date of given date range
      * @param String $unit Time unit to convert calculation to. Valid values to $unit are:
      *   - "y": years
      *   - "h": hours
      *   - "m": minutes
      *   - "s": seconds
      *      
      * If no unit or any value other than the listed values is provided, the calculation result will be
      * in the unit of weeks. 
      * @return int The number of complete weeks elapsed between $date1 and $date2 for the given $unit value
      */
    public static function calcNumCompleteWeeks($date1, $date2, $unit) {
        $numDays = self::calcNumDays($date1, $date2, null);
        
        //As the exercise asks for the complete number of weeks ie. an integer value, 
        //consideration of the time component is irrelevant
        return self::convertToUnit(floor($numDays / 7), "w", $unit);
    }
    
    /**
      * Sets the timezone for the given date value. This will update the actual date variable
      * passed into this function
      *
      * @param DateTime $date1 Date for which timezone is to be changed
      * @param String $timeZone Time Zone to convert $date1 to. The list of valid Time Zone values is
      * based on the list of valid PHP timezone values
      *      
      * @return boolean Boolean value indicates whether the Time Zone conversion was successful
      */
    public static function setDateTimeZone(&$date, $timeZone) {
        if(!is_null($timeZone)) {
            if(in_array($timeZone, timezone_identifiers_list())) {
                date_timezone_set($date, timezone_open($timeZone));
                return true;
            }
        };
        
        return false;
    }
}
?>

