<?php include_once("header.php");
//============搜索参数处理================
$s_classid = get('classid','all');
$s_starttime = get('starttime');
$s_endtime = get('endtime',$today);//默认今天
$s_startmoney = get('startmoney');
$s_endmoney = get('endmoney');
$s_remark = get('remark');
$s_bankid = get('bankid');
$s_page = get('page','1');

$pageurl = "show.php?1=1";
if($s_classid != ""){
	$pageurl = $pageurl."&classid=".$s_classid;
}
if($s_starttime != ""){
	$pageurl = $pageurl."&starttime=".$s_starttime;
}
if($s_endtime != ""){
	$pageurl = $pageurl."&endtime=".$s_endtime;
}
if($s_startmoney != ""){
	$pageurl = $pageurl."&startmoney=".$s_startmoney;
}
if($s_endmoney != ""){
	$pageurl = $pageurl."&endmoney=".$s_endmoney;
}
if($s_remark != ""){
	$pageurl = $pageurl."&remark=".$s_remark;
}
if($s_bankid != ""){
	$pageurl = $pageurl."&bankid=".$s_bankid;
}
?>

<table align="left" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">查询修改</td></tr>
    <tr><td bgcolor="#FFFFFF">
			<div class="search_box">
				<form id="s_form" name="s_form" method="get">
				<p><label for="classid">分类：<select class="w180" name="classid" id="classid">
					<option value="all" <?php if($s_classid=="all"){echo "selected";}?>>全部分类</option>
					<option value="pay" <?php if($s_classid=="pay"){echo "selected";}?>>====支出====</option>
					<?php
					$pay_type_list = show_type(2,$userid);
					foreach($pay_type_list as $myrow){
						if($myrow['classid']==$s_classid){
							echo "<option value='$myrow[classid]' selected>支出>>".$myrow['classname']."</option>";
						}else{
							echo "<option value='$myrow[classid]'>支出>>".$myrow['classname']."</option>";
						}						
					}
					?>
					<option value="income" <?php if($s_classid=="income"){echo "selected";}?>>====收入====</option>
					<?php
					$pay_type_list = show_type(1,$userid);
					foreach($pay_type_list as $myrow){
						if($myrow['classid']==$s_classid){
							echo "<option value='$myrow[classid]' selected>收入 -- ".$myrow['classname']."</option>";
						}else{
							echo "<option value='$myrow[classid]'>收入 -- ".$myrow['classname']."</option>";
						}
					}
					?>
				</select></label></p>
				
				<p><label for="time">时间：<input class="w100" value="<?php echo $s_starttime;?>" type="text" name="starttime" id="starttime" onClick="WdatePicker({maxDate:'#F{$dp.$D(\'endtime\')||\'<?php echo $today;?>\'}'})" />-<input class="w100" type="text" name="endtime" value="<?php if($s_endtime==""){echo $today;}else{echo $s_endtime;}?>" id="endtime" onClick="WdatePicker({minDate:'#F{$dp.$D(\'starttime\')}',maxDate:'%y-%M-%d'})" /></label></p>
				
				<p><label for="money">金额：<input class="w100" value="<?php echo $s_startmoney;?>" type="number" step="0.01" name="startmoney" id="startmoney" size="10" maxlength="8" onkeyup="value=value.replace(/[^\d{1,}\.\d{1,}|\d{1,}]/g,'')" />-<input class="w100" value="<?php echo $s_endmoney;?>" type="number" step="0.01" name="endmoney" id="endmoney" size="10" maxlength="8" onkeyup="value=value.replace(/[^\d{1,}\.\d{1,}|\d{1,}]/g,'')" /></label></p>				
				<p><label for="remark">备注：<input class="w180" type="text" name="remark" id="remark" size="30" value="<?php echo $s_remark;?>"></label></p>
				<p><label for="bankid">账户：<select class="w180" name="bankid" id="bankid">
					<option value="" <?php if($s_bankid==""){echo "selected";}?>>全部账户</option>
					<option value="0" <?php if($s_bankid=="0"){echo "selected";}?>>默认账户</option>
					<?php
					$banklist = db_list("bank","where userid='$userid'","order by bankid asc");
					foreach($banklist as $myrow){
						if($myrow['bankid']==$s_bankid){
							echo "<option value='$myrow[bankid]' selected>".$myrow['bankname']."</option>";
						}else{
							echo "<option value='$myrow[bankid]'>".$myrow['bankname']."</option>";
						}						
					}
					?>
				</select></label></p>
				<p class="btn_div"><input type="submit" name="submit" value="查询" class="btn btn-primary" /></p>
				</form>
			</div>
        </td>
    </tr>
</table>

<?php	
	//show_tab(1);
	echo "<form name='del_all' id='del_all' method='post' onsubmit='return deleterecordAll(this);'>";
	show_tab(2);
		$Prolist = itlu_page_search($userid,20,$s_page,$s_classid,$s_starttime,$s_endtime,$s_startmoney,$s_endmoney,$s_remark,$s_bankid);
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
				echo "<li>".$row['acmoney']."</li>";
				if(isMobile()){
					echo "<li>".date("m-d",$row['actime'])."</li>";
				}else{
					echo "<li>".date("Y-m-d",$row['actime'])."</li>";
				}
				echo "<li>".$row['acremark']."</li>";
				echo "<li><a href='javascript:' onclick='editRecord(this,\"myModal\")' data-info='{\"id\":\"".$row["acid"]."\",\"money\":\"".$row["acmoney"]."\",\"zhifu\":\"".$row["zhifu"]."\",\"bankid\":\"".$row["bankid"]."\",\"addtime\":\"".date("Y-m-d h:i",$row['actime'])."\",\"remark\":".json_encode($row["acremark"]).",\"classname\":".json_encode($word." -- ".$row["classname"])."}'><img src='img/edit.png' /></a><a class='ml8' href='javascript:' onclick='delRecord(\"record\",".$row['acid'].");'><img src='img/del.png' /></a></li>";
				echo "<li class='noshow'><input name='del_id[]' type='checkbox' id='del_id[]' value=".$row['acid']." /></li>";
			echo "</ul>";
			$thiscount ++ ;
		}	
	echo "</form>";
	show_tab(3);	
?>
	<?php 
	//显示页码
	$allcount = record_num_query($userid,$s_classid,$s_starttime,$s_endtime,$s_startmoney,$s_endmoney,$s_remark,$s_bankid);
	$pages = ceil($allcount/20);	
	if($pages > 1){?>
	<div class="page"><?php getPageHtml($s_page,$pages,$pageurl."&",$thiscount,$allcount);?></div>
	<?php }?>
<?php
//取账户列表
$banklist = db_list("bank","where userid='$userid'","order by bankid asc");
$banklist_show = '';
foreach($banklist as $myrow){
	$banklist_show = $banklist_show."<option value='$myrow[bankid]'>".$myrow['bankname']."</option>";
}
?>
<?php include_once("footer.php");?>
<!--// 编辑-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="edit-form" name="edit-form" method="post">
		<input name="edit-id" type="hidden" id="edit-id" />
		<input name="old-bank-id" type="hidden" id="old-bank-id" />
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
					<label for="edit-bankid">帐户</label>
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
$('input[name="check_all"]').on("click",function(){
	if($(this).is(':checked')){
		$('input[name="del_id[]"]').each(function(){
			$(this).prop("checked",true);
		});
	}else{
		$('input[name="del_id[]"]').each(function(){
			$(this).prop("checked",false);
		});
	}
});
</script>