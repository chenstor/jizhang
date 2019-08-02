<?php
include_once("header.php");
?>
<table align="center" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">个人信息</td></tr>
    <tr><td bgcolor="#FFFFFF">
		<div class="record_form" id="user">
			<form id="user_form" name="user_form" method="post" onsubmit="return checkpost('user',this);">
			<p><i>用 户 名：</i><?php echo $userinfo['username'];?></p>
			<p><i>注册时间：</i><?php echo date("Y-m-d H:i:s",$userinfo['regtime']);?></label></p>
			<p><i>更新时间：</i><?php echo date("Y-m-d H:i:s",$userinfo['updatetime']);?></label></p>
			<p><i>电子邮箱：</i><input type="text" class="w180" name="email" id="email" value="<?php echo $userinfo['useremail'];?>"></p>
			<p><i>旧 密 码：</i><input type="password" class="w180" name="password" id="password" /><span class="red fs12">修改必须填写</span></p>
			<p><i>新 密 码：</i><input type="password" class="w180" name="newpassword" id="newpassword" /><span class="red fs12">密码请填写6-20位</span></p>
			<p class="btn_div">
				<button name="submit" type="submit" id="submit_user" class="btn btn-primary">更新信息</button>
				<span id="user_error_show" class="red"></span>
			</p>
			</form>
		</div>
        </td>
    </tr>
</table>

