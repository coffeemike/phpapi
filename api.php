<?php

/*
*
* Action:
* 1 = Send Penger
* 2 = Opprette sparemål
* 3 = Hente ut alle sparemål
* 4 = Slette et sparemål
* 5 = Sjekker login
* 6 = Sjekker registrering
* 7 = Henter ut alle transaksjoner til en person.
*
*/

// Funksjonsfiler inkluderes
include_once "resources/sendfunctions.php";
include_once "resources/goalfunctions.php";
include_once "resources/loginfunctions.php";

// Initialiserer APIet.
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400'); 
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
 
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// Raw JSON data fra appen
$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    
    // Dekoder json dataen
    $request = json_decode($postdata);
    
    // Sjekker om action er satt
    if (isset($request->action)) {
        $action = $request->action;
    }
    else {
        echo "Action er ikke satt";
        die();
    }
    
    // Sjekker om vi har kontakt med databasen.
    if (checkDBCon($con) == 1) {
        echo "Får ikke kontakt med databasen.";
        return 0;
    }
    
    // Spare penger.
    if ($action == 1) {
        if (isset($request->amount)) {
            $amount = $request->amount;
        }
        if (isset($request->userid)) {
            $userid = $request->userid;
        }
        if (isset($request->reciever)) {
            $reciever = $request->reciever;
        }
        // Hvis man ikke skriver inn antall kroner man vil sende, får man respons.
        if ($amount == "") {
            echo "Vennligst skriv inn hvor mye du vil spare.";
            return 0;
        }
        
        $sender = getSenderAccount($userid);
        // Kjører funksjonene som overfører penger.
        echo sendMoney($userid, $sender, $reciever, $amount);
    }
    
    // Opprette sparemål
    if ($action == 2) {
        if (isset($request->name)) {
            $name = $request->name;
        }
        
        if (isset($request->amount)) {
            $amount = $request->amount;
        }
        
        if (isset($request->userid)) {
            $userid = $request->userid;
        }
        // Kjører funksjonen som lager nytt sparemål
        echo createGoal($userid, $name, $amount);
    }
    
    // Henter ut alle sparemål
    if ($action == 3) {
        if (isset($request->userID)) {
            $userid = $request->userID;
        }
        // Lager et array som inneholder alle sparemål til en bruker
        $acc = getGoals($userid);
        
        // Gjør om arrayet til json format og sender det i respons
        echo json_encode($acc);
        die();
    }
    
    // Sletter et sparemål.
    if ($action == 4) {
        if (isset($request->account_id)) {
            $account_id = $request->account_id;
        }
        if (isset($request->userid)) {
            $userid = $request->userid;
        }
        echo removeGoalAcc($userid, $account_id);
    }
    
    // Sjekker login.
    if ($action == 5) {
        if (isset($request->email)) {
            $email = $request->email;
        }
        if (isset($request->password)) {
            $pass = $request->password;
        }
        echo checkLogin($email, $pass);
    }
    
    // Sjekker og registrer en ny bruker.
    if ($action == 6) {
        if (isset($request->email)) {
            $email = $request->email;
        }
        if (isset($request->pass)) {
            $pass = $request->pass;
        }
        if (isset($request->passrep)) {
            $passrep = $request->passrep;
        }
        if (isset($request->name)) {
            $name = $request->name;
        }
        if (isset($request->ssn)) {
            $ssn = $request->ssn;
        }
        echo registerUser($email, $pass, $passrep, $name, $ssn);
    }
    
    // Returnerer logg av alle transaksjoner.
    if ($action == 7) {
        if (isset($request->userid)) {
            $userid = $request->userid;
        }
        $arr = getTransactions($userid);
        echo json_encode($arr);
        die();
    }
    
    if ($action == 8) {
        if (isset($request->userid)) {
            $userid = $request->userid;
        }
        $arr = getAllAchis($userid);
        echo json_encode($arr);
    }
}
else {
    echo "En feil oppstod!";
}
?>