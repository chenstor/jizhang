<?php include_once("header.php");?>
<table align="left" width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr><td bgcolor="#EBEBEB">导出导入</td></tr>
    <tr><td bgcolor="#FFFFFF">
			<div class="form-group block mt20">
			<div class="col-sm-10">
				<input type="button" class="btn btn-primary" id="exportCSV" value="导出全部记账CSV" onClick="window.location.href='date.php?action=export'">
			</div>
			</div>
			<hr />
            <form id="import_form" class="block" action="date.php?action=import" method="post" enctype="multipart/form-data" onsubmit="return checkpost();">
				<div class="import_tips">
				<strong class="red">批量导入数据</strong><br />
				请选择本地的CSV文件，注意按照上述的格式填写
				</div>
                <div class="form-group">				   
				   <div class="col-sm-8">
						<label class="pull-right"><input type="submit" class="btn btn-danger ml5" value="导入CSV"></label>
						<div class="input-group">
						<input id='location' class="form-control" onclick="$('#i-file').click();">
						<label class="input-group-btn">
							<input type="button" id="i-check" value="浏览文件" class="btn btn-success" onclick="$('#i-file').click();">
						</label>						
						</div>						
				   </div>
				   <input type="file" name="file" id='i-file' accept=".csv" onchange="$('#location').val($('#i-file').val());" style="display: none">
				</div>
            </form>
			<div class="form-group block mt20">
			<div class="col-sm-8">
				用文本复制以下内容保存为csv后缀名<br />
				<span class="red">特别注意：账户导入只能识别默认账户，其他账户不处理</span><br />
				或excel导出csv格式文件，格式必须如下：<br /><br />
				收支,分类,账户,金额,时间,备注<br />
				支出,车费,默认账户,35,2015-11-30 05:15,打的<br />
				收入,工资,默认账户,22,2015-11-30 05:16,工作<br />
			</div>
			</div>
        </form>
    </td></tr>
</table>
<script type="text/javascript">
function checkpost() {
	var selectfile = $("#location").val();
	if(selectfile == ""){alert("请选择需要上传的文件！");return false;}
}
</script>
<?php include_once("footer.php");?>