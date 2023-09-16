<?php
    require_once "../../method.php";

    $id=0;

    if(!empty($_GET["id"])){
        $id=$_GET['id'];
    }
    $balance = getAllDataWhere("accounts",$id);

    echo json_encode($balance);
