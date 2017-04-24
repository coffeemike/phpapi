<?php

/*
*
*   Har selvfølgelig vært så flink å skrive "reciever" på alt som er
*   I motsetning til "receiver" som det egentlig skrives.
*
*   @param $userid = brukerSession. Hvem som er logget inn.
*   @param $sender = Avsender konto som forespør en transaksjon
*   @param $reciever = Mottaker konto som får inn penger.
*   @param $amount = Antall kroner som skal sendes. 
*
*/

include_once "db_con.php";
include_once "achifunctions.php";


// Samle funksjon som går igjennom alle stega for å sende penger.
// Returverdiene er det som skal sendes i respons til appen.
function sendMoney($userid, $sender, $reciever, $amount) {
    global $con;
    
    if ($amount < 1) {
        return "Vennligst skriv et positivt tall.";
    }
    
    $check = checkOwnerCash($userid, $sender, $amount);
    // Return verdien til variabelen over lagres.
    // Sjekker om avsender eier kontoen det sendes penger fra.
    if ($check == 0) {
        return "En feil oppstod, eller du prøver å sende fra en konto som ikke er din.";
    }
    // Hvis avsender eier kontoen, sjekkes det så om han har nok penger til å sende.
    else if ($check == 1) {
        return "Du har ikke så mye penger på kontoen.";
    }
    // Loggfører transaksjonen i et eget table
    insertTransactions($sender, $reciever, $amount);
    
    // Fjerner penger fra avsenders konto
    removeMoney($sender, $amount);
    
    // Legger til penger på mottakers konto
    addMoney($reciever, $amount);
    
    checkAchi($userid);
    
    return $amount;
}

// Loggfører transaksjoner i eget table.

function insertTransactions($sender, $reciever, $amount) {
    global $con;
    
    $stmt = $con->prepare("INSERT INTO transactions (sender_id, reciever_id, datetime, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisd", $sender, $reciever, $datetime, $amount);
    
    $datetime = date("Y-m-d H:i:s");
    $sender = $sender;
    $reciever = $reciever;
    $amount = $amount;
    
    $stmt->execute();
    
    $stmt->free_result();
    $stmt->close();
}

// Funksjon for å fjerne penger fra sender konto

function removeMoney($sender, $amount) {
    global $con;
    
    $stmt = $con->prepare("UPDATE account SET amount = amount - ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $sender);
    
    $sender = $sender;
    $amount = $amount;
    
    $stmt->execute();
    
    $stmt->free_result();
    $stmt->close();
}

// Funksjon for å legge til penger på mottaker konto

function addMoney($reciever, $amount) {
    global $con;
    
    $stmt = $con->prepare("UPDATE account SET amount = amount + ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $reciever);
    
    $reciever = $reciever;
    $amount = $amount;
    
    $stmt->execute();
    
    $stmt->free_result();
    $stmt->close();
}

function checkOwnerCash($userid, $sender, $amount) {
    global $con;
    
    $stmt = $con->prepare("SELECT account.amount AS am FROM users JOIN account ON users.id = account.owner_id WHERE users.id = ? AND account.id = ?");
    $stmt->bind_param("ii", $userid, $sender);
    
    $stmt->execute();
    
    $stmt->store_result();
    if ($stmt->num_rows < 1) {
        return 0;
        $stmt->free_result();
        $stmt->close();
    }
    $stmt->bind_result($dbamount);
    
    while ($row = $stmt->fetch()) {
        if ($dbamount < $amount) {
            $stmt->free_result();
            $stmt->close();
            return 1;
        }
    }
    $stmt->free_result();
    $stmt->close();
    return 2;
}

function getTransactions($userid) {
    global $con;
    $usid = mysqli_real_escape_string($con, $userid);
    $sql = "SELECT transactions.id AS id, a1.name AS sendername, a2.name AS recievername, transactions.amount AS amount, transactions.datetime FROM transactions
JOIN account a1 ON transactions.sender_id = a1.id
JOIN account a2 ON transactions.reciever_id = a2.id
WHERE a1.owner_id = '$usid'";

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

?>