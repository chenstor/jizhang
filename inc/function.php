<?php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); 
if(!$conn){die('数据库打开失败！');}

if(PHP_VERSION>=7){
	define('PHP7', true);
}else{
	define('PHP7', false);
}

$today = date("Y-m-d");
$yesterday = date("Y-m-d",strtotime("-1 day"));
$this_year = date("Y");
$this_month_firstday = date('Y-m-01', strtotime(date("Y-m-d")));
$this_year_firstday = date("Y",time())."-01-01";
$last_week_start = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y")));
$last_week_end = date("Y-m-d",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));
$last_month_start = date("Y-m-d",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
$last_month_end = date("Y-m-d",mktime(23,59,59,date("m") ,0,date("Y")));
$last_year_start = date("Y-m-d", strtotime("-1 year"));
$last_year_end = date("Y-12-31", strtotime("-1 year"));
function get_week_day($type=1){
	$date=new DateTime();
	if($type==1){
		$date->modify('this week');
		$get_week_day=$date->format('Y-m-d');
	}else{
		$date->modify('this week +6 days');
		$get_week_day=$date->format('Y-m-d');
	}
	return $get_week_day;	
}
function get($kw,$default=""){
	if(isset($_GET[$kw])){
		$get =  fliter_script(stripslashes(trim($_GET[$kw])));
	}else{
		$get =  $default;
	}
	return $get;
}
function post($kw,$default=""){
	if(isset($_POST[$kw])){
		$post =  fliter_script(stripslashes(trim($_POST[$kw])));
	}else{
		$post =  $default;
	}
	return $post;
}
function post_pass($kw,$default=""){
	if(isset($_POST[$kw])){
		$post =  fliter_escape(trim($_POST[$kw]));
	}else{
		$post =  $default;
	}
	return $post;
}
function hash_md5($password,$salt){
	$password=md5($password).$salt;
	$password=md5($password);
	return $password;
}
//使用系统统一的$userid
if(isset($_SESSION['uid'])){
	$userid = $_SESSION['uid'];
}else{
	$userid = "";
}
function loginchk($uid){
	if($uid=="" || empty($uid) || $uid==null){
		msgbox("您无权限访问该页,正在跳转登入页面...","","login.php");
	}
}
function user_mktime($onlinetime) {
    $new_time = mktime();
    if (($new_time - $onlinetime) > '900') {
        session_destroy();
        echo "登陆超时";
        exit ();
    } else {
        $_SESSION['times'] = mktime();
    }
}
function show_tab($type){
	if($type==1){
		echo "<table width='100%' border='0' align='left' cellpadding='5' cellspacing='1' bgcolor='#B3B3B3' class='table table-striped table-bordered'>";
	}elseif($type==2){
		echo "<tr><th bgcolor='#EBEBEB'>分类</th><th bgcolor='#EBEBEB'>收支</th><th bgcolor='#EBEBEB'>金额</th><th bgcolor='#EBEBEB'>时间</th><th bgcolor='#EBEBEB'>备注</th><th bgcolor='#EBEBEB' class='noshow'>操作</th></tr>";
	}elseif($type==3){
		echo "</table>";
	}elseif($type==4){
		echo "<tr><th bgcolor='#EBEBEB'>分类</th><th bgcolor='#EBEBEB'>收支</th><th bgcolor='#EBEBEB'>金额</th><th bgcolor='#EBEBEB'>时间</th><th bgcolor='#EBEBEB'>备注</th><th bgcolor='#EBEBEB'>操作</th><th class='noshow' bgcolor='#EBEBEB'><input type=\"checkbox\" name=\"check_all\" id=\"check_all\" /> <input type='submit' id='del_submit' name='del_submit' value='删除' class='btn btn-danger btn-xs' /></th></tr>";
	}	
}
function showlogin($tid){
	switch ($tid) {
		case "username":
			$showlogin = "<label for=\"user_name\">用户名<br><input type=\"text\" name=\"user_name\" id=\"user_name\" class=\"input\" value='' size=\"20\"></label>";
			break;
		case "email":
			$showlogin = "<label for=\"user_email\">邮箱<br><input type=\"text\" name=\"user_email\" id=\"user_email\" class=\"input\" value='' size=\"20\"></label>";
			break;
		case "password":
			$showlogin = "<label for=\"user_pass\">密码<br><input type=\"password\" name=\"user_pass\" id=\"user_pass\" class=\"input\" value='' size=\"20\"></label>";
			break;
		case "email_session":
			$showlogin = "<label for=\"user_email\">邮箱<br><input type=\"text\" name=\"user_email\" id=\"user_email\" class=\"input form-control\" value='".$_SESSION['email']."' readonly size=\"20\"></label>";
			break;
		case "newpassword":
			$showlogin = "<label for=\"user_pass_new\">新密码(6-20位)<br><input type=\"password\" name=\"user_pass_new\" id=\"user_pass_new\" class=\"input\" value='' size=\"20\"></label>";
			break;
	}
	$showlogin = "<p>".$showlogin."</p>";
	return $showlogin;
	
}
function msgbox($content,$title="",$url){
	echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />";
	echo "<div style=\"width:350px;margin:50px auto;border:1px solid #ddd;font-size:14px;\"><div style=\"width:100%;border-bottom:1px solid #ddd;height:32px;line-height:32px;text-align:center;background:#efefef;font-weight:bold;\">";
	if($title==""){
		echo "错误提示";
	}else{
		echo $title;
	}	
	echo "</div><div style=\"padding:15px 20px;\">";
	echo $content;
	echo "</div></div>";
	if($url != ""){
		echo "<meta http-equiv='refresh' content='1; url=".$url."' />";
	}
	exit();
}
function clearsession($uid){
	session_unset($uid);
	session_destroy();
}
function gotourl($url){
	echo "<script>window.location.href='".$url."';</script>";
	exit();
}
function alertword($word){
	echo "<script>alert('".$word."');history.go(-1);</script>";
	exit();
}
function alertgourl($word,$url){
	echo "<script>alert('".$word."');location.href='".$url."';</script>";
	exit();
}
function checkemail($email){
	if($email == ""){
		$result = false;
	}else{
		$regex = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
		$result = preg_match($regex,$email);
	}	
	return $result;
}
function showmonth($month){
	switch ($month) {
		case 1:
			$showmonth = "一";
			break;
		case 2:
			$showmonth = "二";
			break;
		case 3:
			$showmonth = "三";
			break;
		case 4:
			$showmonth = "四";
			break;
		case 5:
			$showmonth = "五";
			break;
		case 6:
			$showmonth = "六";
			break;
		case 7:
			$showmonth = "七";
			break;
		case 8:
			$showmonth = "八";
			break;
		case 9:
			$showmonth = "九";
			break;
		case 10:
			$showmonth = "十";
			break;
		case 11:
			$showmonth = "十一";
			break;
		case 12:
			$showmonth = "十二";
			break;
	}
	return $showmonth;
}
function input_csv($handle) {
    $out = array ();
    $n = 0;
    while ($data = fgetcsv($handle, 10000)) {
        $num = count($data);
        for ($i = 0; $i < $num; $i++) {
            $out[$n][$i] = $data[$i];
        }
        $n++;
    }
    return $out;
}
function export_csv($filename,$data) {
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}
function getPageHtml($page, $pages, $url){
	$_pageNum = 4;
	$page = $page<1?1:$page;
	$page = $page > $pages ? $pages : $page;
	$pages = $pages < $page ? $page : $pages;
	$_start = $page - floor($_pageNum/2);
	$_start = $_start<1 ? 1 : $_start;
	$_end = $page + floor($_pageNum/2);
	$_end = $_end>$pages? $pages : $_end;
	$_curPageNum = $_end-$_start+1;
	if($_curPageNum<$_pageNum && $_start>1){  
		$_start = $_start - ($_pageNum-$_curPageNum);
		$_start = $_start<1 ? 1 : $_start;
		$_curPageNum = $_end-$_start+1;
	}
	if($_curPageNum<$_pageNum && $_end<$pages){ 
		$_end = $_end + ($_pageNum-$_curPageNum);
		$_end = $_end>$pages? $pages : $_end;
	}
	$_pageHtml = '<ul class="pagination">';
	if($page>1){
		$_pageHtml .= '<li><a title="上一页" href="'.$url.'page='.($page-1).'">上一页</a></li>';
	}
	for ($i = $_start; $i <= $_end; $i++) {
		if($i == $page){
			$_pageHtml .= '<li class="active"><span class="current">'.$i.'</span></li>';
		}else{
			$_pageHtml .= '<li class="p"><a href="'.$url.'page='.$i.'">'.$i.'</a></li>';
		}
	}
	if($page<$_end){
		$_pageHtml .= '<li><a title="下一页" href="'.$url.'page='.($page+1).'">下一页</a></li>';
	}
	$_pageHtml .= '</ul>';
	echo $_pageHtml;
}
function isMobile(){ 
    if(isset($_SERVER['HTTP_X_WAP_PROFILE'])){return true;} 
    if(isset($_SERVER['HTTP_VIA'])){return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;} 
    if(isset ($_SERVER['HTTP_USER_AGENT'])){$clientkeywords = array ('nokia',
            'sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'); 
        if(preg_match("/(".implode('|', $clientkeywords).")/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
            return true;
        } 
    }
    if(isset($_SERVER['HTTP_ACCEPT'])){
        if((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
            return true;
        } 
    } 
    return false;
}

include_once("content.php");
include_once("safe.php");
if(PHP7){
	include_once("aes7.php");
}else{
	include_once("aes5.php");
}
include_once("smtp_config.php");
if(!empty($_COOKIE["userinfo"])){
	$userinfo = AES::decrypt($_COOKIE["userinfo"], $sys_key);
}
?>