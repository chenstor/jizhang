<?php
include("header.php");
?>
<div class="table stat"><div class="itlu-title"><?php if(sys_role_check($userinfo['isadmin'],$userinfo['role_id'],"12")){?><span class="pull-right"><button type="button" class="btn btn-primary btn-xs" id="btn_add">添加分类</button></span><?php }?>分类管理</div></div>

<?php
show_tab(5);
for($i=2;$i>=1;$i--){
	if($i==2){
		$fontcolor = "red";
		$word = "支出";		
	}else{
		$fontcolor = "green";
		$word = "收入";
	}
	$pay_type_list = show_type($i);
	foreach($pay_type_list as $row){
		echo "<ul class=\"table-row\">";
		echo "<li class='".$fontcolor."'>".$row["classname"]."</li>";
		echo "<li class='".$fontcolor."'>".$word."</li><li>";
		if(sys_role_check($userinfo['isadmin'],$userinfo['role_id'],"13")){
			echo "<a class='btn btn-primary btn-xs' href='javascript:' onclick='edit(this)' data-info='{\"classid\":\"".$row["classid"]."\",\"classtype\":\"".$i."\",\"classname\":".json_encode($row["classname"])."}'>修改</a>";
		}
		if(sys_role_check($userinfo['isadmin'],$userinfo['role_id'],"14")){
			echo " <a class='btn btn-success btn-xs' href='javascript:' onclick='change(this)' data-info='{\"classid\":\"".$row["classid"]."\",\"classtype\":\"".$i."\",\"classname\":".json_encode($row["classname"])."}'>转移</a>";
		}
		if(sys_role_check($userinfo['isadmin'],$userinfo['role_id'],"15")){
			echo " <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"classify\",".$row["classid"].")'>删除</a>";
		}
		echo "</li></ul>";
    }
}
show_tab(3);
?>
<?php include("footer.php");?>
<!--// 添加编辑分类-->
<div class="modal fade" id="myModal_classify" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="add_form_classify" name="add_form_classify" method="post">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">分类管理</h4>
			</div>
			<div class="modal-body">				
				<div class="form-group">
					<label for="classname">分类名称</label>
					<input type="text" name="classname" class="form-control" id="classname" placeholder="分类名称" required="请输入分类名称">
					<input name="classid" id="classid" type="hidden" value="" />
				</div>
				<div class="form-group" id="classtype_div">
					<label for="classtype">所属类型</label>
					<select name="classtype" id="classtype" class="form-control">
						<option value="2">支出</option>
                        <option value="1">收入</option>						
                    </select>
				</div>
				<div class="form-group" id="newclassname_div" style="display:none;">
					<label for="newclassid">目标分类</label>
					<select name="newclassid" id="newclassid" class="form-control">
						<option value='0'>请选择目标分类</option>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<div id="error_show_classify" class="footer-tips"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="btn_submit_classify" date-info="add" class="btn btn-primary">保存</button>
			</div>
		</div>
		</form>
	</div>
</div>
<script type="text/javascript">
chushihua_classify();
$("#btn_add").click(function(){
	chushihua_classify();
	$("#myModalLabel").text("添加分类");
	$('#myModal_classify').modal({backdrop:'static', keyboard:false});
});
$("#btn_submit_classify").click(function(){
	$("#error_show_classify").html("提交中...");
	$(this).addClass("disabled");
	var action = $(this).attr("date-info");
	send_post_form(action,"classify");
});
// 编辑分类
function edit(t){
	chushihua_classify();
	var info = $(t).data('info');
	var classname = info.classname;
	var classid = info.classid;
	var classtype = info.classtype;
	$("#myModalLabel").text("编辑分类");
	$("#myModal_classify").modal({backdrop:'static', keyboard:true});
	$("#classname").val(classname);
	$("#classid").val(classid);
	$("#classtype_div").hide();
	//$("#classtype").find("option").attr("selected",false);
	$("#classtype").find("option[value="+classtype+"]").attr("selected",true);
	$('#btn_submit_classify').attr('date-info','modify');
}
// 转移分类
function change(t){
	chushihua_classify();
	$("#newclassid").find("option").not(":first").remove();
	//$("#newclassid").find("option").remove();//清除所有选项
	var info = $(t).data('info');
	var classname = info.classname;
	var classid = info.classid;
	var classtype = info.classtype;	
	$.ajax({
		type:"get",
		url:"date.php?action=getclassify&classtype="+classtype+"&classid="+classid+"",
		async:true,
		success:function(data){
			console.log(data)
			$("#newclassid").append(data);
		}
	});
	$("#myModalLabel").text("转移分类");
	$("#myModal_classify").modal({backdrop:'static', keyboard:true});
	$("#classname").val(classname);
	$("#classname").attr('readonly','true');
	$("#classid").val(classid);
	$("#classtype_div").hide();
	$("#newclassname_div").show();
	$('#btn_submit_classify').attr('date-info','change');
}
</script>