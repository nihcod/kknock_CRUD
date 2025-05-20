<?php
    include "dbconn.php";
    session_start();
    if (!isset($_SESSION["user_name"]) || !isset($_SESSION["id"]))
    {
        header("Location: index.php?error=로그인 정보가 없습니다.");
        exit();
    }

    $number = $_GET["number"];
    if(!isset($number)){
        header("Location: notice_list.php");
        exit();
    }
    
    $sql = "SELECT * FROM Notice WHERE number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $number);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if(!$row){
        header("Location: notice_list.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 보기</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container board_view">
    <h1><?php echo htmlspecialchars($row['title']);?></h1>
    <p><strong>작성자 : </strong><?php echo htmlspecialchars($row['id']);?></p>
    <p><strong>작성시각 : </strong><?php echo $row['attime'];?></p>
    
    <?php if ($row['attime'] != $row['fixattime']):?>
        <p>최종 수정시각 : <?php echo $row['fixattime'];?></p>
    <?php endif;?>

    <hr>
    <p><?php echo nl2br(htmlspecialchars($row['content']));?></p>
    <hr>
    <?php if (isset($row['file_name']) && isset($row['file_path'])): ?>
        <p><strong>첨부파일</strong></p>
        <a href="<?= htmlspecialchars($row['file_path'])?>" class="button"
            download="<?= htmlspecialchars($row['file_path'])?>">
            <?= htmlspecialchars($row['file_name'])?></a>
        <hr>
    <?php endif; ?>
    
    <br><a href="notice_list.php" class="button">목록으로</a>
    <?php if ($_SESSION['user_name'] == $row['id'] || $_SESSION['user_name'] == 'admin'):?>
        <br><a href="notice_edit.php?number=<?php echo $row['number'];?>" class="button">수정하기</a>
        <br><a href="notice_delete.php?number=<?php echo $row['number']; ?>"
                    class="button" onclick="return confirm('정말 삭제 하시겠습니까?');">삭제하기</a>
    <?php endif;?>
</div>
</body>
</html>
