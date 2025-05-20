<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])){
    header("Location: index.php?error=로그인이 필요합니다.");
    exit();
}
if ($_SESSION['user_name'] != 'admin' || $_SESSION['id'] != 'admin')
{
    echo "<script>alert('권한이 없습니다.'); history.back()</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 쓰기</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST" action="notice_insert.php" enctype="multipart/form-data">
        <h3>공지사항 제목</h3>
        <input type="text" name="title" placeholder="공지사항 제목을 입력하세요."><br>
        <h3>공지내용을 입력해 주세요.</h3>
        <textarea name="content" placeholder="공지내용을 입력하세요.(최대 1000자까지 입력 가능)"></textarea><br>
        <h3>파일 업로드</h3>
        <input type="file" name="upload_file"><br>
        <a href="board_main.php" class="button">취소</a>
        <input type="submit" value="작성">
    </form>
</body>
</html>