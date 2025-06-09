<?php
session_start();
include "dbconn.php";

if (!isset($_GET['comment_number']) || !isset($_GET['board_number']))
    {
        header("Location: board_main.php?error=잘못된 접근입니다.");
        exit();
    }
$comment_number = (int)$_GET['comment_number'];
$board_number = (int)$_GET['board_number'];

$sql = "SELECT * FROM comment WHERE number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $comment_number);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();


if (!$row || $_SESSION['user_name'] != $row['id'])
{
    header("Location: board_view.php?number=$board_number&error=수정권한 없음");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>댓글 수정</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>댓글 수정</h2>
    <form method="POST" action="board_comment_update.php">
        <input type="hidden" name="comment_number" value="<?= $comment_number?>">
        <input type="hidden" name="board_number" value="<?= $board_number?>">
        <textarea name="content" maxlength="100"><?= htmlspecialchars($row['content']) ?></textarea>
        <a href="board_view.php?number=<?=$board_number?>" class="button">취소</a>
        <input type="submit" value="수정">
        
    </form>
</body>
</html>