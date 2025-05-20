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
    <title>Document</title>
</head>
<body>
    <div class="container notice-list-page">
    <div class="btn-group">
        <?php
        if ($_SESSION['user_name'] == 'admin')
            echo "<a href='notice_write.php' class='button'>글 쓰기</a>";
        ?>
            <a href="board_main.php" class="button">메인으로</a>
    </div>
    <ul class="notice-list">
            <h2>공지사항</h2>
            <?php
            $sql = "SELECT * FROM Notice ORDER BY number DESC";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $number = $row['number'];
                $title = htmlspecialchars($row['title']);
                echo "<li><a href='notice_view.php?number={$number}' class='list-link'>{$title}</a></li>";
            }
            ?>
    </ul>
    </div>
</body>
</html>