<?php
include_once("header.php");
?>
<table align="left" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <td bgcolor="#EBEBEB" class="add_th">
		<div class="tab-title">
			<a <?php echo show_sysmenu_cur('proinfo',get('action'));?> href="javascript:" id="proinfo">个人信息</a>
			<a <?php echo show_sysmenu_cur('sys',get('action'));?> href="javascript:" id="sys">系统参数</a>			
			<a <?php echo show_sysmenu_cur('user',get('action'));?> href="javascript:" id="user">用户管理</a>
			<a <?php echo show_sysmenu_cur('role',get('action'));?> href="javascript:" id="role">角色管理</a>			
			<a <?php echo show_sysmenu_cur('smtp',get('action'));?> href="javascript:" id="smtp">SMTP设置</a>
			<a <?php echo show_sysmenu_cur('menu',get('action'));?> href="javascript:" id="menu">系统菜单</a>
			<a <?php echo show_sysmenu_cur('export',get('action'));?> href="javascript:" id="export">数据导出</a>
		</div>
		</td>
    </tr>
</table>

<?php if(get('action')=="" or get('action')=='proinfo'){?>
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
<?php }?>

<?php if((get('action')=='sys') and ($userinfo['isadmin']=="1")){?>
<table align="center" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">系统参数</td></tr>
    <tr><td bgcolor="#FFFFFF">
		<div class="record_form" id="system">
			<form id="system_form" name="system_form" method="post" onsubmit="return checkpost('system',this);">
			<?php
			$keyinfo = [
				"SiteName"=>"站点名称",
				"SiteURL"=>"站点网址",
				"Multiuser"=>"多用户",
				"Invite"=>"邀请注册",
				"WeekDayStart"=>"每周开始",
				"DB_HOST"=>"数据库地址",
				"DB_USER"=>"数据库用户",
				"DB_PASS"=>"数据库密码",
				"DB_NAME"=>"数据库名称",
				"DB_PORT"=>"数据库端口",
				"TABLE"=>"数据库前缀"
			];
			$info = vita_get_url_content("data/config.php");			
			preg_match_all("/define\(\"(.*?)\",\"(.*?)\"\)/",$info,$arr);
			foreach($arr[1] as $k=>$v){
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
			elseif($v=='WeekDayStart'){
			?>
			<p><i><?php echo $keyinfo[$v];?>：</i><label><input name="WeekDayStart" type="radio" value="1" <?php if($arr[2][$k]=='1'){echo "checked";}?> />周一</label><label class="ml10"><input name="WeekDayStart" type="radio" value="0" <?php if($arr[2][$k]=='0'){echo "checked";}?> />周日</label> <u>(每周的开始，影响统计数据显示)</u></p>
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
<?php }?>

<?php if((get('action')=='smtp') and ($userinfo['isadmin']=="1")){?>
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
<?php }?>

<?php if((get('action')=='user') and ($userinfo['isadmin']=="1")){?>
<div class="table stat"><div class="itlu-title"><span class="pull-right"><button type="button" class="btn btn-primary btn-xs" id="btn_add_user">添加用户</button></span>用户管理</div></div>
<table width="100%" border="0" align="left" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <th align="left" bgcolor="#EBEBEB">帐号</th>
		<th align="left" bgcolor="#EBEBEB">类型</th>
        <th align="left" bgcolor="#EBEBEB">邮箱</th>
		<th align="left" bgcolor="#EBEBEB">角色</th>
		<th align="left" bgcolor="#EBEBEB">关联项目</th>
        <th align="left" bgcolor="#EBEBEB">注册时间</th>
		<th align="left" bgcolor="#EBEBEB">状态</th>
		<th align="left" bgcolor="#EBEBEB">操作</th>
    </tr>
	<?php 
	$userlist = db_list("user","where uid>0","order by uid asc");
	foreach($userlist as $myrow){
		if($myrow['Isallow']=="0"){
			$res = "<span class='green'>正常</span>";
			$btn_show ="<a class=\"btn btn-success btn-xs\" href=\"javascript:\" onclick=\"changeuser('noallow',$myrow[uid],'0');\">禁用</a>";
		}else{
			$res = "<span class='red'>禁用</span>";
			$btn_show ="<a class=\"btn btn-success btn-xs\" href=\"javascript:\" onclick=\"changeuser('allow',$myrow[uid],'0');\">启用</a>";
		}
		if($userid == $myrow['uid'] or $myrow["uid"]==1){
			$btn_show ="";			
		}else{
			$btn_show .= " <a class=\"btn btn-warning btn-xs\" href=\"javascript:\" onclick=\"changeuser('changepassword',$myrow[uid],'$myrow[username]');\">重置</a> ";
			$btn_show .= "<a class='btn btn-primary btn-xs' href='javascript:' onclick='edit_user(this)' data-info='{\"uid\":\"".$myrow["uid"]."\",\"pro_id\":\"".$myrow["pro_id"]."\",\"role_id\":\"".$myrow["role_id"]."\",\"username\":".json_encode($myrow["username"]).",\"email\":\"".$myrow["email"]."\",\"isadmin\":\"".$myrow["Isadmin"]."\"}'>修改</a> <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"user\",".$myrow["uid"].")'>删除</a>";
		}		
	?>
    <tr><td align='left' bgcolor='#FFFFFF'><?php echo $myrow['username'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo admin_type($myrow['Isadmin']);?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow['email'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo rolename($myrow['role_id'],"系统内置");?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo programname($myrow['pro_id'],$userid,"不限制");?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo date("Y-m-d",$myrow['addtime']);?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $res;?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $btn_show;?></td>
	</tr>
	<?php }?>
</table>
<!--// 添加编辑用户-->
<div class="modal fade" id="myModal_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="add_form_user" name="add_form_user" method="post">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel_user">添加</h4>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<label for="bankname">登录账号</label>
					<input name="userid" id="userid" type="hidden" />
					<input type="text" name="username" class="form-control" id="username" placeholder="请输入登录账号" required="请输入登录账号" maxlength="20">
				</div>
				<div class="form-group">
					<label for="isadmin">账户类型</label>
					<select name="isadmin" id="isadmin" class="form-control">
						<?php for($i=0;$i<2;$i++){?>
                        <option value="<?php echo $i;?>"><?php echo admin_type($i);if($i==0){echo "(需关联项目)";}?></option>
						<?php }?>						
                    </select>
				</div>
				<div class="form-group">
					<label for="pro_id">关联项目</label>
					<select name="pro_id" id="pro_id" class="form-control">
						<option value="0">==不关联项目==</option>
						<?php 
						$pay_type_list = show_program($userid,0,1);						
						foreach($pay_type_list as $row){
						?>
                        <option value="<?php echo $row['proid'];?>"><?php echo $row['proname'];?></option>
						<?php }?>						
                    </select>
				</div>
				<div class="form-group">
					<label for="role_id">所属角色</label>
					<select name="role_id" id="role_id" class="form-control">
						<?php 
						$rolelist_e = db_list("sys_role","where role_id>0","order by role_id asc");
						foreach($rolelist_e as $row_e){
						?>
                        <option value="<?php echo $row_e["role_id"];?>"><?php echo $row_e["role_name"];?></option>
						<?php }?>						
                    </select>
				</div>
				<div class="form-group">
					<label for="email">电子邮箱</label>
					<input type="text" name="email" class="form-control" id="email" placeholder="请输入电子邮箱" required="请输入电子邮箱" maxlength="30">
				</div>
				<div class="form-group" id="password_div">
					<label for="password">登录密码</label>
					<input type="text" name="password" class="form-control" id="password" placeholder="请输入登录密码(长度为6-20)" required="请输入登录密码(长度为6-20)" maxlength="20">
				</div>		
			</div>
			<div class="modal-footer">
				<div id="error_show_user" class="footer-tips"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="btn_submit_user" date-info="add" class="btn btn-primary">保存</button>
			</div>
		</div>
		</form>
	</div>
</div>
<?php }?>


<?php if((get('action')=='role') and ($userinfo['isadmin']=="1")){?>
<div class="table stat"><div class="itlu-title"><span class="pull-right"><button type="button" class="btn btn-primary btn-xs" id="btn_add_role">添加角色</button></span>角色管理</div></div>
<table width="100%" border="0" align="left" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <th align="left" bgcolor="#EBEBEB">角色名称</th>
		<th align="left" bgcolor="#EBEBEB">角色描述</th>
        <th align="left" bgcolor="#EBEBEB">权限详细</th>
        <th align="left" bgcolor="#EBEBEB">编辑时间</th>
		<th align="left" bgcolor="#EBEBEB">操作</th>
    </tr>
	<?php 
	$rolelist = db_list("sys_role","where role_id>0","order by role_id asc");
	foreach($rolelist as $myrow){
		if($myrow['updatetime']>0){$update_time = date("Y-m-d H:i:s",$myrow['updatetime']);}else{$update_time = "-";}
		$btn_show = "<a class='btn btn-primary btn-xs' href='javascript:' onclick='edit_role(this)' data-info='{\"role_id\":\"".$myrow["role_id"]."\",\"role_name\":".json_encode($myrow["role_name"]).",\"role_description\":".json_encode($myrow["role_description"])."}'>修改</a> <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"role\",".$myrow["role_id"].")'>删除</a>";
		$modify_role_btn = "<a class='btn btn-default btn-xs' href='javascript:' onclick='edit_rolelist(this)' data-info='{\"role_id\":\"".$myrow["role_id"]."\",\"role_menu_id\":\"".$myrow["role_menu_id"]."\"}'>编辑权限</a>";
	?>
    <tr><td align='left' bgcolor='#FFFFFF'><?php echo $myrow['role_name'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow['role_description'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $modify_role_btn;?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $update_time;?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $btn_show;?></td>
	</tr>
	<?php }?>
</table>
<!--// 添加角色-->
<div class="modal fade" id="myModal_role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="add_form_role" name="add_form_role" method="post">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">添加</h4>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<label for="role_name">角色名称</label>
					<input name="role_id" id="role_id" type="hidden" />
					<input type="text" name="role_name" class="form-control" id="role_name" placeholder="请输入角色名称" required="请输入角色名称">
				</div>
				<div class="form-group">
					<label for="role_description">角色描述</label>
					<input type="text" name="role_description" class="form-control" id="role_description" placeholder="请输入角色描述（非必填）" required="请输入角色描述（非必填）">
				</div>
			</div>
			<div class="modal-footer">
				<div id="error_show_role" class="footer-tips"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="btn_submit_role" date-info="add" class="btn btn-primary">保存</button>
			</div>
		</div>
		</form>
	</div>
</div>
<!--//权限列表-->
<div class="modal fade" id="myModal_rolelist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="add_form_rolelist" name="add_form_rolelist" method="post">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel_rolelist">添加</h4>
			</div>
			<div class="modal-body"><input name="role_id_rolelist" id="role_id_rolelist" type="hidden" />				
				<div class="form-group">					
				
				<table align="center" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
					<?php
					$menulist_2 = db_list("sys_menu","where m_f_id=0 and m_type=1 and isshow=1 ","order by orderid desc,m_id asc");
					foreach($menulist_2 as $myrow_2){
					?>
					<tr><td bgcolor="#EBEBEB"><label for="rolelist_id_<?php echo $myrow_2['m_id'];?>"><input type="checkbox" name="rolelist_id" id="rolelist_id_<?php echo $myrow_2['m_id'];?>" value="<?php echo $myrow_2['m_id'];?>"/><?php echo $myrow_2['m_name'];?></label></td></tr>
					<tr><td bgcolor="#FFFFFF"><div class="role_menu">
						<?php
						$menulist_2_c = db_list("sys_menu","where m_f_id= '$myrow_2[m_id]' and isshow=1 ","order by orderid desc,m_id asc");
						foreach($menulist_2_c as $myrow_2_c){?>
							<div class="role_menu_list"><label for="rolelist_id_<?php echo $myrow_2_c['m_id'];?>" class="fwn"><input type="checkbox" name="rolelist_id" id="rolelist_id_<?php echo $myrow_2_c['m_id'];?>" value="<?php echo $myrow_2_c['m_id'];?>"/><?php echo $myrow_2_c['m_name'];?></label></div>
						<?php }?>
					</div></td></tr>
					<?php }?>
				</table>
				</div>
			</div>
			<div class="modal-footer">
				<div id="error_show_rolelist" class="footer-tips"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="btn_submit_rolelist" date-info="modify" class="btn btn-primary">保存</button>
			</div>
		</div>
		</form>
	</div>
</div>
<?php }?>

<!--//菜单管理-->
<?php if((get('action')=='menu') and ($userinfo['isadmin']=="1")){?>
<div class="table stat"><div class="itlu-title"><!--<span class="pull-right"><button type="button" class="btn btn-primary btn-xs" id="btn_add_menu">添加菜单</button></span>-->菜单管理</div></div>
<table width="100%" border="0" align="left" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <th align="left" bgcolor="#EBEBEB">菜单ID</th>
		<th align="left" bgcolor="#EBEBEB">菜单名称</th>
		<th align="left" bgcolor="#EBEBEB">菜单类型</th>
        <th align="left" bgcolor="#EBEBEB">链接/参数</th>
		<th align="left" bgcolor="#EBEBEB">排序</th>
		<!--<th align="left" bgcolor="#EBEBEB">操作</th>-->
    </tr>
	<?php 
	$menulist = db_list("sys_menu","where m_f_id=0 and m_type=1 and isshow=1 ","order by orderid desc,m_id asc");
	foreach($menulist as $myrow){
		$btn_show = "<a class='btn btn-primary btn-xs' href='javascript:' onclick='edit_menu(this)' data-info='{\"m_id\":\"".$myrow["m_id"]."\",\"m_url\":\"".$myrow["m_url"]."\",\"orderid\":\"".$myrow["orderid"]."\",\"m_type\":\"".$myrow["m_type"]."\",\"m_f_id\":\"".$myrow["m_f_id"]."\",\"m_name\":".json_encode($myrow["m_name"])."}'>修改</a>";
		if($myrow["islock"]==0){
			$btn_show .= " <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"menu\",".$myrow["m_id"].")'>删除</a>";
		}
	?>
    <tr>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow['m_id'];?></td>
		<td align='left' bgcolor='#FFFFFF'><b><?php echo $myrow['m_name'];?></b></td>
		<td align='left' bgcolor='#FFFFFF'>一级菜单</td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow['m_url'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow['orderid'];?></td>
		<!--<td align='left' bgcolor='#FFFFFF'><?php echo $btn_show;?></td>-->
	</tr>
		<?php 
		$menulist_f_2 = db_list("sys_menu","where m_type=2 and m_f_id= '$myrow[m_id]' and isshow=1 ","order by orderid desc,m_id asc");
		foreach($menulist_f_2 as $myrow_f_2){
			$btn_show = "<a class='btn btn-primary btn-xs' href='javascript:' onclick='edit_menu(this)' data-info='{\"m_id\":\"".$myrow_f_2["m_id"]."\",\"m_url\":\"".$myrow_f_2["m_url"]."\",\"orderid\":\"".$myrow_f_2["orderid"]."\",\"m_type\":\"".$myrow_f_2["m_type"]."\",\"m_f_id\":\"".$myrow_f_2["m_f_id"]."\",\"m_name\":".json_encode($myrow_f_2["m_name"])."}'>修改</a>";
			if($myrow_f_2["islock"]==0){
				$btn_show .= " <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"menu\",".$myrow_f_2["m_id"].")'>删除</a>";
			}
		?>
		<tr>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow_f_2['m_id'];?></td>
		<td align='left' bgcolor='#FFFFFF'>|-- <?php echo $myrow_f_2['m_name'];?></td>
		<td align='left' bgcolor='#FFFFFF'>二级菜单</td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow_f_2['m_url'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow_f_2['orderid'];?></td>
		<!--<td align='left' bgcolor='#FFFFFF'><?php echo $btn_show;?></td>-->
		</tr>
		<?php 
		}
		$menulist_f_3 = db_list("sys_menu","where m_type=3 and m_f_id= '$myrow[m_id]' and isshow=1","order by orderid desc,m_id asc");
		foreach($menulist_f_3 as $myrow_f_3){
			$btn_show = "<a class='btn btn-primary btn-xs' href='javascript:' onclick='edit_menu(this)' data-info='{\"m_id\":\"".$myrow_f_3["m_id"]."\",\"m_url\":\"".$myrow_f_3["m_url"]."\",\"orderid\":\"".$myrow_f_3["orderid"]."\",\"m_type\":\"".$myrow_f_3["m_type"]."\",\"m_f_id\":\"".$myrow_f_3["m_f_id"]."\",\"m_name\":".json_encode($myrow_f_3["m_name"])."}'>修改</a>";
			if($myrow_f_3["islock"]==0){
				$btn_show .= " <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"menu\",".$myrow_f_3["m_id"].")'>删除</a>";
			}
		?>
		<tr>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow_f_3['m_id'];?></td>
		<td align='left' bgcolor='#FFFFFF'>|-- <?php echo $myrow_f_3['m_name'];?></td>
		<td align='left' bgcolor='#FFFFFF'>按钮</td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow_f_3['m_url'];?></td>
		<td align='left' bgcolor='#FFFFFF'><?php echo $myrow_f_3['orderid'];?></td>
		<!--<td align='left' bgcolor='#FFFFFF'><?php echo $btn_show;?></td>-->
		</tr>
		<?php }}?>
</table>
<!--// 添加-->
<div class="modal fade" id="myModal_menu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="add_form_menu" name="add_form_menu" method="post">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">添加</h4>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<label for="m_name">菜单名称</label>
					<input name="m_id" id="m_id" type="hidden" />
					<input type="text" name="m_name" class="form-control" id="m_name" placeholder="请输入菜单名称" required="请输入菜单名称">
				</div>
				<div class="form-group">
					<label for="m_type">菜单类型</label>
					<select name="m_type" id="m_type" class="form-control">
						<option value="1">一级菜单</option>
						<option value="2">二级菜单</option>
						<option value="3">操作按钮</option>
					</select>
				</div>
				<div class="form-group">
					<label for="m_f_id">父级菜单</label>
					<select name="m_f_id" id="m_f_id" class="form-control">
						<option value="0">无父级菜单(一级)</option>
						<?php
						$menulist_f_0 = db_list("sys_menu","where m_f_id=0 and m_type=1 ","order by orderid desc,m_id asc");
						foreach($menulist_f_0 as $r_f_0){
						?>
                        <option value="<?php echo $r_f_0['m_id'];?>"><?php echo $r_f_0['m_name'];?></option>
						<?php }?>
                    </select>
				</div>				
				<div class="form-group">
					<label for="m_url">链接/参数</label>
					<input type="text" name="m_url" class="form-control" id="m_url" placeholder="请输入链接/参数" required="请输入链接/参数" />
				</div>
				<div class="form-group">
					<label for="orderid">排序</label>
					<input type="text" name="orderid" class="form-control" id="orderid" placeholder="排序ID" required="序号越大越靠前">
				</div>
			</div>
			<div class="modal-footer">
				<div id="error_show_menu" class="footer-tips"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="btn_submit_menu" date-info="add" class="btn btn-primary">保存</button>
			</div>
		</div>
		</form>
	</div>
</div>
<?php }?>

<?php if((get('action')=='export') and ($userinfo['isadmin']=="1")){?>
<table align="left" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">数据导出</td></tr>
    <tr><td bgcolor="#FFFFFF">
			<div class="form-group block mt20">
			<div class="col-sm-10">
				<input type="button" class="btn btn-primary" id="exportCSV" value="导出全部记账CSV" onClick="window.location.href='date.php?action=export'">
			</div>
			</div>
			<div class="form-group block mt20">
			<div class="col-sm-8">
				<span class="red">导出全部记账数据，该功能仅对管理员开放</span><br />
			</div>
			</div>
        </form>
    </td></tr>
</table>
<?php }?>

<script language="javascript">
$("#btn_add_user").click(function(){
	chushihua_user();
	$('#myModal_user').modal({backdrop:'static', keyboard:false});
});
$("#btn_submit_user").click(function(){
	$("#error_show_user").html("提交中...");
	$(this).addClass("disabled");
	var action = $(this).attr("date-info");
	send_post_form(action,"user");
});
//==================
$("#btn_add_menu").click(function(){
	chushihua_menu();
	$('#myModal_menu').modal({backdrop:'static', keyboard:false});
});
$("#btn_submit_menu").click(function(){	
	$(this).addClass("disabled");
	var action = $(this).attr("date-info");
	send_post_form(action,"menu");
});
//============
$("#btn_add_role").click(function(){
	chushihua_role();
	$('#myModal_role').modal({backdrop:'static', keyboard:false});
});
$("#btn_submit_role").click(function(){
	$("#error_show_role").html("提交中...");
	$(this).addClass("disabled");
	var action = $(this).attr("date-info");
	send_post_form(action,"role");
});
$("#btn_submit_rolelist").click(function(){
	$("#error_show_rolelist").html("提交中...");
	$(this).addClass("disabled");
	var role_id = $("#role_id_rolelist").val();
	var sendtype = "rolelist";
	var rolelist_id = [];
	$("input[name='rolelist_id']:checked").each(function(i){
		rolelist_id[i] =$(this).val();
	});
	$.post('date.php?action=modifyrolelist',{role_id:role_id,rolelist_id:rolelist_id},function(result){
		$("#error_show_"+sendtype).show();
		var data = '';
		if(result != ''){
			data = eval("("+result+")");
		}
		$('#error_show_'+sendtype).html(data.error_msg);
		if(data.url != ""){
			location.href = data.url;
		}else{
			$("#btn_submit_"+sendtype).removeClass("disabled");
		}
	});
});
//============
function checkpost(type,form){
	if(type=="user"){
		if(isEmpty(form.password.value)){
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
	else if(type=="system"){
		if(isEmpty(form.SiteName.value)){
			alert("站点名称不能为空！");
			form.SiteName.focus();
			return false;
		}
		if(isEmpty(form.SiteURL.value)){
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
	else if(type=="smtp"){
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
function updateUserInfo(sendtype){
	var geturl = "date.php?action=update"+sendtype;
	var formname = "#"+sendtype+"_form";
	var error_id = "#"+sendtype+"_error_show";
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
			if(data.code == "0"){$(".btn_div > #submit_"+sendtype).removeClass("disabled");}
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
	if(type=="changepassword"){
		geturl = "date.php?action=changepassword&name="+name+"&uid="+uid+"";
	}else{
		geturl = "date.php?action=changeuser&m="+type+"&uid="+uid+"";
	}
	Ewin.confirm({ message: "确认要进行该操作吗？" }).on(function (e) {
		if(!e){return;}
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
			error : function(){}
		});
	});
}
</script>
<?php include_once("footer.php");?>