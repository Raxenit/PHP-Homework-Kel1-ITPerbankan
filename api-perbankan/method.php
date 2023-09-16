<?php

require_once "connection.php";

function getAllDataWhere($table, $where = 0)
{
    global $conn;
    if($where <= 0){
        $sql = "SELECT * FROM $table";
        // eksekusi query
        $result = $conn->query($sql);

        // masukan results ke dalam array result
        return $result->fetch_all(MYSQLI_ASSOC);
    }else{
        $sql = "SELECT * FROM $table WHERE id='$where'";
        $result= $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}