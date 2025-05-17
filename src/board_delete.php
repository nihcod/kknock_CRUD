<?php
session_start();
include "dbconn.php";
$number = $_GET['number'];
if (!isset($number)){
    header("Location: board_main.php?error=Access Denied");
    exit();
}
$sid = $_SESSION['id'];
$sun = $_SESSION['user_name'];
if (!isset($sid) || !isset($sun)){
    header("Location: home.php");
    exit();
}


$sql = "SELECT * FROM Board WHERE number=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $number);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row){
    header("Location: board_main.php?error=Not Found");
    exit();
}

if ($sun == $row['id'] || $sun == 'admin'){
    $sql2 = "DELETE FROM Board WHERE number=?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $number);
    $stmt2->execute();

    header("Location: board_main.php");
    exit();
}

?>