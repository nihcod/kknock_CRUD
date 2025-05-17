<?php 
session_start(); 
include "dbconn.php";

if (isset($_POST['email']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$email = validate($_POST['email']);
	$password = validate($_POST['password']);
	$email = mysqli_real_escape_string(($conn), $email);
    $password = mysqli_real_escape_string(($conn), $password);

	if (empty($email)) {
		header("Location: index.php?error=Email is required");
	    exit();
	}else if(empty($password)){
        header("Location: index.php?error=Password is required");
	    exit();
	}else{
		// hashing the password
        //$pass = md5($pass);

        

		$sql = "SELECT * FROM users WHERE email=? AND password=?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss",$email, $password);
		$stmt->execute();
        $res = $stmt->get_result();

		if ($res && $res->num_rows > 0) {
			$row = $res->fetch_assoc();
            if ($row['email'] === $email && $row['password'] === $password) {
            	$_SESSION['id'] = $row['email'];
            	$_SESSION['user_name'] = $row['name'];
            	//$_SESSION['id'] = $row['id'];
            	header("Location: home.php");
		        exit();
            }else{
				header("Location: index.php?error=계정 또는 암호가 틀렸습니다.");
		        exit();
			}
		}else{
			header("Location: index.php?error= 계정 또는 암호가 틀렸습니다.");
	        exit();
		}
	}
	
}else{
	header("Location: index.php");
	exit();
}