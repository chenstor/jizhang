<?php
include_once("header.php");
?>
<div class="table stat"><div class="itlu-title"><span class="pull-right"><button type="button" class="btn btn-primary btn-xs" id="btn_add">添加分类</button></span>分类管理</div></div>

<?php
for($i=1;$i<=2;$i++){
	if($i==1){
		$fontcolor = "green";
		$word = "收入";
	}else{
		$fontcolor = "red";
		$word = "支出";
	}
	show_tab(5);
	$pay_type_list = show_type($i,$userid);
	foreach($pay_type_list as $row){
		echo "<ul class=\"table-row\">";
			echo "<li class='".$fontcolor."'>".$row["classname"]."</li>";
			echo "<li class='".$fontcolor."'>".$word."</li>";
			echo "<li><a class='btn btn-primary btn-xs' href='javascript:' onclick='edit(this)' data-info='{\"classid\":\"".$row["classid"]."\",\"classtype\":\"".$i."\",\"classname\":".json_encode($row["classname"])."}'>修改</a> <a class='btn btn-success btn-xs' href='javascript:' onclick='change(this)' data-info='{\"classid\":\"".$row["classid"]."\",\"classtype\":\"".$i."\",\"classname\":".json_encode($row["classname"])."}'>转移</a> <a class='btn btn-danger btn-xs' href='javascript:' onclick='delRecord(\"classify\",".$row["classid"].")'>删除</a></li>";
		echo "</ul>";
    }
	show_tab(3);
}?>


<?php include_once("footer.php");?>
<!--// 添加编辑分类-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form id="addform" name="addform" method="post">
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
					<div id="error_show" style="color:#f00"></div>
				</div>
				<div class="form-group" id="classtype_div">
					<label for="classtype">所属类型</label>
					<select name="classtype" id="classtype" class="form-control">
                        <option value="1">收入</option>
						<option value="2">支出</option>
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
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" id="btn_submit" date-info="save" class="btn btn-primary">保存</button>
			</div>
		</div>
		</form>
	</div>
</div>
<script type="text/javascript">
//初始化
chushihua();
$("#btn_add").click(function(){
	//初始化
	chushihua();
	$("#myModalLabel").text("添加分类");
	$('#myModal').modal({backdrop:'static', keyboard:false});
});
$("#btn_submit").click(function(){
	var action = $(this).attr("date-info");
	saveclassify(action);
});

function saveclassify(type){
	if(type=="save"){
		posturl = "date.php?action=addclassify";
	}else if(type=="modify"){
		posturl = "date.php?action=modifyclassify";
	}else{
		posturl = "date.php?action=changeclassify";
	}
	$.ajax({
		type: "POST",
		dataType: "json",
		url: posturl ,//url
		data: $('#addform').serialize(),
		success: function (result) {
			$("#error_show").show();
			//console.log(result);//打印服务端返回的数据(调试用)
			var data = '';
			if(result != ''){
				data = eval("("+result+")");    //将返回的json数据进行解析，并赋给data
			}
			//if(data.code == "1"){tipsword = "成功";}
			$('#error_show').html(data.error_msg);    //在#text中输出
			if(data.url != ""){location.href=data.url;}				
		},
		error : function() {
			$("#error_show").hide();
			console.log(result);
			//alert("保存异常！");
		}
	});
}
// 编辑分类
function edit(t){
	//初始化
	chushihua();
	var info = $(t).data('info');
	var classname = info.classname;
	var classid = info.classid;
	var classtype = info.classtype;
	$("#myModalLabel").text("编辑分类");
	$("#myModal").modal({backdrop:'static', keyboard:true});
	$("#classname").val(classname);
	$("#classid").val(classid);
	$("#classtype_div").hide();
	//$("#classtype").find("option").attr("selected",false);
	//$("#classtype").find("option[value="+classtype+"]").attr("selected",true);
	$('#btn_submit').attr('date-info','modify');
}
// 转移分类
function change(t){
	//初始化
	chushihua();
	$("#newclassid").find("option").not(":first").remove();//清除所有选项
	//$("#newclassid").find("option").remove();//清除所有选项
	var info = $(t).data('info');
	var classname = info.classname;
	var classid = info.classid;
	var classtype = info.classtype;	
	$.ajax({
		type:"get",
		url:"date.php?action=getclassify&classtype="+classtype+"&classid="+classid+"", //需要获取的页面内容
		async:true,
		success:function(data){
			console.log(data)
			$("#newclassid").append(data);
		}
	});
	$("#myModalLabel").text("转移分类");
	$("#myModal").modal({backdrop:'static', keyboard:true});
	$("#classname").val(classname);
	$("#classname").attr('readonly','true');//屏蔽编辑
	$("#classid").val(classid);
	$("#classtype_div").hide();
	$("#newclassname_div").show();
	$('#btn_submit').attr('date-info','change');
}
</script>