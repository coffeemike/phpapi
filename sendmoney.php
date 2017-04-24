<?php

/*
*
* IKKE I BRUK
* Ble brukt som første utkast til testing.
*
*/

$con = mysqli_connect('knarbakk.asuscomm.com', 'knarbakk', 'knarbakk_database_for_blog', 'dnb');

if (!$con) {
    echo 'Kunne ikke koble til database';
    die();
}

if (isset($_GET['amount'])) {
    $amount = mysqli_real_escape_string($con, $_GET['amount']);
}

if (isset($_GET['sender'])) {
    $sender = mysqli_real_escape_string($con, $_GET['sender']);
}

if (isset($_GET['reciever'])) {
    $reciever = mysqli_real_escape_string($con, $_GET['reciever']);
}

$datetime = date("Y-m-d H:i:s");

$sql = "INSERT INTO transactions (sender_id, reciever_id, datetime, amount) VALUES ('$sender', '$reciever', '$datetime', '$amount')";

$res = mysqli_query($con, $sql);

if (!$res) {
    echo "feil";
    die();
}

echo "nice";

?>