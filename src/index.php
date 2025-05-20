<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])){
        header("Location: home.php");
        exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
        <title>로그인</title>
</head>
<body>
     <form action="login.php" method="post">
        <h2>LOGIN</h2>
        <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>
        <?php if (isset($_GET['msg'])) { ?>
                <p class="success"><?php echo htmlspecialchars($_GET['msg']); ?></p>
        <?php } ?>
        <label>Email</label>
        <input type="text" name="email" placeholder="Email"><br>

        <label>Password</label>
        <input type="password" name="password" placeholder="Password"><br>

        <button type="submit" class="button">로그인</button>
          <a href="register.php" class="button">회원 가입</a>
     </form>
</body>
</html>