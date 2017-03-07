<?php

include_once "resources/sendfunctions.php";

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

// Test
$test = array("Verdi 1", "Verdi 2");

$postdata = file_get_contents("php://input");

if (isset($postdata)) {
    $request = json_decode($postdata);
    
    echo $test;
    die();
    
    // Gjør ikke noe enda.
    if (isset($request->action)) {
        $action = $request->action;
    }
    else {
        echo "En feil oppstod";
        die();
    }
    
    // TODO: Sjekk action, og kjør den riktige funksjonen basert på det
    // Feks. Hvis vi får inn action=1, så vet vi at brukeren prøver å overføre penger til konto. Dermed kjører vi den funksjonen.
    // Hvis vi får inn action=2, så vet vi at man prøver å opprette sparemål.
    
    // Spare penger.
    if ($action == 1) {
        if (isset($request->amount)) {
            $amount = $request->amount;
        }
        if (isset($request->sender)) {
            $sender = $request->sender;
        }
        if (isset($request->reciever)) {
            $reciever = $request->reciever;
        }
    
        if (checkDBCon($con) == 1) {
            echo "Får ikke kontakt med databasen.";
            return 0;
        }

        if ($amount == "") {
            echo "Venligst skriv inn hvor mye du vil spare.";
        //echo $test;
            return 0;
        }
    
        echo sendMoney(3, $sender, $reciever, $amount);
    }
    
    // Opprette sparemål
    if ($action == 2) {
        
    }
    
    
}
else {
    echo "En feil oppstod!";
}
?>