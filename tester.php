<!DOCTYPE html>
<html>
<head>
<title>DateOperator Test Code</title>
</head>
<body>
    <h1>DateOperator Test Code</h1>
    <?php
    include("phpinfo.php");
    
    $date1 = date_create("2022-03-03");
    $date2 = date_create("2022-01-01");
    
    DateOperator::setDateTimeZone($date1, "Australia/Sydney");
    DateOperator::setDateTimeZone($date2, "Australia/Adelaide");
    
    $date1a = date_create("2022-03-03 11:00:00");
    $date2a = date_create("2022-01-01 10:30:00");
    
    $numDaysDateDiff = date_diff($date1a, $date2a);
    $numDaysDateDiffValue = $numDaysDateDiff->format("%a") + 
                            (($numDaysDateDiff->format("%h") / 24) + 
                             ($numDaysDateDiff->format("%i") / 60 / 24) +
                             ($numDaysDateDiff->format("%s") / 60 / 60 / 24));
                             
    echo "Date diff value: " . $numDaysDateDiffValue . "\n";
    
    echo "Number of years between Date 1 and Date 2 is " . DateOperator::calcNumDays($date1, $date2, "y") . "\n";
    echo "Number of days between Date 1 and Date 2 is " . DateOperator::calcNumDays($date1, $date2, null) . "\n";
    echo "Number of hours between Date 1 and Date 2 is " . DateOperator::calcNumDays($date1, $date2, "h") . "\n";
    echo "Number of minutes between Date 1 and Date 2 is " . DateOperator::calcNumDays($date1, $date2, "m") . "\n";
    echo "Number of seconds between Date 1 and Date 2 is " . DateOperator::calcNumDays($date1, $date2, "s") . "\n\n";
    
    echo "Number of weekdays between Date 1 and Date 2 (in years) is " . DateOperator::calcNumWeekdays($date1, $date2, "y") . "\n";
    echo "Number of weekdays between Date 1 and Date 2 (in days) is " . DateOperator::calcNumWeekdays($date1, $date2, null) . "\n";
    echo "Number of weekdays between Date 1 and Date 2 (in hours) is " . DateOperator::calcNumWeekdays($date1, $date2, "h") . "\n";
    echo "Number of weekdays between Date 1 and Date 2 (in minutes) is " . DateOperator::calcNumWeekdays($date1, $date2, "m") . "\n";
    echo "Number of weekdays between Date 1 and Date 2 (in seconds) is " . DateOperator::calcNumWeekdays($date1, $date2, "s") . "\n\n";
    
    echo "Number of complete weeks between Date 1 and Date 2 (in years) is " . DateOperator::calcNumCompleteWeeks($date1, $date2, "y") . "\n";
    echo "Number of complete weeks between Date 1 and Date 2 (in days) is " . DateOperator::calcNumCompleteWeeks($date1, $date2, null) . "\n";
    echo "Number of complete weeks between Date 1 and Date 2 (in hours) is " . DateOperator::calcNumCompleteWeeks($date1, $date2, "h") . "\n";
    echo "Number of complete weeks between Date 1 and Date 2 (in minutes) is " . DateOperator::calcNumCompleteWeeks($date1, $date2, "m") . "\n";
    echo "Number of complete weeks between Date 1 and Date 2 (in seconds) is " . DateOperator::calcNumCompleteWeeks($date1, $date2, "s") . "\n";
    ?>
</body>
</html>