<?php
session_start();
include "dbconn.php";

if (!isset($_POST['comment_number'], $_POST['board_number'], $_POST['content'])){
    header("Location: board_main.php");
    exit();
}
$comment_number = $_POST['comment_number'];
$board_number = $_POST['board_number'];
$content = $_POST['content'];

$sql = "SELECT id FROM comment WHERE number=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("i", $comment_number);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $_SESSION['user_name'] != $row['id'])
{
    header("Location: board_view.php?number=$board_number&error=수정 권한 없음");
    exit();
}
$sql2 = "UPDATE comment SET content=?, fixattime=NOW() WHERE number=?";
$stmt2=$conn->prepare($sql2);
$stmt2->bind_param("si", $content, $comment_number);
$stmt2->execute();
header("Location: board_view.php?number=$board_number");
exit();
?>