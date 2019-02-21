<?php
include_once("header.php");
?>
<div class="table stat"><div class="itlu-title"><span class="pull-right"><button type="button" class="btn btn-primary btn-xs" id="btn_add">添加账户</button></span>账户管理</div></div>

    <?php
	show_tab(4);
	$banklist = db_list("bank","where userid='$userid'","order by bankid asc");
	foreach($banklist as $row){
		echo "<ul class=\"table-row\">";
			echo "<li>".$row["bankname"]."</li>";
			echo "<li>".$row["bankaccount"]."</li>";
			echo "<li>".$row["balancemoney"]."</li>";
			echo "<li><a class='btn btn-primary btn-xs' href='javascript:' onclick='edit(this)' data-info='{\"bankid\":\"".$row["bankid"]."\",\"money\":\"".$row["balancemoney"]."\",\"bankaccount\":\"".$row["bankaccount"]."\",\"bankname\":".json_encode($row["bankname"])."}'>修改</a> <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"bank\",".$row["bankid"].")'>删除</a></li>";
		echo "</ul>";
    }
	show_tab(3);
    ?>
<?php include_once("footer.php");?>
<!--// 添加编辑分类-->
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
					<input type="text" name="bankname" class="form-control" id="bankname" placeholder="账户名称" required="请输入账户名称">
				</div>
				<div class="form-group">
					<label for="bankaccount">卡号/帐号</label>
					<input type="text" name="bankaccount" class="form-control" id="bankaccount" placeholder="卡号/帐号" required="请输入卡号/帐号">
				</div>
				<div class="form-group">
					<label for="balancemoney">账户余额</label>
					<input type="number" step="0.01" name="balancemoney" class="form-control" id="balancemoney" placeholder="账户余额" required="请输入账户余额" />
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
	saveclassify(action);
});

function saveclassify(action){
	/*if(type=="save"){
		posturl = "date.php?action=addbank";
	}else if(type=="modify"){
		posturl = "date.php?action=modifybank";
	}*/
	posturl = "date.php?action="+action+"bank";
	$.ajax({
		type: "POST",
		dataType: "json",
		url: posturl,
		data: $('#addform').serialize(),
		success: function (result) {
			$("#error_show").show();
			var data = '';
			if(result != ''){
				data = eval("("+result+")");
			}
			$('#error_show').html(data.error_msg);
			if(data.url != ""){
				location.href=data.url;
			}else{
				$("#btn_submit").removeClass("disabled");
			}			
		},
		error : function() {
			$("#error_show").hide();
			console.log(result);
		}
	});
}
// 编辑分类
function edit(t){
	//初始化
	chushihua_bank();
	var info = $(t).data('info');
	var bankname = info.bankname;
	var bankaccount = info.bankaccount;
	var money = info.money;
	var bankid = info.bankid;
	$("#myModalLabel").text("编辑账户");
	$("#myModal").modal({backdrop:'static', keyboard:true});
	$("#bankname").val(bankname);
	$("#bankaccount").val(bankaccount);
	$("#balancemoney").val(money);
	$("#bankid").val(bankid);
	$('#btn_submit').attr('date-info','modify');
}
</script>