<?php
session_start();
include "dbconn.php";
function validate($data){
    $data = trim($data);
    return $data;
}

if (isset($_POST['title']) && isset($_POST['content']))
{
    $title = validate($_POST['title']);
    $content = validate($_POST['content']);
    $id = $_SESSION['user_name'];
    $file_name=NULL;
    $file_path=NULL;
}
if (empty($_POST['title']) || empty($_POST['content'])) {
    echo "<script>alert('제목 또는 내용이 비어있습니다.'); history.back()</script>";
    exit();
}
if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error']==0){
    $upload_dir = "uploads/";
    $org_name = basename($_FILES['upload_file']['name']);
    $ext = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);
    //ext: 확장자
    $saved_name = uniqid() . '.' . $ext;//고유값(파일명중복방지)

    $allow_ext = ['jpg', 'jpeg', 'png', 'gif', 'txt'];
    if (!in_array(strtolower($ext), $allow_ext))
    {
        die("허용된 파일 확장자가 아닙니다.");
    }

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
    $targetpath = $upload_dir . $saved_name;
    if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $targetpath))
    {
        $file_name = $org_name;
        $file_path = $targetpath;
    }
}


$sql = "INSERT INTO Board(title, content, id, attime, fixattime, file_name, file_path)
    VALUES (?, ?, ?, NOW(), NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $content, $id, $file_name, $file_path);
    $stmt->execute();
    echo "<script>window.location.replace('board_main.php');</script>";
    exit();

?>
