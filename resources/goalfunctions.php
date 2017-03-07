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
function getGoals($userid) {
    global $con;

    $stmt = $con->prepare("SELECT * FROM goals JOIN account ON goals.account_id = account.id WHERE account.owner_id = ?");
    $stmt->bind_param("i", $userid);
    
    $userid = $userid;

    $stmt->execute();

    $stmt->store_result();
    
    if ($stmt->num_rows < 1) {
        return 0;
        $stmt->free_result();
        $stmt->close();
    }
    $stmt->bind_result($goalResult);
    
    while ($row = $stmt->fetch()) {
        return $goalResult;
        $stmt->free_result();
        $stmt->close();
    }
    
    $stmt->free_result();
    $stmt->close();
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

// Hente ut accounten som nettopp ble laget.
function getAccount($userid) {
    global $con;
    
    $stmt = $con->prepare("SELECT id FROM account WHERE owner_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("i", $userid);
    
    $userid = $userid;
    
    $stmt->execute();
    
    $stmt->store_result();
    if ($stmt->num_rows < 1) {
        return 0;
        $stmt->free_result();
        $stmt->close();
    }
    $stmt->bind_result($dbaccount);
    
    while ($row = $stmt->fetch()) {
        return $dbaccount;
        $stmt->free_result();
        $stmt->close();
    }
    
    $stmt->free_result();
    $stmt->close();
}

// Lager en rad i goals med sparemål.
function insertGoal($account, $goalname, $amount) {
    global $con;
    
    $stmt = $con->prepare("INSERT INTO goals (account_id, goal, created, goal_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $account, $amount, $datetime, $goalname);
    $datetime= date("Y-m-d H:i:s");
    $account = $account;
    $goalname = $goalname;
    $amount = $amount;
    
    $stmt->execute();
    
    $stmt->free_result();
    $stmt->close();
}

?>