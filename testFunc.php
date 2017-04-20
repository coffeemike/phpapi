<?php

include_once "resources/sendfunctions.php";
include_once "resources/goalfunctions.php";
include_once "resources/achifunctions.php";

//insertTransactions(5,2,1333.9);
//echo sendMoney(3, 4, 555);

echo checkAchi(1);
$row = getAccInfo(1);
var_dump($row);
echo "LOLz";
?>