<?php if($userinfo['isadmin']=="1"){?>
<table align="center" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">系统管理</td></tr>
    <tr><td bgcolor="#FFFFFF">
		<div class="record_form" id="system">
			<form id="system_form" name="system_form" method="post" onsubmit="return checkpost('system',this);">
			<?php
			$keyinfo = [
				"siteName"=>"站点名称",
				"SiteURL"=>"站点网址",
				"Multiuser"=>"多用户",
				"Invite"=>"邀请注册",
				"ViewAllData"=>"管理数据",
				"DB_HOST"=>"数据库地址",
				"DB_USER"=>"数据库用户",
				"DB_PASS"=>"数据库密码",
				"DB_NAME"=>"数据库名称",
				"DB_PORT"=>"数据库端口",
				"TABLE"=>"数据库前缀"
			];
			$info = vita_get_url_content("data/config.php");			
			preg_match_all("/define\(\"(.*?)\",\"(.*?)\"\)/",$info,$arr);
			//var_dump($arr);
			foreach($arr[1] as $k=>$v){
				//echo $v;
			if($v=='DB_HOST' or $v=='DB_USER' or $v=='DB_PASS' or $v=='DB_NAME' or $v=='DB_PORT' or $v=='TABLE'){continue;}
			if($v=='Multiuser'){
			?>
			<p><i><?php echo $keyinfo[$v];?>：</i><label class="red"><input name="Multiuser" type="radio" value="1" <?php if($arr[2][$k]=='1'){echo "checked";}?> />开启</label><label class="ml10"><input name="Multiuser" type="radio" value="0" <?php if($arr[2][$k]=='0'){echo "checked";}?> />关闭</label></p>
			<?php
			}
			elseif($v=='Invite'){
			?>
			<p><i><?php echo $keyinfo[$v];?>：</i><label class="red"><input name="Invite" type="radio" value="1" <?php if($arr[2][$k]=='1'){echo "checked";}?> />开启</label><label class="ml10"><input name="Invite" type="radio" value="0" <?php if($arr[2][$k]=='0'){echo "checked";}?> />关闭</label> <u>(开启多用户，该配置才有效)</u></p>
			<?php 
			}
			elseif($v=='ViewAllData'){
			?>
			<p><i><?php echo $keyinfo[$v];?>：</i><label class="red"><input name="ViewAllData" type="radio" value="1" <?php if($arr[2][$k]=='1'){echo "checked";}?> />开启</label><label class="ml10"><input name="ViewAllData" type="radio" value="0" <?php if($arr[2][$k]=='0'){echo "checked";}?> />关闭</label> <u>(开启后管理员可查看所有记账记录)</u></p>
			<?php 
			}
			else{?>
			<p><i><?php echo $keyinfo[$v];?>：</i><input type="text" class="w180" name="<?php echo $v;?>" id="<?php echo $v;?>" value="<?php echo $arr[2][$k];?>"></p>
			<?php 
			}
			}?>
			<p class="btn_div">
				<button name="submit" type="submit" id="submit_system" class="btn btn-primary">更新信息</button>
				<span id="system_error_show" class="red"></span>
			</p>
			</form>
		</div>
        </td>
    </tr>
</table>

<table align="center" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">SMTP设置</td></tr>
    <tr><td bgcolor="#FFFFFF">
		<div class="record_form" id="smtp">
			<form id="smtp_form" name="smtp_form" method="post" onsubmit="return checkpost('smtp',this);">
			<?php
			$keyinfo_smtp = [
				"c_protocol"=>"是否SSL",
				"c_serverport"=>"端口",
				"c_smtp"=>"SMTP",
				"c_email"=>"邮箱",
				"c_emailpass"=>"密码"
			];
			$info = vita_get_url_content("inc/smtp_config.php");
			preg_match_all("/define\(\"(.*?)\",\"(.*?)\"\)/",$info,$arr);
			foreach($arr[1] as $k=>$v){
				if($v=='c_protocol'){?>
				<p><i><?php echo $keyinfo_smtp[$v];?>：</i><label class="red"><input name="c_protocol" type="radio" value="1" <?php if($arr[2][$k]=='1'){echo "checked";}?> />使用SSL</label><label class="ml10"><input name="c_protocol" type="radio" value="0" <?php if($arr[2][$k]=='0'){echo "checked";}?> />默认</label></p>	
				<?php
				}else{
			?>
				<p><i><?php echo $keyinfo_smtp[$v];?>：</i><input type="text" class="w180" name="<?php echo $v;?>" id="<?php echo $v;?>" value="<?php echo $arr[2][$k];?>"></p>
			<?php }
			}?>
			<p class="btn_div">
				<button name="submit" type="submit" id="submit_smtp" class="btn btn-primary">更新信息</button>
				<span id="smtp_error_show" class="red"></span>
			</p>
			</form>
		</div>
        </td>
    </tr>
</table>

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
	$userlist = db_list("user","where uid>0","order by uid asc");
	foreach($userlist as $myrow){
		if($myrow['Isallow']=="0"){
			$res = "<span class='green'>正常</span>";
			$btn_show ="<a class=\"btn btn-danger btn-xs\" href=\"javascript:\" onclick=\"changeuser('noallow',$myrow[uid],'0');\">禁用</a> <a class=\"btn btn-primary btn-xs\" href=\"javascript:\" onclick=\"changeuser('changelogin',$myrow[uid],'$myrow[username]');\">扮演</a>";
		}else{
			$res = "<span class='red'>禁用</span>";
			$btn_show ="<a class=\"btn btn-success btn-xs\" href=\"javascript:\" onclick=\"changeuser('allow',$myrow[uid],'0');\">启用</a> <a class=\"btn btn-primary btn-xs\" href=\"javascript:\" onclick=\"changeuser('changelogin',$myrow[uid],'$myrow[username]');\">扮演</a>";
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
function checkpost(type,form){
	if(type=="user"){
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
	}
	if(type=="system"){
		if(form.siteName.value == ""){
			alert("站点名称不能为空！");
			form.siteName.focus();
			return false;
		}
		if(form.SiteURL.value == ""){
			alert("站点网址不能为空！");
			form.SiteURL.focus();
			return false;
		}
		if((form.SiteURL.value != "") && (!chkUrlHttp(form.SiteURL.value))){
			alert("网址必须以http://或者https://开头！");
			form.SiteURL.focus();
			return false;
		}
	}
	if(type=="smtp"){
		if(form.c_smtp.value == ""){
			alert("SMTP地址不能为空！");
			form.c_smtp.focus();
			return false;
		}
		if(form.c_email.value == ""){
			alert("邮箱不能为空！");
			form.c_email.focus();
			return false;
		}
		if(form.c_emailpass.value == ""){
			alert("密码不能为空！");
			form.c_emailpass.focus();
			return false;
		}
	}
	$(".btn_div > #submit_"+type).addClass("disabled");
	updateUserInfo(type);
	return false;
}
function updateUserInfo(type){
	var geturl = "date.php?action=update"+type;
	var formname = "#"+type+"_form";
	var error_id = "#"+type+"_error_show";
	$.ajax({
		type: "POST",
		dataType: "json",
		url: geturl,
		data: $(formname).serialize(),
		success: function (result) {
			$(error_id).show();
			var data = '';
			if(result != ''){
				data = eval("("+result+")");
			}
			if(data.code == "0"){$(".btn_div > #submit_"+type).removeClass("disabled");}
			$(error_id).html(data.error_msg);
			if(data.url != ""){location.href=data.url;}				
		},
		error : function() {
			$(error_id).hide();
			console.log(result);
		}
	});
}
function changeuser(type,uid,name){
	if(type=="changelogin"){
		geturl = "date.php?action=changelogin&admin=<?php echo $userinfo['userid'];?>&name="+name+"&uid="+uid+"";
	}else{
		geturl = "date.php?action=changeuser&m="+type+"&uid="+uid+"";
	}
	$.ajax({
		type:"get",
		url: geturl, //需要获取的页面内容
		async:true,
		success: function(result) {
			var data = '';
			if(result != ''){
				data = eval("("+result+")");
			}
			alert(data.error_msg);
			if(data.url != ""){
				location.href = data.url;
			}else{
				window.location.reload();
			}		
		},
		error : function() {
		}
	});
}
</script>
<?php include_once("footer.php");?>