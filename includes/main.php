<?php

$conn = mysqli_connect('localhost', 'root', '', 'huntap');


function query($arg) {
    
    global $conn;
    $result = mysqli_query($conn, $arg);
    $rows = [];

    while($row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }

    return $rows;
}


?>