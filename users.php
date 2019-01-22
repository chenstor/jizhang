<?php
include_once("header.php");
?>
<table align="center" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">个人信息</td></tr>
    <tr><td bgcolor="#FFFFFF">
		<div class="record_form" id="user">
			<form id="user_form" name="user_form" method="post" onsubmit="return checkpost(this);">
			<p><i>用 户 名：</i><?php echo $userinfo['username'];?></p>
			<p><i>注册时间：</i><?php echo date("Y-m-d H:i:s",$userinfo['regtime']);?></label></p>
			<p><i>更新时间：</i><?php echo date("Y-m-d H:i:s",$userinfo['updatetime']);?></label></p>
			<p><i>电子邮箱：</i><input type="text" class="w180" name="email" id="email" value="<?php echo $userinfo['useremail'];?>"></p>
			<p><i>旧 密 码：</i><input type="password" class="w180" name="password" id="password" /><span class="red fs12">修改必须填写</span></p>
			<p><i>新 密 码：</i><input type="password" class="w180" name="newpassword" id="newpassword" /><span class="red fs12">密码请填写6-20位</span></p>
			<p class="btn_div">
				<button name="submit" type="submit" id="submit" class="btn btn-primary">更新信息</button>
				<span id="error_show" class="red"></span>
			</p>
			</form>
		</div>
        </td>
    </tr>
</table>

<?php if($userinfo['isadmin']=="1"){?>
<table align="left" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <td bgcolor="#EBEBEB">帐号管理</td>
    </tr>
</table>
<table width="100%" border="0" align="left" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <th align="left" bgcolor="#EBEBEB">帐号</th>
        <th align="left" bgcolor="#EBEBEB">邮箱</th>
        <th align="left" bgcolor="#EBEBEB">注册时间</th>
		<th align="left" bgcolor="#EBEBEB">状态</th>
		<th align="left" bgcolor="#EBEBEB">操作</th>
    </tr>
	<?php 
	$userlist = user_list("uid asc");
	foreach($userlist as $myrow){
		if($myrow['Isallow']=="0"){
			$res = "<span class='green'>正常</span>";
			$btn_show ="<a class=\"btn btn-danger btn-xs\" href=\"javascript:\" onclick=\"changeuser('noallow',$myrow[uid]);\">禁用</a>";
		}else{
			$res = "<span class='red'>禁用</span>";
			$btn_show ="<a class=\"btn btn-success btn-xs\" href=\"javascript:\" onclick=\"changeuser('allow',$myrow[uid]);\">启用</a>";
		}
		if($userid == $myrow['uid']){
			$btn_show ="<a class=\"btn btn-default btn-xs\" href=\"#\">禁用</a>";
		}
	?>
    <tr><td align='left' bgcolor='#FFFFFF'><?php echo $myrow['username'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow['email'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo date("Y-m-d",$myrow['addtime']);?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $res;?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $btn_show;?></td>
	</tr>
	<?php }?>
</table>
<?php }?>

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
			var data = '';
			if(result != ''){
				data = eval("("+result+")");    //将返回的json数据进行解析，并赋给data
			}
			if(data.code == "0"){$(".btn_div > #submit").removeClass("disabled");}
			$('#error_show').html(data.error_msg);
			if(data.url != ""){location.href=data.url;}				
		},
		error : function() {
			$("#error_show").hide();
			console.log(result);
		}
	});
}
function changeuser(type,uid){
	$.ajax({
		type:"get",
		url:"date.php?action=changeuser&m="+type+"&uid="+uid+"", //需要获取的页面内容
		async:true,
		success:function(data){
			alert(data);
			window.location.reload();
		}
	});
}
</script>
<?php include_once("footer.php");?>