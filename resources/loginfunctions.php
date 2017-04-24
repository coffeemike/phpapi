<?php

/*
*
* Login og registreringsfunksjoner hovedsaklig.
*
*   @param $userid = Referanse til en bruker
*   @param $email = Email til login/register
*   @param $pass = Passord til login/register
*   @param $passrep = Passord repetert i register
*   @param $name = Navn til register.
*   @param $ssn = "Social Security Number" = Fødselsnummer til register. 
*
*/

include_once "db_con.php";
include_once "goalfunctions.php";

// Henter ut en brukers første konto, som tilsvarer avsenderkonto.
function getSenderAccount($userid) {
    global $con;
    $stmt = $con->prepare("SELECT id FROM account WHERE owner_id = ? ORDER BY id ASC LIMIT 1");
    $stmt->bind_param("i", $id);
    
    $id = $userid;
    
    $stmt->execute();
    
    $stmt->store_result();
    if ($stmt->num_rows < 1) {
        return 0;
        $stmt->free_result();
        $stmt->close();
    }
    $stmt->bind_result($senderid);
    
    while ($row = $stmt->fetch()) {
        return $senderid;
    }
    $stmt->free_result();
    $stmt->close();
    
}

// Sjekker om logininfo stemmer. Hvis det ikke stemmer, returneres 0.
function checkLogin($email, $password) {
    global $con;
    
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    
    $email = $email;
    $password = $password;
    
    $stmt->execute();
    
    $stmt->store_result();
    if ($stmt->num_rows < 1) {
        return 0;
        $stmt->free_result();
        $stmt->close();
    }
    $stmt->bind_result($id);
    
    while ($row = $stmt->fetch()) {
        return $id;
    }
    $stmt->free_result();
    $stmt->close();
}

// Registrerer en ny brukerkonto og oppretter dummy konto og sparemål.
function registerUser($email, $password, $passwordrep, $name, $ssn) {
    global $con;
    
    $stmt = $con->prepare("INSERT INTO users (name, email, password, ssn) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $ssn);
    
    $email = $email;
    $password = $password;
    $passwordrep = $passwordrep;
    $name = $name;
    $ssn = $ssn;
    
    if ($password != $passwordrep) return "Passordene matcher ikke.";
    
    
    $stmt->execute();
    $stmt->free_result();
    $stmt->close();
    
    $userid = checkLogin($email, $password);
    
    createDummyAccount($userid);
    
    // Oppretter et sparemål som en slags "Tutorial".
    createGoal($userid, "Spar 10 kr.", 10);
    
    return "Registrert!";
}

// Oppretter en dummy konto når man registrerer seg som inneholder 2000 kr.
// Brukes for enkelt å kunne demonstrere løsningen og teste løsningen.
function createDummyAccount($userid) {
    global $con;
    
    $stmt = $con->prepare("INSERT INTO account (owner_id, amount, name) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $userid, $amount, $goalname);
    
    $userid = $userid;
    $amount = 2000;
    $goalname = "Main";
    
    $stmt->execute();
    
    $stmt->free_result();
    $stmt->close();
}

?>