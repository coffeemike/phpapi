<?php

include "config.php";

$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

function checkDBCon($con) {
    if ($con->connect_error) {
        return 1;
    }
    return 0;
}

?>