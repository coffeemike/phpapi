<?php

include "config.php"; // Config fil

// Oppretter en database tilkobling.
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Funksjon for å sjekke om tilkoblingen fungerer.
function checkDBCon($con) {
    if ($con->connect_error) {
        return 1;
    }
    return 0;
}

// Setter charset til utf8.
mysqli_set_charset($con, "utf8");


?>