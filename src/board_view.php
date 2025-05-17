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
        header("Location: board_main.php");
        exit();
    }
    
    $sql = "SELECT * FROM Board WHERE number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $number);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if(!$row){
        header("Location: board_main.php");
    }

    $sql2 = "SELECT * FROM comment WHERE board_number = ? ORDER BY attime ASC";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $number);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 보기</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
    <p><strong>댓글 목록</strong></p>
    
    <?php
        while($row2 = $result2->fetch_assoc()){
            echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
            echo "<p><strong>작성자:</strong> " . htmlspecialchars($row2['id']) . "</p>";
            echo "<p><strong>작성 시각:</strong> " . $row2['attime'] . "</p>";
            if ($row2['attime'] != $row2['fixattime']) {
                echo "<p><strong>수정 시각:</strong> " . $row2['fixattime'] . "</p>";
            }
            echo "<p>" . nl2br(htmlspecialchars($row2['content'])) . "</p>";
            if ($_SESSION['user_name'] == $row2['id']) {
            echo '<form action="board_comment_edit.php" method="get" style="display:inline;">
            <input type="hidden" name="comment_number" value="' . $row2['number'] . '">
            <input type="hidden" name="board_number" value="' . $row['number'] . '">
            <input type="submit" value="수정하기">
            </form>';
            echo '<form action="board_comment_delete.php" method="get"
             style="display:inline;" onsubmit="return confirm(\'정말 삭제하시겠습니까?\')">
            <input type="hidden" name="comment_number" value="' . $row2['number'] . '">
            <input type="hidden" name="board_number" value="' . $row['number'] . '">
            <input type="submit" value="삭제하기">
            </form>';
            }

            echo "</div>";
        }
    ?>
    <form method="POST" action="board_comment_write.php">
        <input type="hidden" name="board_number" value="<?php echo $row['number'];?>"/>
        <!-- <input type="text" name="content" placeholder="댓글 입력(최대 100자)" maxlength="100"/> -->
        <textarea name="content" maxlength="100" placeholder="댓글 입력(최대 100자)" rows="5"></textarea>
        <input type="submit" value="댓글 작성"/>
    </form>
    <br><a href="board_main.php" class="button">목록으로</a>
    <?php if ($_SESSION['user_name'] == $row['id'] || $_SESSION['user_name'] == 'admin'):?>
        <br><a href="board_edit.php?number=<?php echo $row['number'];?>" class="button">수정하기</a>
        <br><a href="board_delete.php?number=<?php echo $row['number']; ?>"
                    class="button" onclick="return confirm('정말 삭제 하시겠습니까?');">삭제하기</a>
    <?php endif;?>
</body>
</html>
