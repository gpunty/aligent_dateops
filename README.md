# aligent_dateops
Aligent Tech Exercise Code

I decided to code this exercise in PHP to demonstrate that I am able to pick up programming languages without too much fuss. I coded this on Mac OS, which has a built-in Apache server, and with some config changes required to enable the web server, enabled me to directly clone the aligent_dateops repository directly to the web server directory and test the API code by initially providing test code below the class declaration.

The exercise consists of three files:
    - phpinfo.php: contains the DateOperator class definition
    - tester.php: file that calls the various DateOperator functions
    - DateOperatorTest.php: phpunit test file

To run, after cloning the gpunty/aligent_dateops repository, run the tester file by running the following command in a terminal window:
    php tester.php
    
The DateOperatorTest phpunit tests can be run via the following command:
    phpunit DateOperatorTest.php
    
Access to the documentation for DateOperator can be found via docs/classes/DateOperator.html
