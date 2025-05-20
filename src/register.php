<!DOCTYPE html>
<html>
<head>
        <title>회원가입</title>
        <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
     <form action="register_check.php" method="post">
        <h2>회원 가입</h2>
        <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

          <?php if (isset($_GET['success'])) { ?>
               <p class="success"><?php echo $_GET['success']; ?></p>
          <?php } ?>

          <label>Name</label>
          <?php if (isset($_GET['name'])) { ?>
               <input type="text" 
                      name="name" 
                      placeholder="Name"
                      value="<?php echo $_GET['name']; ?>"><br>
          <?php }else{ ?>
               <input type="text" 
                      name="name" 
                      placeholder="Name"><br>
          <?php }?>

          <label>Email</label>
          <?php if (isset($_GET['uname'])) { ?>
               <input type="text" 
                      name="email" 
                      placeholder="Email"
                      value="<?php echo $_GET['email']; ?>"><br>
          <?php }else{ ?>
               <input type="text" 
                      name="email" 
                      placeholder="Email"><br>
          <?php }?>

        <label>Password</label>
        <input type="password" 
                 name="password" 
                 placeholder="Password"><br>

          <label>Confirm Password</label>
          <input type="password" 
                 name="copassword" 
                 placeholder="Confirm Password"><br>

        <button type="submit" class="button">가입하기</button>
          <a href="index.php" class="button">이미 가입 되어 있으신가요?</a>
     </form>
</body>
</html>