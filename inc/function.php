<?php
if(!defined("DB_HOST")){die('非法访问！');}

$version = 'V3.1(20200414)';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); 
if(!$conn){
	die('数据库打开失败！');
}else{
	mysqli_query($conn,'SET NAMES utf8');
}
//使用系统统一的$userid
if(isset($_SESSION['uid'])){
	$userid = $_SESSION['uid'];
}else{
	$userid = "";
}

include("content.php");
include("safe.php");
if(substr(PHP_VERSION,0,1)>='7'){
	include("aes7.php");
}else{
	include("aes5.php");
}
include("Smtp.class.php");
include("smtp_config.php");
if(!empty($_COOKIE["userinfo"])){
	$userinfo = AES::decrypt($_COOKIE["userinfo"], $sys_key);
	$sys_role_menu = db_one_key("sys_role","where role_id=$userinfo[role_id]","role_menu_id");
}
if(empty($_COOKIE["userinfo"]) && $userid>0){
	$_SESSION['uid'] = "";
	gotourl(SiteURL."login.php");
}

$today = date("Y-m-d");
$yesterday = date("Y-m-d",strtotime("-1 day"));
$this_year = date("Y");
$this_month_firstday = date('Y-m-01', strtotime(date("Y-m-d")));
$this_year_firstday = date("Y",time())."-01-01";
$last_month_start = date("Y-m-d",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
$last_month_end = date("Y-m-d",mktime(23,59,59,date("m") ,0,date("Y")));
$last_year_start = date("Y-m-d", strtotime("-1 year"));
$last_year_end = date("Y-12-31", strtotime("-1 year"));

$last_week_data = getWeekMyActionAndEnd(strtotime("-7 day"),WeekDayStart);
$last_week_start = $last_week_data["week_start"];
$last_week_end = $last_week_data["week_end"];

$this_week_data = getWeekMyActionAndEnd("",WeekDayStart);
$this_week_start = $this_week_data["week_start"];
$this_week_end = $this_week_data["week_end"];

function getWeekMyActionAndEnd($time = '', $first = 1){
  if (!$time) $time = time();
  $sdefaultDate = date("Y-m-d", $time);
  $w = date('w', strtotime($sdefaultDate));
  $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
  $week_end = date('Y-m-d', strtotime("$week_start +6 days"));
  return array("week_start" => $week_start, "week_end" => $week_end);
}
function get_month_days($days){
	$days = date('t', strtotime($days));
	return $days;
}
function getMonthNum( $date1, $date2){
	$date1_stamp=strtotime($date1);
    $date2_stamp=strtotime($date2);
    list($date_1['y'],$date_1['m'])=explode("-",date('Y-m',$date1_stamp));
    list($date_2['y'],$date_2['m'])=explode("-",date('Y-m',$date2_stamp));
    return abs($date_1['y']-$date_2['y'])*12 +$date_2['m']-$date_1['m'];
}
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

function toDate($str, $flag = false, $default = 1){
	if(empty($str) or is_null($str) or $str==""){
		if($default==1){
			$date_str = date("Y-m-d");
		}else{
			$date_str = "";
		}		
	}else{
		if($flag){
		  $date_str = date('Y', $str).'-'.date('m', $str).'-'.date('d', $str).' '.date('H', $str).':'.date('i', $str);
		} else {
		  $date_str = date('Y', $str).'-'.date('m', $str).'-'.date('d', $str);
		}
	}
    return $date_str;
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
function table($dbname){
	$dbname = TABLE.$dbname;	
	return $dbname;
}
function add_zero($tid){
	$tid = str_pad($tid,2,"0",STR_PAD_LEFT);
	return $tid;
}
function loginchk($uid){
	if($uid=="" || empty($uid) || $uid==null){
		msgbox("您无权限访问该页,正在跳转登入页面...","","login.php");
	}
}
function show_tab($type){
	global $userinfo;
	if($type==1){
		echo "<div class=\"table\"><div class=\"table-header-group\"><ul class=\"table-row\"><li>分类</li><li>项目</li><li>账户</li><li>金额</li><li>时间</li><li>备注</li><li>操作</li></ul></div>\n";
		echo "<div class=\"table-row-group\">\n";
	}elseif($type==2){
		echo "<div class=\"table\"><div class=\"table-header-group\"><ul class=\"table-row\"><li>分类</li><li>项目</li><li>账户</li><li>金额</li><li>时间</li><li>备注</li><li>操作</li>";
		if(sys_role_check($userinfo['isadmin'],$userinfo['role_id'],"11")){
			echo "<li class=\"noshow\"><input type=\"checkbox\" name=\"check_all\" id=\"check_all\" /><input type='submit' id='del_submit' name='del_submit' value='删除' class='btn btn-danger btn-xs' /></li>\n";
		}
		echo "</ul></div><div class=\"table-row-group\">\n";
	}elseif($type==3){
		echo "</div></div>\n";
	}elseif($type==4){
		echo "<div class=\"table\"><div class=\"table-header-group\"><ul class=\"table-row\"><li>账户名称</li><li>账号/卡号</li><li>账户余额</li><li>操作</li></ul></div>\n";
		echo "<div class=\"table-row-group\">\n";
	}elseif($type==5){
		echo "<div class=\"table\"><div class=\"table-header-group\"><ul class=\"table-row\"><li>类别名称</li><li>收/支</li><li>操作</li></ul></div>\n";
		echo "<div class=\"table-row-group\">\n";
	}elseif($type==6){
		echo "<div class=\"table\"><div class=\"table-header-group\"><ul class=\"table-row\"><li>分类</li><li>账户</li><li>金额</li><li>时间</li><li>备注</li><li>记录人</li></ul></div>\n";
		echo "<div class=\"table-row-group\">\n";
	}elseif($type==7){
		echo "<div class=\"table\"><div class=\"table-header-group\"><ul class=\"table-row\"><li>项目名称</li><li>项目收入</li><li>项目支出</li><li>排序</li><li>操作</li></ul></div>\n";
		echo "<div class=\"table-row-group\">\n";
	}	
}

function admin_type($type){
	switch($type){
		case 0:
			$admin_type = "普通用户";
			break;
		case 1:
			$admin_type = "系统管理员";
			break;
		case 2:
			$admin_type = "项目管理员";
			break;
	}
	return $admin_type;
}

function showlogin($tid){
	switch($tid){		
		case "invite":
			$showlogin = "<label for=\"invite\">邀请码<br><input type=\"text\" name=\"invite\" id=\"invite\" class=\"input\" value='' size=\"20\"></label>";
			break;
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
function show_menu_cur($file){
	$url = $_SERVER['PHP_SELF']; 
	$filename = substr($url, strrpos($url , '/')+1);
	if($file == $filename){
		$show = " class='cur'";
	}else{
		$show = "";
	}
	echo $show;
}
function get_now_filename(){
	$url = $_SERVER['PHP_SELF']; 
	$filename = substr($url, strrpos($url , '/')+1);
	return $filename;
}
function show_sysmenu_cur($act,$get_act=""){
	if($act == $get_act){
		$show = "class='red on'";
	}elseif($act=='proinfo' and $get_act===""){
		$show = "class='red on'";
	}else{
		$show = "";
	}
	echo $show;
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

function get_password($length = 8){
	$str = substr(md5(time()), 0, $length);
	return $str;
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
function vita_get_url_content($url){
	if(function_exists('file_get_contents')) {
		$file_contents = @file_get_contents($url);
	}else{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
	}
	return $file_contents;
}
function getPageHtml($page, $pages, $url, $thiscount=20, $allcount){
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
	$_pageHtml .= '<li><a href="#">'.$thiscount.'/'.$allcount.'条&nbsp;&nbsp;共'.$pages.'页</a></li>';
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
?>