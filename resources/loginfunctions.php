<?php

include_once "db_con.php";

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
    return "Registrert!";
}

?>