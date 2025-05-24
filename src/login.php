<?php 
session_start(); 
include "dbconn.php";

if (isset($_POST['email']) && isset($_POST['password'])) {

	function validate($data){
    //    $data = trim($data);
	   return $data;
	}
	function basic_filter($input) {
		// low 필터링
		return preg_match("/(--|#|\/\*|\*\/|\bor\b|\band\b|\bunion\b)/i", $input);
	}
	$email = validate($_POST['email']);
	$password = validate($_POST['password']);
	
	if (empty($email)) {
		header("Location: index.php?error=Email is required");
	    exit();
	}else if(empty($password)){
        header("Location: index.php?error=Password is required");
	    exit();
	}else{
		// hashing the password
        //$pass = md5($pass);

        
		// vuln here ~~ //
	
	if (empty($email)){
		header("Location: index.php?error=이메일을 입력해주세요.");
		exit();
	}
	else if (empty($password)){
		header("Location: index.php?error=비밀번호를 입력해주세요.");
		exit();
	}
	else if (basic_filter($email) || basic_filter($password)){
		header("Location: index.php?error=bypass filtering!");
		exit();
	}
	else{
        $sql = "SELECT * FROM users WHERE email='{$email}' and password='{$password}'";
		$res = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($res);

		if ($row) {
            	$_SESSION['id'] = $row['email'];
            	$_SESSION['user_name'] = $row['name'];
            	//$_SESSION['id'] = $row['id'];
            	header("Location: home.php");
		        exit();
		}else{
			header("Location: index.php?error= 계정 또는 암호가 틀렸습니다.");
	        exit();
		}
	}
}
	
}else{
	header("Location: index.php");
	exit();
}