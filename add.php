<?php include_once("header.php");?>
<?php
//账户列表
$banklist = db_list("bank","where userid='$userid'","order by bankid asc");
$banklist_show = '';
foreach($banklist as $myrow){
	$banklist_show = $banklist_show."<option value='$myrow[bankid]'>".$myrow['bankname']."</option>";
}
?>
<table align="left" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <td bgcolor="#EBEBEB" class="add_th">
		<i class="pull-right"><button type="button" class="btn btn-primary btn-xs" onclick="location='batch_add.php'">批量记</button></i>
		<div class="tab-title"><span class="red on" data-id="pay">支出</span><span class="green" data-id="income">收入</span></div>
		</td>
    </tr>
    <tr>
		<td bgcolor="#FFFFFF" id="contentbox">
		<div class="record_form" id="pay">
			<form id="pay_form" name="pay_form" method="post" onsubmit="return checkpost(this,'pay');">
			<input name="zhifu" type="hidden" id="zhifu" value="2" />
			<div class="input-group">
				<span class="input-group-label">金额</span>
				<input class="form-field" type="number" step="0.01" name="money" id="money" size="20" maxlength="8" />
			</div>
			<div class="input-group">
				<span class="input-group-label">分类</span>
				<select class="form-field" name="classid" id="classid">
                <?php
				$pay_type_list = show_type(2,$userid);
				foreach($pay_type_list as $myrow){
					echo "<option value='$myrow[classid]'>".$myrow['classname']."</option>";
				}
                ?>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">账户</span>
				<select class="form-field" name="bankid" id="bankid">
				<option value="0">默认账户</option>
                <?php echo $banklist_show;?>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">备注</span>
				<input class="form-field" type="text" name="remark" id="remark" size="30" maxlength="20">
			</div>
			<div class="input-group">
				<span class="input-group-label">时间</span>
				<input class="form-field" type="text" name="time" id="time" size="30" value="<?php echo date("Y-m-d H:i");?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:'<?php echo $today;?>'})" />
			</div>
			<div class="input-group">
				<button name="submit" type="submit" id="submit_pay" class="btn btn-danger">支出记一笔</button>				
			</div>
			<span id="pay_error" class="red"></span>
			</form>
		</div>
        
		<div class="record_form" id="income" style="display:none;">
			<form id="income_form" name="income_form" method="post" onsubmit="return checkpost(this,'income');">
			<input name="zhifu" type="hidden" id="zhifu" value="1" />
			<div class="input-group">
				<span class="input-group-label">金额</span>
				<input class="form-field" type="number" step="0.01" name="money" id="money" size="20" maxlength="8" />
			</div>
			<div class="input-group">
				<span class="input-group-label">分类</span>
				<select class="form-field" name="classid" id="classid">
                <?php
				$pay_type_list = show_type(1,$userid);
				foreach($pay_type_list as $myrow){
					echo "<option value='$myrow[classid]'>".$myrow['classname']."</option>";
				}
                ?>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">账户</span>
				<select class="form-field" name="bankid" id="bankid">
				<option value="0">默认账户</option>
                <?php echo $banklist_show;?>
				</select>
			</div>
			<div class="input-group">
				<span class="input-group-label">备注</span>
				<input class="form-field" type="text" name="remark" id="remark" size="30" maxlength="20">
			</div>
			<div class="input-group">
				<span class="input-group-label">时间</span>
				<input class="form-field" type="text" name="time" id="time" size="30" value="<?php echo date("Y-m-d H:i");?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:'<?php echo $today;?>'})" />
			</div>
			<div class="input-group">
				<button name="submit" type="submit" id="submit_income" class="btn btn-success">收入记一笔</button>				
			</div>
			<span id="income_error" class="red"></span>
			</form>
		</div>            
		</td>
	</tr>
</table>

<div class="table stat"><div id="stat"></div></div>

<?php
$s_classid = 'all';
$s_starttime = $today;
$s_endtime = $today;
$s_startmoney = '';
$s_endmoney = '';
$s_remark = '';
$s_bankid = '';
$s_page = '1';

