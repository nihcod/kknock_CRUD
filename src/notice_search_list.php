<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['user_name'])) {
    header("Location: index.php?error=로그인이 필요합니다.");
    exit();
}
if (!isset($_GET['order']) || !isset($_GET['type']) || !isset($_GET['query']))
{
    header("Location: notice_list.php?order=desc");
    exit();
}

include "dbconn.php";

$type = $_GET['type'] ?? 'title';
$query = $_GET['query'] ?? '';
$type = trim($type);
$query = trim($query);

$allowed_types = ['title', 'content', 'id']; // DB 필드명 기준
if (!in_array($type, $allowed_types)) {
    $type = 'title';
}

$order = $_GET['order'] ?? 'desc';
if (isset($_GET['order']) && $_GET['order'] === 'asc') {
    $order = 'asc';
}
$final_query = '%' . $query . '%';
$sql = "SELECT * FROM Notice WHERE $type LIKE ? ORDER BY number $order";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $final_query);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>자유게시판 검색 목록</title>
</head>
<body>
    <div class="container board-list-page">
    <div class="btn-group">
            <a href="notice_write.php" class="button">글 쓰기</a>
            <a href="board_main.php" class="button">메인으로</a>
    </div>
    <ul class="board-list">
            <h2>자유게시판</h2>
            
            <select id="array" onchange="location.href = 'notice_search_list.php?order=' + this.value + '&type=' + currentType + '&query=' + currentQuery;">
                <option value="desc" <?= $order === 'desc' ? 'selected' : '' ?>>최신순</option>
                <option value="asc" <?= $order === 'asc' ? 'selected' : '' ?>>오래된순</option>
            </select>
            <script>
                const currentType = "<?= htmlspecialchars($type) ?>";
                const currentQuery = "<?= urlencode($query) ?>";
            </script>
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