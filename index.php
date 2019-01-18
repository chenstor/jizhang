<?php
//判断是否已安装
if(!is_file("./install/lock") && is_file("./install/index.php")){
	@header("location:install/index.php");
}
session_start();
if (isset($_SESSION['uid'])<>"") {
    echo "<script language='javascript' type='text/javascript'>window.location.href='add.php'</script>";
} else{
    echo "<script language='javascript' type='text/javascript'>window.location.href='login.php'</script>";
}
?>