show_tab(1);
$Prolist = itlu_page_search($userid,50,$s_page,$s_classid,$s_starttime,$s_endtime,$s_startmoney,$s_endmoney,$s_remark,$s_bankid);
$thiscount = 0;
foreach($Prolist as $row){
	if($row['zhifu']==1){
		$fontcolor = "green";
		$word = "收入";
	}else{
		$fontcolor = "red";
		$word = "支出";
	}
	echo "<ul class=\"table-row ".$fontcolor."\">";
		echo "<li><i class='noshow'>".$word.">></i>".$row['classname']."</li>";
		echo "<li>".bankname($row['bankid'],$userid,"默认账户")."</li>";
		echo "<li class='t_a_r'>".$row['acmoney']."</li>";
		if(isMobile()){
			echo "<li>".date("m-d",$row['actime'])."</li>";
		}else{
			echo "<li>".date("Y-m-d H:i",$row['actime'])."</li>";
		}
		echo "<li>".$row['acremark']."</li>";
		echo "<li><a href='javascript:' onclick='editRecord(this,\"myModal\")' data-info='{\"id\":\"".$row["acid"]."\",\"money\":\"".$row["acmoney"]."\",\"zhifu\":\"".$row["zhifu"]."\",\"bankid\":\"".$row["bankid"]."\",\"addtime\":\"".date("Y-m-d H:i",$row['actime'])."\",\"remark\":".json_encode($row["acremark"]).",\"classname\":".json_encode($word." -- ".$row["classname"])."}'><img src='img/edit.png' /></a><a class='ml8' href='javascript:' onclick='delRecord(\"record\",".$row['acid'].");'><img src='img/del.png' /></a></li>";
	echo "</ul>";
	$thiscount ++ ;
}
show_tab(3);
?>
<script>
$("#stat").html("今天支出<strong class='red'><?php echo state_day($today,$today,$userid,2);?></strong>，收入<strong class='green'><?php echo state_day($today,$today,$userid,1);?></strong>");
</script>
<?php include_once("footer.php");?>
<!--// 编辑-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="edit-form" name="edit-form" method="post">
			<input name="edit-id" type="hidden" id="edit-id" />
			<input name="old-bank-id" type="hidden" id="old-bank-id" />
			<input name="old-money" type="hidden" id="old-money" />
			<input name="edit-zhifu" type="hidden" id="edit-zhifu" />
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">数据修改</h4>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<label for="edit-money">金额</label>
					<input type="number" step="0.01" name="edit-money" class="form-control" id="edit-money" placeholder="收支金额" required="请输入收支金额" />					
				</div>
				<div class="form-group">
					<label for="edit-classtype">分类</label>
					<input type="text" name="edit-classtype" class="form-control" id="edit-classtype" readonly="readonly" />
				</div>
				<div class="form-group">
					<label for="edit-remark">备注</label>
					<input type="text" name="edit-remark" class="form-control" id="edit-remark" maxlength="20" />
				</div>
				<div class="form-group">
					<label for="edit-bankid">账户</label>
					<select name="edit-bankid" id="edit-bankid" class="form-control">
						<option value='0'>默认账户</option>
						<?php echo $banklist_show;?>
					</select>
				</div>
				<div class="form-group">
					<label for="edit-time">时间</label>
					<input type="text" name="edit-time" class="form-control" id="edit-time" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:'<?php echo $today;?>'})" />
				</div>
			</div>
			<div class="modal-footer">
				<div id="error_show" class="footer-tips"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="btn_submit_save_edit" class="btn btn-primary">保存</button>
			</div>
		</div>
		</form>
	</div>
</div>
<script language="javascript">
function checkpost(form,type){
	if ((form.money.value == "") || (form.money.value <= 0)) {
		alert("请输入金额且金额必须大于0");
		form.money.focus();
		return false;
	}
	$("#submit_"+type).addClass("disabled");
	saverecord(type);
	return false;
}
</script>