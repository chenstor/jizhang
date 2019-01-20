<?php
include_once("header.php");
?>
<table align="center" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">账号管理</td></tr>
    <tr><td bgcolor="#FFFFFF">
		<div class="record_form" id="user">
			<form id="user_form" name="user_form" method="post" onsubmit="return checkpost(this);">
			<p><i>用 户 名：</i><?php echo $userinfo['username'];?></p>
			<p><i>注册时间：</i><?php echo date("Y-m-d H:i:s",$userinfo['regtime']);?></label></p>
			<p><i>更新时间：</i><?php echo date("Y-m-d H:i:s",$userinfo['updatetime']);?></label></p>
			<p><i>电子邮箱：</i><input type="text" class="w180" name="email" id="email" value="<?php echo $userinfo['useremail'];?>"></p>
			<p><i>旧 密 码：</i><input type="password" class="w180" name="password" id="password" /><span class="red fs12">修改必须填写</span></p>
			<p><i>新 密 码：</i><input type="password" class="w180" name="newpassword" id="newpassword" /><span class="red fs12">密码请填写6-20位</span></p>
			<!--<p><i>授权密码：</i><input type="password" class="w180" name="cpassword" id="cpassword" /></p>-->
			<p class="btn_div">
				<button name="submit" type="submit" id="submit" class="btn btn-primary">更新信息</button>
				<span id="error_show" class="red"></span>
			</p>
			</form>
		</div>
        </td>
    </tr>
</table>

<table align="center" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">清空全部数据</td></tr>
    <tr><td bgcolor="#FFFFFF">
		<form action="" method="post" name="submitdel">
        <p>验证密码：<input type="password" name="mimayanzheng" id="mimayanzheng" size="18" maxlength="15" />（操作前请备份导出）</p>
        <p><input name="shangchushuju" type="submit" value="清除全部数据" class="btn btn-danger" /></p>
        </form>
    </td></tr>
</table>

<?php
if(isset($_POST['shangchushuju'])){
	alertword("这么危险的动作，怎么可能提供？？？");
	$mmyanzheng = md5($_POST['mimayanzheng']);
	if ($mmyanzheng == $row['password']) {
		echo "<meta http-equiv=refresh content='2; url=delete.php?uid=".$_SESSION['uid'].")'>";
		echo "<font color='green'>已全部删除成功！</font>";
	} else {
		echo "<font color='red'>密码错误！</font>";
	}
}
?>

<script language="javascript">
function checkpost(form){
	if(form.password.value == ""){
		alert("密码不能为空！");
		form.password.focus();
		return false;
	}
	if((form.newpassword.value != "") && (form.newpassword.value.length <6)){
		alert("新密码必须6位以上！");
		form.newpassword.focus();
		return false;
	}
	$(".btn_div > #submit").addClass("disabled");
	updateUserInfo();
	return false;
}
function updateUserInfo(){
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "date.php?action=updateuser",//url
		data: $('#user_form').serialize(),
		success: function (result) {
			$("#error_show").show();
			//console.log(result);//打印服务端返回的数据(调试用)
			var data = '';
			if(result != ''){
				data = eval("("+result+")");    //将返回的json数据进行解析，并赋给data
			}
			if(data.code == "0"){$(".btn_div > #submit").removeClass("disabled");}
			$('#error_show').html(data.error_msg);    //在#text中输出
			if(data.url != ""){location.href=data.url;}				
		},
		error : function() {
			$("#error_show").hide();
			console.log(result);
			//alert("保存异常！");
		}
	});
}
</script>
<?php include_once("footer.php");?>