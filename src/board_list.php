<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: index.php?error=로그인이 필요합니다.");
    exit();
}
include "dbconn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>자유게시판</title>
</head>
<body>
    <div class="container board-list-page">
    <div class="btn-group">
            <a href="board_write.php" class="button">글 쓰기</a>
            <a href="board_main.php" class="button">메인으로</a>
    </div>
    <ul class="board-list">
            <h2>자유게시판</h2>
            <?php
            $sql = "SELECT * FROM Board ORDER BY number DESC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $number = $row['number'];
                $title = htmlspecialchars($row['title']);
                echo "<li><a href='board_view.php?number={$number}' class='list-link'>{$title}</a></li>";
            }
            ?>
    </ul>
    </div>
    
</body>
</html>