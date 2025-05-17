<?php
session_start();
include "dbconn.php";
if (!isset($_SESSION["id"]) || !isset($_SESSION["user_name"])){
    header("Location: index.php?error=잘못된 접근입니다. 로그인을 다시 해주세요.");
    exit();
}
function filter($data){
    $data = trim($data);
    return $data;
}
$name = $_SESSION['user_name'];

if (isset($_POST['board_number']) && isset($_POST['content']))
{
    $content = $_POST['content'];
    $board_number = $_POST['board_number'];
    $content = filter($content);
    $board_number = filter($board_number);


    $sql = "INSERT INTO comment (board_number, id, content, attime, fixattime) 
            VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $board_number, $name, $content);
    $stmt->execute();

    header("Location: board_view.php?number=$board_number");
    exit();
}
else{
    header("Location: board_main.php?error=잘못된 접근");
    exit();
}

?>