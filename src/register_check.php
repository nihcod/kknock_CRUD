<?php
session_start();
include "dbconn.php";

if (isset($_POST['name']) && isset($_POST['email'])
    && isset($_POST['password']) && isset($_POST['copassword'])) {

        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $name = validate($_POST['name']);
        $email = validate($_POST['email']);
        $password = validate($_POST['password']);
        $copassword = validate($_POST['copassword']);

        $name = mysqli_real_escape_string(($conn), $name);
        $email = mysqli_real_escape_string(($conn), $email);
        $password = mysqli_real_escape_string(($conn), $password);
        $copassword = mysqli_real_escape_string(($conn), $copassword);
        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
        $user_data = 'uname='. $name. '&email='. $email;


        if (empty($name)) {
                header("Location: register.php?error=User Name을 입력해주세요.&$user_data");
            exit();
        }else if(empty($password)){
            header("Location: register.php?error=비밀번호를 입력해주세요.&$user_data");
            exit();
        }
        else if(empty($copassword)){
            header("Location: register.php?error=Confirm Password를 입력해주세요.&$user_data");
            exit();
        }

        else if(empty($email)){
            header("Location: register.php?error=이메일 주소를 입력해주세요.&$user_data");
            exit();
        }

        else if($password !== $copassword){
            header("Location: register.php?error=Confirm Password가 일치하지 않습니다.&$user_data");
            exit();
        }
        else if(!preg_match($pattern, $email)){
            header("Location: register.php?error=올바른 이메일 형식이 아닙니다.&$user_data");
            exit();
        }
        else if(preg_match('/admin/i',$email) || preg_match('/admin/i', $name)){
            header("Location: register.php?error=User name 또는 Email에 금지어가 존재합니다.&$user_data");
            exit();
        }

        else{

                // hashing the password
        //$pass = md5($pass);

            $sql = "SELECT * FROM users WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();

                if ($res && $res->num_rows > 0) {
                        header("Location: register.php?error=The username is taken try another&$user_data");
                exit();
                }else {
           $sql2 = "INSERT INTO users(email, password, name) VALUES(?, ?, ?)";
           $stmt2 = $conn->prepare($sql2);
           $stmt2->bind_param("sss", $email, $password, $name);
           $res2 = $stmt2->execute();
           
           if ($res2) {
                 header("Location: index.php?msg=회원 가입이 완료 되었습니다");
                 exit();
           }else {
                header("Location: register.php?error=에러가 발생 했습니다.&$user_data");
                exit();
                }
            }
        }

    }   else{
            header("Location: register.php");
            exit();
        }
?>
