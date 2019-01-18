<?php include_once("header.php");?>
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
			<p class="red"><label for="money">金额：<input class="w180" type="text" name="money" id="money" size="20" maxlength="8"></label></p>
			<p><label for="classid">分类：<select class="w180" name="classid" id="classid">
                <?php
				$pay_type_list = show_type(2,$_SESSION['uid']);
				foreach($pay_type_list as $myrow){
					echo "<option value='$myrow[classid]'>".$myrow['classname']."</option>";
				}
                ?>
            </select></label><a href="classify.php" class="addclass">添加分类</a></p>
			<p><label for="remark">备注：<input class="w180" type="text" name="remark" id="remark" size="30" maxlength="8"></label></p>
			<p><label for="time">时间：<input class="w180" type="text" name="time" id="time" size="30" value="<?php echo date("Y-m-d H:i");?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:'<?php echo $today;?>'})" /></label></p>
			<p class="btn_div">
				<button name="submit" type="submit" id="submit" class="btn btn-danger">支出记一笔</button>
				<span id="pay_error" class="red"></span></p>
			</form>
		</div>
        
		<div class="record_form" id="income" style="display:none;">
			<form id="income_form" name="income_form" method="post" onsubmit="return checkpost(this,'income');">
			<input name="zhifu" type="hidden" id="zhifu" value="1" />
			<p class="green"><label for="money">金额：<input class="w180" type="text" name="money" id="money" size="20" maxlength="8"></label></p>
			<p><label for="classid">分类：<select class="w180" name="classid" id="classid">
                <?php
				$pay_type_list = show_type(1,$_SESSION['uid']);
				foreach($pay_type_list as $myrow){
					echo "<option value='$myrow[classid]'>".$myrow['classname']."</option>";
				}
                ?>
            </select></label><a href="classify.php" class="addclass">添加分类</a></p>
			<p><label for="remark">备注：<input class="w180" type="text" name="remark" id="remark" size="30" maxlength="8"></label></p>
			<p><label for="time">时间：<input class="sang_Calender" type="text" name="time" id="time" size="30" value="<?php echo date("Y-m-d H:i");?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:'<?php echo $today;?>'})" /></label></p>
			<p class="btn_div">
				<button name="submit" type="submit" id="submit" class="btn btn-success">收入记一笔</button>
				<span id="income_error" class="red"></span></p>
			</form>
		</div>            
		</td>
	</tr>
</table>

<table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class='table table-striped table-bordered'>
    <tr>
        <td id="stat"></td>
    </tr>
</table>

<?php
$get_page = get("page","1"); //获取参数
show_tab(1);
show_tab(2);
$Prolist = itlu_page_query($_SESSION['uid'],20,$get_page);
foreach($Prolist as $row){
	if($row['zhifu']==1){
		$fontcolor = "green";
		$word = "收入";
	}else{
		$fontcolor = "red";
		$word = "支出";
	}
	echo "<tr class='".$fontcolor."'>";
		echo "<td align='left' bgcolor='#FFFFFF'>".$row['classname']."</td>";
		echo "<td align='left' bgcolor='#FFFFFF'>".$word."</td>";
		echo "<td align='left' bgcolor='#FFFFFF'>".$row['acmoney']."</td>";		
		echo "<td align='left' bgcolor='#FFFFFF'>".date("Y-m-d",$row['actime'])."</td>";
		echo "<td align='left' bgcolor='#FFFFFF'>".$row['acremark']."</td>";
		echo "<td align='left' bgcolor='#FFFFFF' class='noshow'><a href='javascript:' onclick='editRecord(this,\"myModal\")' data-info='{\"id\":\"".$row["acid"]."\",\"money\":\"".$row["acmoney"]."\",\"addtime\":\"".date("Y-m-d h:i",$row['actime'])."\",\"remark\":".json_encode($row["acremark"]).",\"classname\":".json_encode($word." -- ".$row["classname"])."}'><img src='img/edit.png' /></a><a class='ml8' href='javascript:' onclick='del(".$row['acid'].");'><img src='img/del.png' /></a></td>";
	echo "</tr>";
}
show_tab(3);
?>
	<?php 
	$pages = record_num_query($_SESSION['uid'],"all");
	$pages = ceil($pages/20);	
	if($pages > 1){?>
	<div class="page"><?php getPageHtml($get_page,$pages,"show.php?");?></div>
	<?php }?>

<!--// 编辑-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="edit-form" name="edit-form" method="post">
		<input name="edit-id" type="hidden" id="edit-id" />
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">数据修改</h4>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<label for="edit-money">金额</label>
					<input type="text" name="edit-money" class="form-control" id="edit-money" placeholder="收支金额" required="请输入收支金额" />					
				</div>
				<div class="form-group">
					<label for="edit-classtype">分类</label>
					<input type="text" name="edit-classtype" class="form-control" id="edit-classtype" readonly="readonly" />
				</div>
				<div class="form-group">
					<label for="edit-remark">备注</label>
					<input type="text" name="edit-remark" class="form-control" id="edit-remark" maxlength="10" />
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
	$("#"+type+"_form > p > input[name='submit']").addClass("disabled");
	saverecord(type);
	return false;
}

function saverecord(type){
	form_name = "#"+type+"_form";
	error_name = "#"+type+"_error";
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "date.php?action=addrecord",
		data: $(form_name).serialize(),
		success: function (result) {
			$(error_name).show();
			//console.log(result);
			var data = '';
			if(result != ''){
				data = eval("("+result+")");
			}
			$(error_name).html(data.error_msg);
			if(data.url != ""){
				location.href = data.url;
			}else{
				$("#"+type+"_form > p > input[name='submit']").removeClass("disabled");
			}		
		},
		error : function() {
			$(error_name).hide();
			$("#"+type+"_form > p > input[name='submit']").removeClass("disabled");
		}
	});
}

function del(t){
	var r=confirm("确定删除该记录？");
	if (r==true){
		$.ajax({
			type:"get",
			url:"date.php?action=deleterecord&id="+t+"", //需要获取的页面内容
			async:true,
			success:function(data){
				alert(data);
				window.location.href="add.php";
			}
		});
	}
}

$("#stat").html("<span class='pull-right noshow'>↓↓下表显示最近20条记录</span>去年1月至今共收入<strong class='green'><?php echo state_day($last_year_start,$today,$_SESSION['uid'],1);?></strong>，共支出<strong class='red'><?php echo state_day($last_year_start,$today,$_SESSION['uid'],2);?></strong>");
</script>
<?php include_once("footer.php");?>