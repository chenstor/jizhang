<?php
header('Content-type:text/json;charset=utf-8');
include("data/config.php");
include("inc/function.php");
loginchk($userid);
$gotourl = "";
$success = "0";
$getaction = get("action");
if($getaction=="getclassify"){	
	header('Content-type:text/html;charset=utf-8');
	$classtype = get("classtype");
	$classid = get("classid");
	$sql = "select * from ".TABLE."account_class where userid='$userid' and classtype='$classtype'";
    $query = mysqli_query($conn,$sql);
    while($row = mysqli_fetch_array($query)){
		if($row["classid"] <> $classid){
			echo "<option value='".$row["classid"]."'>".$row["classname"]."</option>";
		}
	}
}
if($getaction=="addbank"){
	$bankname = post("bankname");
	$bankaccount = post("bankaccount");
	$balancemoney = post("balancemoney","0");
	if(empty($bankname) or empty($bankaccount)){
		$error_code = "缺少参数！";
	}elseif(!is_numeric($balancemoney)){
		$error_code = "金额非法！";
	}else{
		$a = db_record_num("bank", " where bankname='$bankname' and userid='$userid'", "bankid");
		if($a){
			$error_code = "该名称已存在！";
		}else{			
			$sql = "insert into ".TABLE."bank (bankname, bankaccount, balancemoney, userid) values ('$bankname', '$bankaccount', '$balancemoney', '$userid')";
			$query = mysqli_query($conn,$sql);
			if($query){
				$success = "1";
				$error_code = "保存成功！";
				$gotourl = "bank.php";						
			}else{
				$error_code = "保存失败！";
			}
		}
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="modifybank"){
	$bankname = post("bankname");
	$bankaccount = post("bankaccount");
	$bankid = post("bankid");
	$balancemoney = post("balancemoney","0");
	if(empty($bankname) or empty($bankaccount) or empty($bankid)){
		$error_code = "缺少参数！";
	}else{
		$sql = "update ".TABLE."bank set bankname='".$bankname."',bankaccount='".$bankaccount."' where bankid=".$bankid;
		$result = mysqli_query($conn,$sql);
		if ($result) {
			$success = "1";
			$error_code = "保存成功！";
			$gotourl = "bank.php";
		} else {
			$error_code = "保存失败！";
		}	
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="deletebank"){
	header('Content-type:text/html;charset=utf-8');
	$bankid = get("bankid");
	if(empty($bankid) || !is_numeric($bankid)){
		$error_code = "缺少参数或参数非法！";
	}else{
		$a = db_record_num("account", "where bankid='$bankid' and userid='$userid'", "acid");
		if($a){
			$error_code = "有关联数据，不能删除！";
		}else{
			$sql = db_del('bank','bankid',$bankid);
			if($sql){
				$error_code = "删除成功！";
			}else{
				$error_code = "删除失败！";
			}			
		}
	}				
	echo $error_code;
}
if($getaction=="addrecord"){
	$classid = post("classid");
	$money = post("money");
	$zhifu = post("zhifu");
	$remark = post("remark");
	$bankid = post("bankid");
	$proid = post("proid");
	$addtime = strtotime(post("time"));
	if(empty($classid) or empty($money) or empty($zhifu) or empty($proid)){
		$error_code = "缺少参数！";
	}elseif(!is_numeric($money)){
		$error_code = "金额非法！";
	}else{
		$sql = "insert into ".TABLE."account (acmoney, acclassid, actime, acremark, userid, zhifu, bankid, proid) values ('$money', '$classid', '$addtime', '$remark', '$userid', '$zhifu', '$bankid', '$proid')";
		$query = mysqli_query($conn,$sql);
		if($query){
			if($bankid>0){money_int_out($bankid,$money,$zhifu);}
			$success = "1";
			$error_code = "保存成功！";
			if($zhifu=="1"){
				$gotourl = "add.php?action=income";
			}else{
				$gotourl = "add.php?action=pay";
			}
			setcookie("add_itlu_classid_".$userid, $classid, time()+86400*3);
			setcookie("add_itlu_bankid_".$userid, $bankid, time()+86400*3);
			setcookie("add_itlu_proid_".$userid, $proid, time()+86400*3);
		}else{
			$error_code = "保存失败！";
		}		
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="saverecord"){
	$id = post("edit-id");
	$money = post("edit-money");
	$old_money = post("old-money");
	$remark = post("edit-remark");
	$proid = post("edit-proid");
	$old_bankid = post("old-bank-id");
	$new_bankid = post("edit-bankid");
	$zhifu = post("edit-zhifu");
	$addtime = strtotime(post("edit-time"));
	if(empty($id) or empty($money) or empty($proid) or empty($new_bankid)){
		$error_code = "缺少参数！";
	}elseif(!is_numeric($money)){
		$error_code = "金额非法！";
	}else{
		$sql = "update ".TABLE."account set acmoney='".$money."',acremark='".$remark."',actime='".$addtime."',bankid='".$new_bankid."',proid='".$proid."' where acid='".$id."' and userid='".$userid."'";
		$result = mysqli_query($conn,$sql);
		if($result){
			if($zhifu==2){//支出
				if($old_bankid==$new_bankid && $old_bankid>0){money_int_out($old_bankid,$old_money,"1");money_int_out($old_bankid,$money,"2");}
				if($old_bankid<>$new_bankid && $old_bankid>0){money_int_out($old_bankid,$money,"1");}
				if($old_bankid<>$new_bankid && $new_bankid>0){money_int_out($new_bankid,$money,"2");}
			}else{//收入
				if($old_bankid==$new_bankid && $old_bankid>0){money_int_out($old_bankid,$old_money,"2");money_int_out($old_bankid,$money,"1");}
				if($old_bankid<>$new_bankid && $old_bankid>0){money_int_out($old_bankid,$money,"2");}
				if($old_bankid<>$new_bankid && $new_bankid>0){money_int_out($new_bankid,$money,"1");}
			}
			$success = "1";
			$error_code = "保存成功！";
		} else {
			$error_code = "保存失败！";
		}		
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="deleterecord"){
	header('Content-type:text/html;charset=utf-8');
	$get_id = get("id");
	if(empty($get_id) || !is_numeric($get_id)){
		$error_code = "缺少参数或参数非法！";
	}else{
		$sql = "select bankid,zhifu,acmoney from ".TABLE."account where acid='$get_id' and userid='$userid'";
		$query = mysqli_query($conn,$sql);
		if($row = mysqli_fetch_array($query)){
			if($row["bankid"]>0){
				$zhifu = "2";
				if($row["zhifu"]=="2"){$zhifu = "1";}
				money_int_out($row["bankid"],$row["acmoney"],$zhifu);
			}
		}
		$del_sql = "delete from ".TABLE."account where acid='$get_id' and userid='$userid'";
		if(mysqli_query($conn,$del_sql)){
			$error_code = "删除成功！";
		}else{
			$error_code = "删除失败！";
		}
	}				
	echo $error_code;
}
if($getaction=="deleterecordAll"){
	if(isset($_POST["del_id"]) && $_POST["del_id"] != ""){
		$del_id = implode(",",$_POST['del_id']);
		$sql = "delete from ".TABLE."account where userid='$userid' and acid in ($del_id)";
		if (mysqli_query($conn,$sql)){
			$success = "1";
			$error_code = "删除成功！";
		}else{
			$error_code = "删除失败！";
		}	
	}else{
		$error_code = "参数不正确！";
	}			
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="changeclassify"){
	$classid = post("classid");
	$newclassid = post("newclassid");
	if(empty($newclassid) or $newclassid=="0" or empty($classid)){
		$error_code = "缺少参数或者未选择目标分类！";
	}else{
		$sql = "update ".TABLE."account set acclassid='$newclassid' where acclassid='$classid' and userid='$userid'";
		$query = mysqli_query($conn,$sql);
		if ($query) {
			$success = "1";
			$error_code = "更改分类成功！";
			$gotourl = "classify.php";
		} else {
			$error_code = "保存失败！";
		}		
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="deleteclassify"){
	header('Content-type:text/html;charset=utf-8');
	$classid = get("classid");
	if(empty($classid)){
		$error_code = "缺少参数！";
	}else{
		$a = db_record_num("account", "where acclassid='$classid' and userid='$userid'", "acid");
		if($a){
			$error_code = "在此分类下有账目，请将账目转移到其他分类";
		}else{
			$sql = db_del('account_class','classid',$classid);
			if($sql){
				$error_code = "删除成功！";
			}else{
				$error_code = "删除失败！";
			}			
		}
	}
	echo $error_code;
}
if($getaction=="addclassify"){
	$classname = post("classname");
	$classtype = post("classtype");
	if(empty($classname)){
		$error_code = "分类名称不能为空！";
	}elseif(strlen($classname)>18){
		$error_code = "分类名称不能大于6个字！";
	}else{
		$a = db_record_num("account_class", "where classname='$classname' and classtype='$classtype' and userid='$userid'", "classid");
		if($a){
			$error_code = "该名称的分类已经存在！";
		}else{
			$sql = "insert into ".TABLE."account_class (classname, classtype, userid) values ('$classname', '$classtype',$userid)";
			$query = mysqli_query($conn,$sql);
			if ($query) {
				$error_code = "保存成功！";
				$success = "1";
				$gotourl = "classify.php";
			} else {
				$error_code = "保存失败！";
			}
		}		
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="modifyclassify"){
	$classname = post("classname");
	if(empty($classname)){
		$error_code = "分类名称不能为空！";
	}elseif(strlen($classname)>18){
		$error_code = "分类名称不能大于6个字！";
	}else{
		$a = db_record_num("account_class", "where classname='$classname' and classid<>'$_POST[classid]' and userid='$userid'", "classid");
		if($a){
			$error_code = "该名称的分类已经存在！";
		}else{
			$sql = "UPDATE ".TABLE."account_class set classname='$classname' , classtype=$_POST[classtype] where userid='$userid' and classid=".$_POST["classid"];
			$query = mysqli_query($conn,$sql);
			if($query){
				$error_code = "保存成功！";
				$success = "1";
				$gotourl = "classify.php";
			}else{
				$error_code = "保存失败！";
			}
		}		
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="addprogram"){
	$classname = post("proname");
	$orderid = post("orderid");
	if(empty($classname)){
		$error_code = "名称不能为空！";
	}elseif(strlen($classname)>30){
		$error_code = "名称不能大于10个字！";
	}elseif(!is_numeric($orderid)){
		$error_code = "排序ID只能输入数字！";
	}else{
		$a = db_record_num("program", "where proname='$classname' and userid='$userid'", "proid");
		if($a){
			$error_code = "该名称的项目已经存在！";
		}else{
			$sql = "insert into ".TABLE."program (proname, orderid, userid) values ('$classname', '$orderid', $userid)";
			$query = mysqli_query($conn,$sql);
			if($query){
				$error_code = "保存成功！";
				$success = "1";
				$gotourl = "program.php";
			}else{
				$error_code = "保存失败！";
			}
		}		
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="modifyprogram"){
	$classname = post("proname");
	$orderid = post("orderid");
	$proid = post("proid");
	if(empty($classname)){
		$error_code = "名称不能为空！";
	}elseif(strlen($classname)>30){
		$error_code = "名称不能大于10个字！";
	}elseif(!is_numeric($orderid)){
		$error_code = "排序ID只能输入数字！";
	}else{
		$a = db_record_num("program", " where proname='$classname' and proid<>'$proid' and userid='$userid'", "proid");
		if($a){
			$error_code = "该名称已经存在！";
		}else{
			$sql = "UPDATE ".TABLE."program set proname='$classname',orderid='$orderid',userid='$userid' where proid=".$proid;
			$query = mysqli_query($conn,$sql);
			if($query){
				$error_code = "保存成功！";
				$success = "1";
				$gotourl = "program.php";
			}else{
				$error_code = "保存失败！";
			}
		}	
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="deleteprogram"){
	header('Content-type:text/html;charset=utf-8');
	$classid = get("proid");
	if(empty($classid)){
		$error_code = "缺少参数！";
	}else{
		$a = db_record_num("bank", " where bankaccount='$classid' and userid='$userid'", "bankid");
		if ($a) {
			$error_code = "在此项目下有员工，不能删除项目！";
		} else {
			$sql = db_del('program','proid',$classid);
			if ($sql){
				$error_code = "删除成功！";
			}else{
				$error_code = "删除失败！";
			}			
		}
	}
	echo $error_code;
}
if($getaction=="adduser"){
	$username = post("username");
	$isadmin = post("isadmin");
	$pro_id = post("pro_id");
	$role_id = post("role_id");
	$email = post("email");
	$password = post("password");
	if(empty($username) or empty($role_id) or empty($email) or empty($password)){
		$error_code = "参数不完整！";
	}elseif(empty($isadmin) and empty($pro_id)){
		$error_code = "普通用户必须关联项目！";
	}elseif(strlen($password)<6){
		$error_code = "密码不能小于6个字符！";
	}elseif((!empty($email)) && (checkemail($email) == false)){
		$error_code = "邮箱格式错误！";
	}elseif(strlen($username) > 15){
		$error_code = "用户名长度不能大于15";
	}elseif(strlen($email) > 30){
		$error_code = "邮箱长度不能大于30";
	}else{
		$a = db_record_num("user", " where username='$username' or email='$email'", "uid");
		if($a){
			$error_code = "账号或邮箱已存在！";
		}else{
			$addtime = strtotime("now");
			$salt = md5($username.$addtime.$password);
			$password = hash_md5($password,$salt);
			$sql = "insert into ".TABLE."user (username, password, email, addtime, utime, salt, pro_id, role_id, Isadmin) values ('$username', '$password', '$email', '$addtime', '$addtime', '$salt', '$pro_id', '$role_id', '$isadmin')";
			$query = mysqli_query($conn,$sql);
			if($query){
				$success = "1";
				$error_code = "添加成功！";
				$gotourl = "system.php?action=user";
			}else{
				$error_code = "添加失败！";
			}
		}		
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="modifyuser"){
	$uid = post("userid");
	$username = post("username");
	$isadmin = post("isadmin");
	$pro_id = post("pro_id");
	$role_id = post("role_id");
	$email = post("email");
	$updatetime = strtotime("now");
	if(empty($uid) or empty($username) or empty($role_id) or empty($email)){
		$error_code = "参数不完整！";
	}elseif(empty($isadmin) and empty($pro_id)){
		$error_code = "普通用户必须关联项目！";
	}elseif((!empty($email)) && (checkemail($email) == false)){
		$error_code = "邮箱格式错误！";
	}elseif(strlen($username) > 15){
		$error_code = "用户名长度不能大于15";
	}elseif(strlen($email) > 30){
		$error_code = "邮箱长度不能大于30";
	}else{
		$sql_db = "(uid != $uid and username='$username') or (uid != $uid and email='$email')";
		$a = db_record_num("user", " where $sql_db", "uid");
		if($a){
			$error_code = "账号或邮箱已存在！";
		}else{
			$sql = "update ".TABLE."user set username='".$username."',email='".$email."',Isadmin='".$isadmin."',pro_id='".$pro_id."',role_id='".$role_id."',utime='".$updatetime."' where uid=".$uid;
			$result = mysqli_query($conn,$sql);
			if ($result) {
				$success = "1";
				$error_code = "保存成功！";
				$gotourl = "system.php?action=user";
			} else {
				$error_code = "保存失败！";
			}
		}
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="changeuser"){
	$type = get("m");
	$uid = get("uid");
	if(empty($type) or empty($uid)){
		$error_code = "参数不完整！";
	}else{
		if($type=="noallow"){
			$u_sql = "Isallow=1";
			$itlu_word = "禁用";
		}else{
			$u_sql = "Isallow=0";
			$itlu_word = "启用";
		}
		$update_sql = "update ".TABLE."user set ".$u_sql." where uid='$uid'";
        $update_query = mysqli_query($conn,$update_sql);
		if($update_query){
			$error_code = $itlu_word."成功！";
		}else{
			$error_code = "操作失败！";
		}
		$success = "1";
		$gotourl = "system.php?action=user";
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="deleteuser"){
	header('Content-type:text/html;charset=utf-8');
	$uid = get("uid");
	if(empty($uid) || !is_numeric($uid)){
		$error_code = "缺少参数或参数非法！";
	}else{
		$sql = db_del('user','uid',$uid);
		if($sql){
			$error_code = "删除成功！";
		}else{
			$error_code = "删除失败！";
		}			
	}				
	echo $error_code;
}
if($getaction=="changepassword"){
	$uid = get("uid");
	$name = get("name");
	if(empty($uid) or empty($name)){
		$error_code = "参数不完整！";
	}else{
		$newpassword = get_password(8);
		$update_time = strtotime("now");
		$salt = md5($name.$update_time.$newpassword);
		$user_pass = hash_md5($newpassword,$salt);
		$u_sql = "password='$user_pass',salt='$salt'";
		$update_sql = "update ".TABLE."user set ".$u_sql." where uid='$uid'";
        $update_query = mysqli_query($conn,$update_sql);
		if($update_query){
			$success = "1";
			$error_code = "操作成功！密码重置为：".$newpassword;
			$gotourl = "system.php?action=user";
		}else{
			$error_code = "出错啦，写入数据库时出错！";
		}
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="updateuser"){
	$password = post_pass("password");
	$newpassword = post_pass("newpassword");
	$email = post("email");
	if(empty($password)){
		$error_code = "密码不能为空！";
	}elseif(strlen($password)<6){
		$error_code = "密码不能小于6个字符！";
	}elseif((!empty($email)) && (checkemail($email) == false)){
		$error_code = "邮箱格式错误！";
	}elseif(!empty($newpassword) && strlen($newpassword)<6){
		$error_code = "新密码不能小于6个字符！";
	}else{
		$sql = "SELECT * FROM ".TABLE."user where uid='$userid'";
		$query = mysqli_query($conn,$sql);
		$row = mysqli_fetch_array($query);
		$db_salt = $row["salt"];
		if($row["password"] == hash_md5($password,$db_salt)){			
			$update_time = strtotime("now");
			$u_sql = "utime='$update_time'";
			if(!empty($email)){
				$u_sql = $u_sql.",email='$email'";
			}
			if(!empty($newpassword)){				
				$salt = md5($row["username"].$update_time.$newpassword);
				$user_pass = hash_md5($newpassword,$salt);
				$u_sql = $u_sql.",password='$user_pass',salt='$salt'";
			}
			$update_sql = "update ".TABLE."user set ".$u_sql." where uid='$userid'";
            $update_query = mysqli_query($conn,$update_sql);
			if($update_query){
				$success = "1";
				$userinfo_update = array("userid"=>"$userid","username"=>"$row[username]","useremail"=>"$email","regtime"=>"$row[addtime]","updatetime"=>"$update_time","isadmin"=>"$row[Isadmin]","pro_id"=>"$row[pro_id]","role_id"=>"$row[role_id]");
				$userinfo = AES::encrypt($userinfo_update, $sys_key);
				setcookie("userinfo", $userinfo, time()+86400);
				$error_code = "信息修改成功！";
				$gotourl = "system.php?action=proinfo";
			}else{
				$error_code = "出错啦，写入数据库时出错！";
			}
		}else{
			$error_code = "旧密码错误！";
		}		
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="export"){
	header('Content-type:text/html;charset=utf-8');
	$classid = get('classid','all');
	$starttime = get('starttime');
	$endtime = get('endtime');
	$startmoney = get('startmoney');
	$endmoney = get('endmoney');
	$remark = get('remark');
	$bankid = get('bankid');
	$sql = "select a.zhifu,a.acmoney,a.actime,a.acremark,a.bankid,a.proid,b.classname from ".TABLE."account as a INNER JOIN ".TABLE."account_class as b ON b.classid=a.acclassid";
	if($classid == "all"){
		
	}elseif($classid == "pay"){
		$sql .= " and a.zhifu = 2 ";
	}elseif($classid == "income"){
		$sql .= " and a.zhifu = 1 ";
	}else{
		$sql .= " and a.acclassid = '".$classid."' ";
	}
	if(!empty($bankid)){
		$sql .= " and bankid = '".$bankid."' ";
	}
	if(!empty($starttime)){
		$sql .= " and actime >= '".strtotime($starttime." 00:00:00")."' ";
	}
	if(!empty($endtime)){
		$sql .= " and actime <= '".strtotime($endtime." 23:59:59")."' ";
	}
	if(!empty($startmoney)){
		$sql .= " and acmoney >= '".$startmoney."' ";
	}
	if(!empty($endmoney)){
		$sql .= " and acmoney <= '".$endmoney."' ";
	}
	if($userinfo['isadmin']!=1 and $userinfo['pro_id']!=0){
		$sql .= " and proid = '".$userinfo['pro_id']."' ";
	}
	$result = mysqli_query($conn,$sql);
    $str = "收支,分类,项目,账户,金额,时间,备注\n";
    $str = iconv('utf-8','gb2312',$str);
    while ($row = mysqli_fetch_array($result)) {
        $classname = iconv('utf-8','gb2312',$row['classname']);
        if ($row['zhifu'] == 1) {
            $shouzhi = iconv('utf-8','gb2312',"收入");
        } else {
            $shouzhi = iconv('utf-8','gb2312',"支出");
        }
        $money = $row['acmoney'];
        $time = date("Y-m-d H:i",$row['actime']);		
		if($row['acremark']<>""){
			$remark = iconv('utf-8','gbk',$row['acremark']);
		}else{
			$remark = "";
		}
		$proname = programname($row['proid'],$userid,"默认项目");
		$proname = iconv('utf-8','gb2312',$proname);
		$bankname = bankname($row['bankid'],$userid,"默认账户");
		$bankname = iconv('utf-8','gb2312',$bankname);
        $str .= $shouzhi.",".$classname.",".$proname.",".$bankname.",".$money.",".$time.",".$remark."\n";
    }
    $filename = date('YmdHis').'.csv';
    export_csv($filename,$str);
}
if($getaction=='updatesystem'){
	$filepath = "data/config.php";
	$info = vita_get_url_content($filepath);
	foreach($_POST as $k=>$v){
        $info=preg_replace("/define\(\"{$k}\",\".*?\"\)/","define(\"{$k}\",\"{$v}\")",$info);
    }
    file_put_contents($filepath,$info);
	$success = "1";
	$error_code = "信息修改成功！";
	$gotourl = "system.php?action=sys";
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=='updatesmtp'){
	$filepath = "inc/smtp_config.php";
	$info = vita_get_url_content($filepath);
	foreach($_POST as $k=>$v){
        $info=preg_replace("/define\(\"{$k}\",\".*?\"\)/","define(\"{$k}\",\"{$v}\")",$info);
    }
    file_put_contents($filepath,$info);
	$success = "1";
	$error_code = "信息修改成功！";
	$gotourl = "system.php?action=smtp";
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="addmenu"){
	$m_name = post("m_name");
	$m_type = post("m_type");
	$m_f_id = post("m_f_id");
	$m_url = post("m_url");
	$orderid = post("orderid","9999");
	$addtime = strtotime("now");
	if(empty($m_name) or empty($m_type) or empty($m_f_id) or empty($m_url)){
		$error_code = "缺少参数！";
	}else{	
		$sql = "insert into ".TABLE."sys_menu (m_f_id, m_name, m_type, m_url, orderid, addtime) values ('$m_f_id', '$m_name', '$m_type', '$m_url', '$orderid', '$addtime')";
		$query = mysqli_query($conn,$sql);
		if($query){
			$success = "1";
			$error_code = "保存成功！";
			$gotourl = "system.php?action=menu";						
		}else{
			$error_code = "保存失败！";
		}
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="modifymenu"){
	$m_id = post("m_id");
	$m_name = post("m_name");
	$m_type = post("m_type");
	$m_f_id = post("m_f_id");
	$m_url = post("m_url");
	$orderid = post("orderid","9999");
	$updatetime = strtotime("now");
	if(empty($m_id) or empty($m_name) or empty($m_type) or empty($m_url)){
		$error_code = "缺少参数！";
	}else{
		$sql = "update ".TABLE."sys_menu set m_f_id='".$m_f_id."',m_name='".$m_name."',m_type='".$m_type."',m_url='".$m_url."',orderid='".$orderid."',updatetime='".$updatetime."' where m_id=".$m_id;
		$result = mysqli_query($conn,$sql);
		if ($result) {
			$success = "1";
			$error_code = "保存成功！";
			$gotourl = "system.php?action=menu";
		} else {
			$error_code = "保存失败！";
		}	
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="deletemenu"){
	header('Content-type:text/html;charset=utf-8');
	$m_id = get("m_id");
	if(empty($m_id) || !is_numeric($m_id)){
		$error_code = "缺少参数或参数非法！";
	}else{
		$sql = db_del('sys_menu','m_id',$m_id);
		if($sql){
			$error_code = "删除成功！";
		}else{
			$error_code = "删除失败！";
		}			
	}				
	echo $error_code;
}
if($getaction=="addrole"){
	$role_name = post("role_name");
	$role_description = post("role_description");
	$addtime = strtotime("now");
	if(empty($role_name) or $role_name=='系统内置' or $role_name=='超级管理员'){
		$error_code = "缺少参数或名称不符合！";
	}else{	
		$sql = "insert into ".TABLE."sys_role (role_name, role_description, addtime) values ('$role_name', '$role_description', '$addtime')";
		$query = mysqli_query($conn,$sql);
		if($query){
			$success = "1";
			$error_code = "保存成功！";
			$gotourl = "system.php?action=role";						
		}else{
			$error_code = "保存失败！";
		}
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="modifyrole"){
	$role_id = post("role_id");
	$role_name = post("role_name");
	$role_description = post("role_description");
	$updatetime = strtotime("now");
	if(empty($role_id) or empty($role_name)){
		$error_code = "缺少参数！";
	}else{
		$sql = "update ".TABLE."sys_role set role_name='".$role_name."',role_description='".$role_description."',updatetime='".$updatetime."' where role_id=".$role_id;
		$result = mysqli_query($conn,$sql);
		if ($result) {
			$success = "1";
			$error_code = "保存成功！";
			$gotourl = "system.php?action=role";
		} else {
			$error_code = "保存失败！";
		}	
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
if($getaction=="deleterole"){
	header('Content-type:text/html;charset=utf-8');
	$role_id = get("role_id");
	if(empty($role_id) || !is_numeric($role_id)){
		$error_code = "缺少参数或参数非法！";
	}else{
		$sql = db_del('sys_role','role_id',$role_id);
		if ($sql){
			$error_code = "删除成功！";
		}else{
			$error_code = "删除失败！";
		}			
	}				
	echo $error_code;
}
if($getaction=="modifyrolelist"){
	$role_id = post("role_id");
	if(!empty($_POST["rolelist_id"])){
		$rolelist_id = $_POST["rolelist_id"];
		$rolelist_id = implode(",",$rolelist_id);
	}else{
		$rolelist_id = "";
	}
	$updatetime = strtotime("now");
	if(empty($role_id) or empty($rolelist_id)){
		$error_code = "缺少参数！";
	}else{
		$sql = "update ".TABLE."sys_role set role_menu_id='".$rolelist_id."',updatetime='".$updatetime."' where role_id=".$role_id;
		$result = mysqli_query($conn,$sql);
		if ($result) {
			$success = "1";
			$error_code = "保存成功！";
			$gotourl = "system.php?action=role";
		} else {
			$error_code = "保存失败！";
		}	
	}
	$data = '{"code":"' .$success. '","error_msg":"' .$error_code.'","url":"' .$gotourl.'"}';
    echo json_encode($data);
}
?>