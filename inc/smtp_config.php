<?php
// 邮箱配置
define("c_protocol","1");//1为使用ssl
define("c_smtp","");
define("c_serverport","");
define("c_email","");
define("c_emailpass","");

function send_getpass_email($user_email,$user_name,$time,$url){
	if(c_protocol=='1'){
		$smtpserver = 'ssl://'.c_smtp;
	}else{
		$smtpserver = c_smtp;
	}
	$smtpserverport = c_serverport;
	$smtpusermail = c_email;
	$smtpemailto = $user_email;
	$smtpuser = c_email;
	$smtppass = c_emailpass;
	$mailtitle = "您的密码找回信(重要)";
	$mailcontent = $user_name."：<br />您在".$time."提交了找回密码请求，注意该链接有效期为：20分钟<Br>请点击下面的链接，按流程进行密码重设。<br /><a href='".$url."' target='_blank'>".$url."</a><br />如果上面的链接无法点击，您也可以复制链接，粘贴到您浏览器的地址栏内，然后按“回车”打开重置密码页面。<br /><br />本程序由<a href='https://itlu.org/'>itlu.org</a>提供。";
	$mailtype = "HTML";
	$smtp = new Smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
	$smtp->debug = false;
	$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
	return $state;
}
?>