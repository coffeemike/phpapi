<?php
/*
*
* Inneholder globale og statiske variabler som blir brukt til db_con og tidssone.
*
*/

define('DB_HOST', 'knarbakk.asuscomm.com'); // Database host
define('DB_USER', 'knarbakk'); // Database brukernavn
define('DB_PASS', 'knarbakk_database_for_blog'); // Database passord
define('DB_NAME', 'dnb'); // Database navn

define('TIMEZONE', 'Europe/Oslo'); // Definerer tidssonen
date_default_timezone_set(TIMEZONE); // Setter tidssonen

?>