<?php

/*
*   @param $userid = brukerSession. Hvem som er logget inn.
*   @param $goaldescription = Hva man skal spare penger til
*   @param $goalamount = Hvor mye penger sparemålet er på. 
*
*/

include_once "db_con.php";
include_once "sendfunctions.php";

// Samle funksjon for å lage et sparemål.
function createGoal($userid, $goaldesc, $goalamount) {
    insertAccount($userid, $goaldesc);
    $account = getAccount($userid);
    if ($account == 0) return "En feil oppstod";
    insertGoal($account, $goaldesc, $goalamount);
    return "Sparemål oppretta!";
}

// Henter ut alle goals basert på brukerid.
function getGoals($userid) {
    global $con;
    $usid = mysqli_real_escape_string($con, $userid);
    $sql = "SELECT account.id AS id, goals.goal_name AS name, goals.goal AS goal, account.amount AS oppspart FROM goals JOIN account ON goals.account_id = account.id WHERE account.owner_id = '$usid'";

    $res = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($res) < 1) {
        return 0;
    }
    
    $arr = array();
    while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
        $arr[] = $row;
    }
    echo json_encode($arr);
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

//Under denne linjen begynner funksjonene for å slette kontoer og mål.

function removeGoalAcc($userid, $accountid) {
    $checkOwner = checkOwner($userid, $accountid);
    if ($checkOwner == 0) {
        return "Ikke din konto";
    }
    else if ($checkOwner == 1) {
        $dbamount = getAccountMoney($accountid);
        refundMoney($userid, $accountid, $dbamount);
        echo "penger på konto";
        var_dump($dbamount);
    }
    
    deleteGoal($accountid);
    deleteAccount($accountid);
    return "Slettet!";
}

function refundMoney($userid, $accountid, $amount) {
    $owneracc = getMainAccount($userid);
    var_dump($owneracc);
    sendMoney($userid, $accountid, $owneracc, $amount);
}

function getMainAccount($userid) {
    global $con;
    
    $stmt = $con->prepare("SELECT id FROM account WHERE owner_id = ? ORDER BY id ASC LIMIT 1");
    $stmt->bind_param("i", $userid);
    
    $stmt->execute();
    
    $stmt->store_result();
    
    $stmt->bind_result($mainacc);
    
    while ($row = $stmt->fetch()) {
        return $mainacc;
    }
    
}

function getAccountMoney($accountid) {
    global $con;
    
    $stmt = $con->prepare("SELECT amount FROM account WHERE id = ?");
    $stmt->bind_param("i", $accountid);
    
    $stmt->execute();
    
    $stmt->store_result();
    if ($stmt->num_rows < 1) {
        return 0;
        $stmt->free_result();
        $stmt->close();
    }
    $stmt->bind_result($dbamount);
    
    while ($row = $stmt->fetch()) {
        return $dbamount;
    }
    
}

function deleteAccount($accountid) {
    global $con;
    
    $stmt = $con->prepare("DELETE FROM account WHERE id = ?");
    $stmt->bind_param("i", $accountid);

    $stmt->execute();

    $stmt->free_result();
    $stmt->close();
}

function deleteGoal($account_id) {
    global $con;

    $stmt = $con->prepare("DELETE FROM goals WHERE account_id = ?");
    $stmt->bind_param("i", $account_id);

    $account_id = $account_id;
    
    $stmt->execute();

    $stmt->free_result();
    $stmt->close();

    
}

function checkOwner($userid, $accountid) {
    global $con;
    
    $stmt = $con->prepare("SELECT account.amount AS am FROM users JOIN account ON users.id = account.owner_id WHERE users.id = ? AND account.id = ?");
    $stmt->bind_param("ii", $userid, $accountid);
    
    $stmt->execute();
    
    $stmt->store_result();
    if ($stmt->num_rows < 1) {
        return 0;
        $stmt->free_result();
        $stmt->close();
    }
    $stmt->bind_result($dbamount);
    
    while ($row = $stmt->fetch()) {
        if ($dbamount > 0) {
            //$stmt = $con->prepare("UPDATE account(3) SET amount = amount + $dbamount WHERE owner_id = 4");
            //$stmt->execute();
            return 1;
        }
    }
    $stmt->free_result();
    $stmt->close();
    return 2;
}

?>