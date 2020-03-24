<?php
include_once("header.php");
?>
<div class="table stat"><div class="itlu-title"><span class="pull-right"><button type="button" class="btn btn-primary btn-xs" id="btn_add">添加项目</button></span>项目管理</div></div>

<?php
show_tab(7);
	$pay_type_list = show_program($userid);
	foreach($pay_type_list as $row){
		echo "<ul class=\"table-row\">";
		echo "<li>".$row["proname"]."</li>";
		echo "<li class='green' id='income_".$row["proid"]."'>0.00</li>";
		echo "<li class='red' id='pay_".$row["proid"]."'>0.00</li>";
		echo "<li>".$row["orderid"]."</li>";
		echo "<li><a class='btn btn-default btn-xs' href='javascript:' onclick='location.href=\"show.php?classid=all&starttime=&endtime=$today&startmoney=&endmoney=&proid=$row[proid]&bankid=\"'>查看明细</a> <a class='btn btn-primary btn-xs' href='javascript:' onclick='edit(this)' data-info='{\"proid\":\"".$row["proid"]."\",\"orderid\":\"".$row["orderid"]."\",\"proname\":".json_encode($row["proname"])."}'>修改</a> <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"program\",".$row["proid"].")'>删除</a></li>";
		echo "</ul>";
    }
show_tab(3);

$type_count_list_1 = program_total_count(0,1,$userid);//收入
$type_count_list_2 = program_total_count(0,2,$userid);//支出
echo "<script language=\"javascript\">";
foreach($type_count_list_1 as $row){
	echo "$('#income_".$row["proid"]."').text('".$row["total"]."');";
}
foreach($type_count_list_2 as $row){
	echo "$('#pay_".$row["proid"]."').text('".$row["total"]."');";
}
echo "</script>";
?>


<?php include_once("footer.php");?>
<!--// 添加编辑分类-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="addform" name="addform" method="post">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">添加</h4>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<label for="classname">项目名称</label>
					<input type="text" name="proname" class="form-control" id="proname" placeholder="请输入项目名称" required="请输入项目名称">
					<input name="proid" id="proid" type="hidden" value="" />
				</div>
				<div class="form-group">
					<label for="classname">排序</label>
					<input type="text" name="orderid" class="form-control" id="orderid" placeholder="排序ID" required="序号越大越靠前">
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
chushihua();
$("#btn_add").click(function(){
	chushihua();
	$("#myModalLabel").text("添加项目");
	$("#orderid").val("9999");
	$('#myModal').modal({backdrop:'static', keyboard:false});
});
$("#btn_submit").click(function(){
	var action = $(this).attr("date-info");
	saveclassify(action);
});

function saveclassify(action){
	posturl = "date.php?action="+action+"program";
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
			if(data.url != ""){location.href=data.url;}				
		},
		error : function() {
			$("#error_show").hide();
			console.log(result);
		}
	});
}
// 编辑
function edit(t){
	chushihua();
	var info = $(t).data('info');
	var proname = info.proname;
	var proid = info.proid;
	var orderid = info.orderid;
	$("#myModalLabel").text("编辑项目");
	$("#myModal").modal({backdrop:'static', keyboard:true});
	$("#proname").val(proname);
	$("#proid").val(proid);
	$("#orderid").val(orderid);
	$('#btn_submit').attr('date-info','modify');
}
</script>