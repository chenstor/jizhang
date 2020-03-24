<?php include("header.php");?>
<div class="table stat"><div class="itlu-title"><span class="pull-right"><button type="button" class="btn btn-primary btn-xs" id="btn_add">添加账户</button></span>账户管理</div></div>
<?php
	show_tab(4);
	$banklist = db_list("bank","where userid='$userid'","order by bankid desc");
	foreach($banklist as $row){
		echo "<ul class=\"table-row\">";
			echo "<li>".$row["bankname"]."</li>";
			echo "<li>".$row["bankaccount"]."</li>";
			echo "<li>".$row["balancemoney"]."</li>";
			//echo "<li>".count_money($row["balancemoney"],$start_w_date,$end_w_date)."</li>";
			//echo "<li class='red'>".count_bank_money($row["bankid"],$start_w_date,$end_w_date)."</li>";
			echo "<li><a class='btn btn-default btn-xs' href='javascript:' onclick='location.href=\"show.php?classid=all&starttime=&endtime=$today&startmoney=&endmoney=&proid=&bankid=$row[bankid]\"'>查看明细</a> <a class='btn btn-primary btn-xs' href='javascript:' onclick='edit(this)' data-info='{\"bankid\":\"".$row["bankid"]."\",\"bankaccount\":\"".$row["bankaccount"]."\",\"bankname\":".json_encode($row["bankname"])."}'>修改</a> <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"bank\",".$row["bankid"].")'>删除</a></li>";
		echo "</ul>";
    }
	show_tab(3);
    ?>
<?php

$pay_type_list = show_program($userid);
$plist = "";
foreach($pay_type_list as $rowshow){
	$plist .= "<option value='".$rowshow['proid']."'>".$rowshow['proname']."</option>";
}
?>
<?php include("footer.php");?>
<!--// 添加-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="addform" name="addform" method="post">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">添加账户</h4>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<label for="bankname">账户名称</label>
					<input name="bankid" id="bankid" type="hidden" />
					<input type="text" name="bankname" class="form-control" id="bankname" placeholder="请输入名称" required="请输入名称">
				</div>
				<div class="form-group">
					<label for="bankaccount">账号/卡号</label>
					<input type="text" name="bankaccount" class="form-control" id="bankaccount" placeholder="请输入账号/卡号" required="请输入账号/卡号">
				</div>
				<div class="form-group" id="balancemoney_show">
					<label for="balancemoney">账户初始余额</label>
					<input type="number" step="0.01" name="balancemoney" class="form-control" id="balancemoney" placeholder="请输入金额" required="请输入金额" />
				</div>		
			</div>
			<div class="modal-footer">
				<div id="error_show" class="footer-tips"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="btn_submit" date-info="add" class="btn btn-primary">保存</button>
			</div>
		</div>
		</form>
	</div>
</div>
<script type="text/javascript">
chushihua_bank();
$("#btn_add").click(function(){
	chushihua_bank();
	$('#myModal').modal({backdrop:'static', keyboard:false});
});
$("#btn_submit").click(function(){
	$(this).addClass("disabled");
	var action = $(this).attr("date-info");
	bank_post_form(action);
});
// 编辑分类
function edit(t){
	//初始化
	chushihua_bank();
	var info = $(t).data('info');
	var bankname = info.bankname;
	var bankaccount = info.bankaccount;
	var bankid = info.bankid;
	$("#myModalLabel").text("编辑信息");
	$("#myModal").modal({backdrop:'static', keyboard:true});
	$("#bankname").val(bankname);
	$("#bankaccount").val(bankaccount);
	$("#bankid").val(bankid);
	$("#balancemoney_show").hide();
	$('#btn_submit').attr('date-info','modify');
}
</script>