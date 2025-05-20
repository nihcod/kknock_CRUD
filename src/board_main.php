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
    <div class="container board-main">
        
        <div class="main-header">
            <h1>게시판</h1>
            <div class="btn-group">
                <a href="home.php"   class="button">홈으로</a>
                <a href="logout.php" class="button">로그아웃</a>
            </div>
        </div>

        
        <section class="notice-section">
            <h2>공지사항</h2>
            <ul class="notice-list">
                <?php
                $sql    = "SELECT * FROM Notice ORDER BY number DESC LIMIT 5";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $num   = $row['number'];
                    $title = htmlspecialchars($row['title']);
                    echo "<li><a href='notice_view.php?number={$num}' class='list-link'>{$title}</a></li>";
                }
                ?>
            </ul>
            <div class="more-link">
                <a href="notice_list.php" class="button small">더보기</a>
            </div>
        </section>

        
        <section class="board-section">
            <h2>자유게시판</h2>
            <ul class="board-list">
                <?php
                $sql    = "SELECT * FROM Board ORDER BY number DESC LIMIT 5";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $num   = $row['number'];
                    $title = htmlspecialchars($row['title']);
                    echo "<li><a href='board_view.php?number={$num}' class='list-link'>{$title}</a></li>";
                }
                ?>
            </ul>
            <div class="more-link">
                <a href="board_list.php" class="button small">더보기</a>
            </div>
        </section>
    </div>
</body>
</html>
