<?php
session_start();
include "dbconn.php";
function validate($data){
       $data = trim($data);
	   return $data;
	}
if (empty($_SESSION['user_name']) || empty($_SESSION['id']))
{
    header("Location: index.php?error=로그인을 해주세요.");
    exit();
}
if (empty($_POST['title']) || empty($_POST['content']))
{
    echo "<script>alert('제목 또는 내용이 비어있습니다.'); history.back();</script>";
    exit();
}
if (isset($_POST['delete_file']))
{   //파일 언링크
    $sql_get = "SELECT file_path FROM Board WHERE number=?";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("i", $_POST['number']);
    $stmt_get->execute();
    $result_get = $stmt_get->get_result();
    $row_get = $result_get->fetch_assoc();

    if ($row_get && !empty($row_get['file_path']) && file_exists($row_get['file_path'])) {
        unlink($row_get['file_path']);
    }
    //db에서도 삭제
    $sql3 = "UPDATE Board SET file_name=NULL, file_path=NULL WHERE number=?";
    $stmt3=$conn->prepare($sql3);
    $stmt3->bind_param("i", $_POST['number']);
    $stmt3->execute();
}
if (isset($_POST['number'], $_POST['title'], $_POST['content'])){
    $number = $_POST['number'];
    $title = validate($_POST['title']);
    $content = validate($_POST['content']);

    $sql = "UPDATE Board SET title=?, content=?, fixattime=NOW() WHERE number=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $number);
    $stmt->execute();

    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error']==0)
    {
        // ㅅㅂ 파일업로드 개빡이네
        $sql_old = "SELECT file_path FROM Board WHERE number=?";
        $stmt_old = $conn->prepare($sql_old);
        $stmt_old->bind_param("i", $number);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        $old_row = $result_old->fetch_assoc();

        if ($old_row && !empty($old_row['file_path']) && file_exists($old_row['file_path'])) {
            unlink($old_row['file_path']);
        }
        

        $upload_dir = "uploads/";
        $allow_ext = ['jpg', 'jpeg', 'png', 'gif', 'txt'];
        $org_name = basename($_FILES['upload_file']['name']);
        $ext = pathinfo($_FILES['upload_file']['name'], PATHINFO_EXTENSION);
        //ext: 확장자
        $allow_mime = ['image/jpeg', 'image/png', 'image/gif', 'text/plain'];
        //실제 파일 유형 
        $tmp_name = $_FILES['upload_file']['tmp_name'];
        $mime = mime_content_type($tmp_name);
        
        if (!in_array(strtolower($ext), $allow_ext) || !in_array($mime, $allow_mime)) {
            die("허용되지 않은 파일입니다.");
        }
        $saved_name = uniqid() . '.' . $ext;//고유값(파일명중복방지)
        $targetpath = $upload_dir . $saved_name;
        if (move_uploaded_file($_FILES['upload_file']['tmp_name'], $targetpath))
        {
            $file_name = $org_name;
            $file_path = $targetpath;
            $sql4 = "UPDATE Board SET file_name=?, file_path=? WHERE number=?";
            $stmt4 = $conn->prepare($sql4);
            $stmt4->bind_param("ssi", $file_name, $file_path, $number);
            $stmt4->execute();
        }
    }
    header("Location: board_view.php?number={$number}");
    exit();
}
?>