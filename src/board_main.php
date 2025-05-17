<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: index.php?error=로그인이 필요합니다.");
    exit();
}
include "dbconn.php";
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시판 메인</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>글 목록</h1>

        <div class="btn-group">
            <a href="board_write.php" class="button">글 쓰기</a>
            <a href="home.php" class="button">홈으로</a>
        </div>

        <ul class="board-list">
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
