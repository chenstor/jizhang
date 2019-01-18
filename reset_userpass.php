<?php
include_once("data/config.php");
include_once("inc/function.php");

$get_token = get("token");//获取参数

if(empty($get_token)){
	msgbox("参数非法！","","login.php");
}
$_SESSION['email'] = "";
$_array = explode('.',base64_decode($get_token));
if((empty($_array['0'])) or (empty($_array['1']))){
	msgbox("参数非法，无法识别！","","login.php");
}
$sql = "select password,email,utime from ".TABLE."user where username = '".trim($_array['0'])."'";
$query = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($query);
$checkCode = md5($_array['0'].'+'.$row['password']);
if($_array['1'] === $checkCode){
	if(strtotime("now")-$row['utime'] > 20*60){
		msgbox("该链接已经失效，请重新获取！","","login.php");
	}
	else{
		$_SESSION['email']=$row['email'];
		gotourl("login.php?action=reset");
	}
	
}else{
	msgbox("参数非法，可能被篡改！","","login.php");
}
?>