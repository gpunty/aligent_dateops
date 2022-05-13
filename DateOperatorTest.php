<?php
use PHPUnit\Framework\TestCase;
include("phpinfo.php");
//The following functions are by no means an exhaustive list of tests, but it does demonstrate
//a range of successful scenarios
final class DateOperatorTest extends TestCase {
    //Test that the my call to calcNumDays matches the built-in date_diff() function
    public function testCalcNumDaysEqualToDateDiff() {
        $date1 = date_create("2022-03-03");
        $date2 = date_create("2022-01-01");
        
        $numDaysDateDiff1 = date_diff($date1, $date2);
        $numDaysDateDiffValue1 = $numDaysDateDiff1->format("%a");
        
        $numDaysDateDiff2 = DateOperator::calcNumDays($date1, $date2, null);
        
        $this->assertEquals($numDaysDateDiffValue1, $numDaysDateDiff2);
    }
    
    //Test that the my call to calcNumDays converted to hours matches the built-in date_diff() function converted to hours
    public function testCalcNumDaysEqualToDateDiffHours() {
        $date1 = date_create("2022-03-03");
        $date2 = date_create("2022-01-01");
        
        $numDaysDateDiff1 = date_diff($date1, $date2);
        $numDaysDateDiffValue1 = ($numDaysDateDiff1->format("%a")) * 24;
        
        $numDaysDateDiff2 = DateOperator::calcNumDays($date1, $date2, "h");
        
        $this->assertEquals($numDaysDateDiffValue1, $numDaysDateDiff2);
    }
    
    //Test that the my call to calcNumDays converted to minutes matches the built-in date_diff() function converted to minutes
    public function testCalcNumDaysEqualToDateDiffMinutes() {
        $date1 = date_create("2022-03-03");
        $date2 = date_create("2022-01-01");
        
        $numDaysDateDiff1 = date_diff($date1, $date2);
        $numDaysDateDiffValue1 = ($numDaysDateDiff1->format("%a")) * 24 * 60;
        
        $numDaysDateDiff2 = DateOperator::calcNumDays($date1, $date2, "m");
        
        $this->assertEquals($numDaysDateDiffValue1, $numDaysDateDiff2);
    }
    
    //Test that the my call to calcNumDays converted to seconds matches the built-in date_diff() function converted to seconds
    public function testCalcNumDaysEqualToDateDiffSeconds() {
        $date1 = date_create("2022-03-03");
        $date2 = date_create("2022-01-01");
        
        $numDaysDateDiff1 = date_diff($date1, $date2);
        $numDaysDateDiffValue1 = ($numDaysDateDiff1->format("%a")) * 24 * 60 * 60;
        
        $numDaysDateDiff2 = DateOperator::calcNumDays($date1, $date2, "s");
        
        $this->assertEquals($numDaysDateDiffValue1, $numDaysDateDiff2);
    }
    
    //Test that the my call to calcNumDays matches the built-in date_diff() function, where the
    //time component is specified
    public function testCalcNumDaysEqualToDateDiffWithTime() {
        $date1 = date_create("2022-03-03 23:00:00");
        $date2 = date_create("2022-01-01 22:15:35");
        
        $numDaysDateDiff1 = date_diff($date1, $date2);
        $numDaysDateDiffValue1 = $numDaysDateDiff1->format("%a") + 
                                 (($numDaysDateDiff1->format("%h") / 24) + 
                                 ($numDaysDateDiff1->format("%i") / 60 / 24) +
                                 ($numDaysDateDiff1->format("%s") / 60 / 60 / 24)) ;
        
        $numDaysDateDiff2 = DateOperator::calcNumDays($date1, $date2, null);
        
        $this->assertEquals($numDaysDateDiffValue1, $numDaysDateDiff2);
    }
    
    //For the weekday-based tests, I figured it would be quicker to produce a hard-coded 
    //expected result for the purposes of this exercise
    public function testCalcNumWeekdaysStartDateWeekend() {
        $date1 = date_create("2022-03-03");
        $date2 = date_create("2022-01-01"); //Saturday date
        
        $numWeekdays = DateOperator::calcNumWeekdays($date1, $date2, null);
        
        $this->assertEquals($numWeekdays, 44);
    }
    
    //Calculating number of weekdays, specifying one weekend date and one weekday date
    public function testCalcNumWeekdaysEndDateWeekend() {
        $date1 = date_create("2022-03-06"); //Sunday date
        $date2 = date_create("2022-01-03"); //Monday date
        
        $numWeekdays = DateOperator::calcNumWeekdays($date1, $date2, null);
        
        $this->assertEquals($numWeekdays, 45);
    }
    
    //Calculating number of weekdays, specifying both weekend dates
    public function testCalcNumWeekdaysStartAndEndDateWeekend() {
        $date1 = date_create("2022-03-06"); //Sunday date
        $date2 = date_create("2022-01-01"); //Saturday date
        
        $numWeekdays = DateOperator::calcNumWeekdays($date1, $date2, null);
        
        $this->assertEquals($numWeekdays, 45);
    }
    
    //Calculating complete number of weeks, where the 2 days are weekday dates
    public function testCalcNumCompleteWeeksWeekdayDates() {
        $date1 = date_create("2022-03-02"); //Thursday date
        $date2 = date_create("2022-04-26"); //Tuesday date
        
        $numWeeks = DateOperator::calcNumCompleteWeeks($date1, $date2, null);
        
        $numWeeksDD = floor(date_diff($date1, $date2)->format("%a") / 7);
        
        $this->assertEquals($numWeeks, $numWeeksDD);
    }
    
    //Calculating complete number of weeks, where 1 date is a weekend date; the other
    //date is a weekday date
    public function testCalcNumCompleteWeeksEndDateWeekend() {
        $date1 = date_create("2022-03-02"); //Thursday date
        $date2 = date_create("2022-05-01"); //Saturday date
        
        $numWeeks = DateOperator::calcNumCompleteWeeks($date1, $date2, null);
        
        $numWeeksDD = floor(date_diff($date1, $date2)->format("%a") / 7);
        
        $this->assertEquals($numWeeks, $numWeeksDD);
    }
    
    //Testing that the setting of the time zone was successful
    public function testSetDateTimeZone() {
        $date1 = date_create("2022-03-02");
        $timezone = "Australia/Adelaide";
        $successfulCall = DateOperator::setDateTimeZone($date1, $timezone);
        
        $this->assertEquals($timezone, timezone_name_get(date_timezone_get($date1)));
        $this->assertTrue($successfulCall);
    }
}
?>