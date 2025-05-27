<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: index.php?error=로그인이 필요합니다.");
    exit();
}
include "dbconn.php";
if (!isset($_GET['order']))
{
    header("Location: notice_list.php?order=desc");
    exit();
}
$order = 'desc';
if (isset($_GET['order']) && $_GET['order'] === 'asc') {
    $order = 'asc';
}
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
            <select id="array" onchange="location.href='notice_list.php?order=' + this.value;">
                <option value="desc" <?= $_GET['order'] === 'desc' ? 'selected' : '' ?>>최신순</option>
                <option value="asc" <?= $_GET['order'] === 'asc' ? 'selected' : '' ?>>오래된순</option>
            </select>
            <form action="notice_search_list.php?" method="GET">
                <select name="type" onchange="changeInputName()">
                    <option value="title">제목</option>
                    <option value="content">내용</option>
                    <option value="id">작성자</option>
                </select>
                <input type="text" name="query" placeholder="검색 내용을 입력하세요">
                <input type="hidden" name="order" value="<?= htmlspecialchars($_GET['order'] ?? 'desc') ?>">
                <input type="submit" value="검색">
            </form>            
            <?php
            if ($order == 'desc'){
                $sql = "SELECT * FROM Notice ORDER BY number DESC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $number = $row['number'];
                    $title = htmlspecialchars($row['title']);
                    echo "<li><a href='notice_view.php?number={$number}' class='list-link'>{$title}</a></li>";
                }
            }
            else if ($order == 'asc')
            {
                $sql = "SELECT * FROM Notice ORDER BY number ASC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $number = $row['number'];
                    $title = htmlspecialchars($row['title']);
                    echo "<li><a href='notice_view.php?number={$number}' class='list-link'>{$title}</a></li>";
                }
            }
            ?>
    </ul>
    </div>
</body>
</html>