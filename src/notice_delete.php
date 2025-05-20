<?php
session_start();
include "dbconn.php";
$number = $_GET['number'];
if (!isset($number)){
    header("Location: board_main.php?error=nonono");
    exit();
}
$sid = $_SESSION['id'];
$sun = $_SESSION['user_name'];
if (!isset($sid) || !isset($sun)){
    header("Location: index.php?error=세션 만료! 로그인을 다시 해주세요.");
    exit();
}


$sql = "SELECT * FROM Notice WHERE number=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $number);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row){
    header("Location: notice_list.php?error=nonono");
    exit();
}

if ($sun == 'admin'){
    $sql2 = "DELETE FROM Notice WHERE number=?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $number);
    $stmt2->execute();

    header("Location: notice_list.php");
    exit();
}

?>