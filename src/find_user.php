<?php
include "dbconn.php";
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['user_name']))
{
    echo "<script>alert('로그인 후 열람 가능합니다.'); location.href='board_main.php'</script>";
    exit();
}
if (isset($_GET['username']) && $_GET['username'] !== '') {
    $username = mysqli_real_escape_string($conn, $_GET['username']);
    $sql = "SELECT name, email, attime FROM users WHERE name='$username'";
}
else if (isset($_GET['email']) && $_GET['email'] !== '') {
    $useremail = mysqli_real_escape_string($conn, $_GET['email']);
    $sql = "SELECT name, email, attime FROM users WHERE email='$useremail'";
}
else {
    $sql = "SELECT name, email, attime FROM users WHERE name='{$_SESSION['user_name']}'";
}

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>유저 정보 검색 창</title>
</head>
<body>
    <h2>유저 정보</h2>
    <form action="find_user.php" method="GET">
    <select id="searchType" onchange="changeInputName()">
        <option value="username">username</option>
        <option value="email">email</option>
    </select>
    <input type="text" id="searchInput" name="username" placeholder="유저의 이름 혹은 이메일">
    <input type="submit" value="검색">
    </form>

    <script>
    function changeInputName() {
        const select = document.getElementById("searchType");
        const input = document.getElementById("searchInput");

        const selected = select.value; // 'username' 또는 'email'
        input.name = selected;

        // 선택에 따라 placeholder도 같이 바꾸고 싶다면:
        input.placeholder = selected === "username" ? "유저 이름을 입력하세요" : "이메일 주소를 입력하세요";
    }
    </script>
    <h3>username</h3><hr>
    <?php echo "{$row['name']}"; ?>
    <h3>email</h3><hr>
    <?php echo "{$row['email']}"; ?>
    <h3>가입 한 날짜</h3><hr>
    <?php echo "{$row['attime']}" ?>
</body>
</html>