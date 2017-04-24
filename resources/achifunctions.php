<?php

/*
* 
* Function must be looped to check every single achievement every time it runs.
* 
* 
*/

include_once "db_con.php";
include_once "goalfunctions.php";

// Main func to use other funcs.
function checkAchi($userid) {
    global $con;
    $accinfo = getAccInfo($userid);
    
    
    $sql = "SELECT * FROM achievements";

    $res = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($res) < 1) {
        return 0;
    }
    while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
        
        if ($row['type'] == 1) {
            for ($i = 0; $i < sizeof($accinfo); $i++) {
                if ($accinfo[$i]['oppspart'] >= $row['trigger_amount']) {
                    if (checkAlreadyOwned($userid, $row['id']) == 0) {
                        insertAchiUser($userid, $row['id']);
                        //echo "Achieved goal: " . $row['name'] . "<br>";
                    }
                    else {
                        //echo "Achieved, but already loaded <br>";
                    }
                }
                else {
                    //echo "-- Not Achieved: ".$row['name']."<br>";
                }
            }
        }
        
        if ($row['type'] == 2) {
            $donegoal = 0;
            for ($i = 0; $i < sizeof($accinfo); $i++) {
                $percent = ($accinfo[$i]['oppspart'] / $accinfo[$i]['goal'] * 100);
                if (strpos($row['description'], '25') !== false) {
                    if ($percent >= 25) {
                        if (checkAlreadyOwned($userid, $row['id']) == 0) {
                            insertAchiUser($userid, $row['id']);
                            //echo "Achieved goal: " . $row['name'] . "<br>";
                        }
                        else {
                            //echo "Achieved, but already loaded <br>";
                        }
                    }
                    else {
                        //echo "-- Not Achieved: 25% <br>";
                    }
                }
                if (strpos($row['description'], '50') !== false) {
                    if ($percent >= 50) {
                        if (checkAlreadyOwned($userid, $row['id']) == 0) {
                            insertAchiUser($userid, $row['id']);
                            //echo "Achieved goal: " . $row['name'] . "<br>";
                        }
                        else {
                            //echo "Achieved, but already loaded <br>";
                        }
                    }
                    else {
                        //echo "-- Not Achieved: 50% <br>";
                    }
                }
                if ($percent >= 100) $donegoal++;
            }
        }
        
    }
    if ($donegoal >= 1) {
        if (checkAlreadyOwned($userid, 10) == 0) {
            insertAchiUser($userid, 10);
            //echo "Achieved goal: 1 sparemål fullført<br>";
        }
        else {
            //echo "Achieved, but already loaded <br>";
        }
    }
    else {
        //echo "-- Not Achieved: 1 sparemål fullført<br>";
    }
    
    if ($donegoal >= 5) {
        if (checkAlreadyOwned($userid, 11) == 0) {
            insertAchiUser($userid, 11);
            //echo "Achieved goal: 5 sparemål fullført<br>";
        }
        else {
            //echo "Achieved, but already loaded <br>";
        }
    }
    else {
        //echo "-- Not Achieved: 5 sparemål fullført<br>";
    }
    
    if ($donegoal >= 10) {
        if (checkAlreadyOwned($userid, 12) == 0) {
            insertAchiUser($userid, 12);
            //echo "Achieved goal: 10 sparemål fullført<br>";
        }
        else {
            //echo "Achieved, but already loaded <br>";
        }
    }
    else {
        //echo "-- Not Achieved: 10 sparemål fullført<br>";
    }
}

function getAchievements() {
    global $con;
    
}

// Check if achi is already owned by the user.
// 1 = already owned, 0 = not owned
function checkAlreadyOwned($userid, $achiid) {
    global $con;
    
    $stmt = $con->prepare("SELECT id FROM achiusers WHERE user_id = ? AND achi_id = ?");
    $stmt->bind_param("ii", $userid, $achiid);
    
    $userid = $userid;
    $achiid = $achiid;
    
    $stmt->execute();
    
    $stmt->store_result();
    if ($stmt->num_rows < 1) {
        return 0;
    }
    else {
        return 1;
    }
    $stmt->free_result();
    $stmt->close();
}

// Get info like whats the goal, and how much saved. 
function getAccInfo($userid) {
    global $con;
    $usid = $userid;
    $sql = "SELECT account.id AS id, goals.goal_name AS name, goals.goal AS goal, account.amount AS oppspart FROM goals JOIN account ON goals.account_id = account.id WHERE account.owner_id = '$usid'";

    $res = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($res) < 1) {
        return 0;
    }
    
    $arr = array();
    while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
        $arr[] = $row;
    }
    return $arr;
}

// Inserts the achievement to the achi table.
function insertAchiUser($userid, $achiid) {
    global $con;
    
    $stmt = $con->prepare("INSERT INTO achiusers (user_id, achi_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userid, $achiid);
    
    $userid = $userid;
    $achiid = $achiid;
    
    $stmt->execute();
    
    $stmt->free_result();
    $stmt->close();
}

function getNumDoneGoals($userid) {
    global $con;
    
    $usid = $userid;
    $sql = "SELECT ";

    $res = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($res) < 1) {
        return 0;
    }
    
    $arr = array();
    while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
        $arr[] = $row;
    }
    return $arr;
}

function getAllAchis($userid) {
    global $con;
    $usid = $userid;
    $sql = "SELECT achievements.name AS name, achievements.description AS descri FROM achiusers JOIN achievements ON achiusers.achi_id = achievements.id WHERE achiusers.user_id = '$usid'";

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