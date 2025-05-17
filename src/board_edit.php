<?php
session_start();
include "dbconn.php";
if (!isset($_GET['number'])){
    header("Location: board_main.php");
    exit();
}
$number = $_GET['number'];
$sql = "SELECT * FROM Board WHERE number=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $number);
$stmt->execute();
$result=$stmt->get_result();
$row=$result->fetch_assoc();

//TODO 세션 검증 추가
if ($_SESSION['user_name'] != $row['id']){
    header('Location: board_main.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 수정</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post" action="board_update.php" enctype="multipart/form-data">
        <input type="hidden" name="number" value="<?php echo $row['number'];?>"/>
        <h3>글 제목</h3>
        <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']);?>"></><br>
        <h3>내용을 입력해 주세요.</h3>
        <textarea name="content"><?php echo htmlspecialchars($row['content']);?></textarea><br>
        <?php if (!empty($row['file_name'])): ?>
            <p>현재 첨부파일: <a href="<?= $row['file_path'] ?>" download><?= htmlspecialchars($row['file_name']) ?></a></p>
            <label><input type="checkbox" name="delete_file" value="1"> 첨부파일 삭제</label><br>
        <?php endif; ?>
        <h3>새 파일 업로드</h3>
        <input type="file" name="upload_file"><br><br>
        <a href="board_main.php" class="button">취소</a>
        <input type="submit" value="수정">
    </form>
</body>
</html>