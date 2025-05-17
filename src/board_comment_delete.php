<?php
session_start();
include "dbconn.php";

if (!isset($_GET['comment_number']) || !isset($_GET['board_number']))
{
    header("Location: board_main.php?error=잘못된 접근입니다.");
    exit();
}
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name']))
{
    header("Location: index.php?error=잘못된 접근입니다.");
    exit();
}
$board_number = $_GET['board_number'];
$comment_number = $_GET['comment_number'];

$sql = "SELECT id FROM comment WHERE number=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("i", $comment_number);
$stmt->execute();
$result=$stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $_SESSION['user_name'] != $row['id'])
{
    header("Location: board_view.php?number=$board_number");
    exit();
}
$sql2 = "DELETE FROM comment WHERE number=?";
$stmt2=$conn->prepare($sql2);
$stmt2->bind_param("i", $comment_number);
$stmt2->execute();

header("Location: board_view.php?number=$board_number");
exit();

?>