<?php

include_once "db_con";

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

?>