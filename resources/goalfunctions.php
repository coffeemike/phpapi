<?php

/*
*   @param $userid = brukerSession. Hvem som er logget inn.
*   @param $goaldescription = Hva man skal spare penger til
*   @param $goalamount = Hvor mye penger sparemålet er på. 
*
*/

include_once "db_con.php";

// Samle funksjon for å lage et sparemål.
function createGoal() {
    echo "Du har oppretta sparemål!";
}

// Henter ut alle goals basert på brukerid.
function getGoals() {
    
}

// Lager en rad i accounts med sparemål.
function insertAccount($userid, $goalname) {
    global $con;
    
    $stmt = $con->prepare("INSERT INTO account (owner_id, amount, name) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $userid, $amount, $goalname);
    
    $userid = $userid;
    $amount = 0;
    $goalname = $goalname;
    
    $stmt->execute();
    
    $stmt->free_result();
    $stmt->close();
}

// Lager en rad i goals med sparemål.
function insertGoal() {
    
}

?>