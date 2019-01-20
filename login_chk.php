<?php
include_once("data/config.php");
include_once("inc/function.php");
header('Content-type:text/json;charset=utf-8');
$getaction = get("action");
$gotourl = "";
$success = "0";
if($getaction=="login"){
	if(!empty($_SESSION['uid'])){$_SESSION['uid']="";}
	$user_name = post("user_name");
	$user_pass = post_pass("user_pass");
	if(empty($user_name) || empty($user_pass)){
        $error_code = "用户名密码不能为空！";
	}
	else{
		$sql = "SELECT * FROM ".TABLE."user WHERE username = '".$user_name."'";
		$query = mysqli_query($conn,$sql);
		if ($row = mysqli_fetch_array($query)){
			$salt = $row['salt'];
			$password = hash_md5($user_pass,$salt);
			if($row['password']==$password){
				$_SESSION['uid'] = $row['uid'];
				$_SESSION['error_times'] = 0;
				$userinfo = array("userid"=>"$row[uid]","username"=>"$row[username]","useremail"=>"$row[email]","regtime"=>"$row[addtime]","updatetime"=>"$row[utime]");
				$userinfo = encrypt($userinfo, $sys_key);
				setcookie("userinfo", $userinfo, time()+86400);
				$success = "1";
				$error_code = "登录成功！";
				$gotourl = "add.php";
			}else{
				$error_code = "用户名或密码错误！<a href='?action=getpassword'>忘记密码？</a>";
			}
		}else{
			$error_code = "用户名或密码错误！<a href='?action=getpassword'>忘记密码？</a>";
		}
	}
	$data = '{code:"' .$success. '",error_msg:"' .$error_code.'",url:"' .$gotourl.'"}';
    echo json_encode($data);
}elseif($getaction=="register"){	
	$user_name = post("user_name");
	$user_email = post("user_email");
	$user_pass = post_pass("user_pass");
	if(empty($user_name) || empty($user_email) || empty($user_pass)){
        $error_code = "用户名、邮箱、密码不能为空！";
	}elseif(checkemail($user_email) == false){
		$error_code = "邮箱格式不正确";
	}else{
		$sql = "select * from ".TABLE."user where username='$user_name' or email='$user_email'";
		$query = mysqli_query($conn,$sql);
		$attitle = is_array($row = mysqli_fetch_array($query));
		if ($attitle) {
			$error_code = "用户或邮箱已存在！换一个吧！";
		} 
		else {
			$addtime = strtotime("now");
			$salt = md5($user_name.$addtime.$user_pass);
			$user_pass = hash_md5($user_pass,$salt);
			$sql = "insert into ".TABLE."user (username, password, email, addtime, utime, salt) values ('$user_name', '$user_pass', '$user_email', '$addtime', '$addtime', '$salt')";
			$query = mysqli_query($conn,$sql);
			if($query){
				$success = "1";
				$error_code = "注册成功！";
				$gotourl = "login.php";
			}
			else{
				$error_code = "出错啦，写入数据库时出错！";
			}
			$sql = "select * from ".TABLE."user where username='$user_name'";
			$query = mysqli_query($conn,$sql);
			$row = mysqli_fetch_assoc($query);
			$uid = $row['uid'];
			$sql = "insert into ".TABLE."account_class (classname, classtype, ufid) values ('默认收入', '1','".$uid."'),('默认支出', '2','".$uid."')";
			$query = mysqli_query($conn,$sql);
			if($query){
				$error_code =  $error_code."增加默认分类成功！";
			}else{
				$error_code =  $error_code."增加默认分类出错！";
			}
		}
	}
	$data = '{code:"' .$success. '",error_msg:"' .$error_code.'",url:"' .$gotourl.'"}';
    echo json_encode($data);
}elseif($getaction=="getpassword"){
	$user_email = post("user_email");
	if(!empty($_SESSION['error_times']) && $_SESSION['error_times']>=3){
		$error_code = "错误提示太多！";
		$gotourl = "login.php";
	}else{	
		if(empty($user_email)){
			$error_code = "邮箱不能为空！";
		}elseif(checkemail($user_email) == false){
			$error_code = "邮箱格式不正确！";
		}else{			
			$sql = "select * from ".TABLE."user where email='$user_email'";
			$query = mysqli_query($conn,$sql);
			if ($row = mysqli_fetch_array($query)){
				$user_name = $row['username'];
				$user_pass = $row['password'];
				$uid = $row['uid'];
				$getpasstime = strtotime("now");
				$time = date('Y-m-d H:i');
				$token = md5($uid.$user_name.$user_pass);
				$x = md5($user_name.'+'.$user_pass);
				$token = base64_encode($user_name.".".$x);
				$url = SITEURL."reset_userpass.php?token=".$token;
				$state = send_getpass_email($user_email,$user_name,$time,$url);
				if($state==""){
					$error_code = "邮箱设置错误！";
				}else{
					mysqli_query($conn,"update `".TABLE."user` set `utime`='$getpasstime' where uid='$uid'");
					$error_code = "邮件发送成功！";
					$success = "1";
					$gotourl = "login.php";
				}
			}else{
				if(!empty($_SESSION['error_times'])){
					$_SESSION['error_times'] = $_SESSION['error_times'] + 1;
				}else{
					$_SESSION['error_times'] = 1;
				}
				$error_code = "邮箱不存在！";
			}
		}
	}
	$data = '{code:"' .$success. '",error_msg:"' .$error_code.'",url:"' .$gotourl.'"}';
    echo json_encode($data);
}elseif($getaction=="reset"){
	$user_email = post("user_email");
	$user_pass_new = post_pass("user_pass_new");	
	if(empty($user_email)){
        $error_code = "邮箱不能为空！";
	}elseif(checkemail($user_email) == false){
		$error_code = "邮箱格式不正确！";
	}elseif(strlen($user_pass_new)<6 or strlen($user_pass_new)>20){
		$error_code = "密码长度不正确！";
	}else{		
		$sql = "select uid,username from ".TABLE."user where email='$user_email'";
		$query = mysqli_query($conn,$sql);
		if ($row = mysqli_fetch_array($query)){
			$utime = strtotime("now");
			$salt = md5($row['username'].$utime.$user_pass_new);
			$user_pass = hash_md5($user_pass_new,$salt);
			$sql = "update ".TABLE."user set password='$user_pass',salt='$salt' where uid='$row[uid]'";
            $query = mysqli_query($conn,$sql);
			if ($query) {
				$_SESSION['email'] = "";
				$error_code = "重置成功，请使用新密码登录！";
				$success = "1";
				$gotourl = "login.php";
			}
			else{
				$_SESSION['email'] = "";
				$error_code = "重置失败，请重新获取！<a href='?action=getpassword'>忘记密码？</a>";
			}
		}
		else{
			$error_code = "账号错误！";
		}		
	}
	$data = '{code:"' .$success. '",error_msg:"' .$error_code.'",url:"' .$gotourl.'"}';
    echo json_encode($data);
}else{
	$error_code = "非法访问！";
	$data = '{code:"' .$success. '",error_msg:"' .$error_code.'",url:"' .$gotourl.'"}';
    echo json_encode($data);
}
?>