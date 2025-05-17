<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {

 ?>
<!DOCTYPE html>
<html>
<head>
        <title>HOME</title>
        <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
     <h1>Hello, <?php echo $_SESSION['user_name']; ?></h1><br>
     <h1><a href="board_main.php" class="button">게시판</a><br></h1>
     <a href="logout.php" class="button">Logout</a>
</body>
</html>

<?php
}else{
     header("Location: index.php?error=세션 만료! 로그인을 다시 해주세요.");
     exit();
}
 ?>
 