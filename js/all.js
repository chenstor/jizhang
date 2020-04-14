$(function(){
    $(".tab-title span").off("click").on("click",function(){
		var index = $(this).index();
		$(this).addClass("on").siblings().removeClass("on");
		var tab = $(this).attr("data-id");
		$("#contentbox .record_form").eq(index).show().siblings().hide();
    });
	var UrlParam = getUrlParam('action');	
	if(UrlParam == "income"){
		$("#income").show();
		$("#pay").hide();
		$(".tab-title span.green").addClass("on");
		$(".tab-title span.red").removeClass("on");
	}
	$("#btn_submit_save_edit").click(function(){
		$(this).addClass("disabled");
		saveEditRecord();
	});
	$(".tab-title a").off("click").on("click",function(){
		var val=$(this).attr("id");
		location.href="system.php?action="+val;
    });
	$("#year").change(function(){
		var select_year = $(this).val();
		location.href = "?year="+select_year;
	});
});

function isNull(str){
	if ( str == "" ) return true;
	var regu = "^[ ]+$";
	var re = new RegExp(regu);
	return re.test(str);
}

function chkUrlHttp(url){
	if(url.substr(0,7).toLowerCase() == "http://"){
		return true;
	}else if(url.substr(0,8).toLowerCase() == "https://"){
		return true;
	}else{
		return false;
	}
}

function chushihua_classify(){
	// 初始化
	$("#classname").val("");
	$("#classname").removeAttr('readonly');
	$("#classtype_div").show();
	$("#newclassname_div").hide();
	$("#classid").val("");
	$('#btn_submit_classify').attr('date-info','add');
	$("#classtype").find("option").attr("selected",false);
	$("#error_show_classify").html("");
}
function chushihua_program(){
	// 初始化
	$("#proname").val("");
	$("#proid").val("");
	$("#orderid").val("");
	$('#btn_submit_program').attr('date-info','add');
	$("#error_show_program").html("");
}
function chushihua_bank(){
	$("#bankid").val("");
	$("#bankname").val("");
	$("#bankaccount").val("");
	$("#balancemoney").val("0");
	$('#btn_submit_bank').attr('date-info','add');
	$("#error_show_bank").html("");
}
function chushihua_menu(){
	$("#m_name").val("");
	$("#m_url").val("");
	$("#orderid").val("9999");
	$('#btn_submit_menu').attr('date-info','add');
	$("#error_show_menu").html("");
}
function chushihua_role(){
	$("#role_name").val("");
	$("#role_description").val("");
	$('#btn_submit_role').attr('date-info','add');
	$("#error_show_role").html("");
}
function chushihua_user(){
	$("#username").val("");
	$("#userid").val("");
	$("#email").val("");
	$("#password").val("");
	$('#btn_submit_user').attr('date-info','add');
	$("#error_show_user").html("");
}
function subtraction(first,second){
	var results = first - second;
	results = results.toFixed(2);
	return results;
}
function GetUrlHash(){
  var query = window.location.hash;  
  return query;
}
//
// 获取url里面的参数(name)
// 使用方法 curPage = getUrlParam('page');
function getUrlParam(name) {
   var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
   var r = window.location.search.substr(1).match(reg);  //匹配目标参数
   if (r != null)
	   return unescape(r[2]);
   return null; //返回参数值
}
function isEmpty(obj){
	return (typeof obj === 'undefined' || obj === null || obj === "");
}
// 添加记录
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
			var data = '';
			if(result != ''){
				data = eval("("+result+")");
			}
			$(error_name).html(data.error_msg);
			if(data.url != ""){
				location.href = data.url;
			}else{
				$("#submit_"+type).removeClass("disabled");
			}		
		},
		error : function() {
			$(error_name).hide();
			$("#submit_"+type).removeClass("disabled");
		}
	});
}
// 删除记录
function deleterecordAll(form){
	if($("input[type='checkbox']").is(':checked')==false){
		alert("请选择需要删除的记录");
		return false;
	}
	var r=confirm("确定删除这些记录？");
	if(r==true){
		$.ajax({
			type:"POST",
			dataType: "json",
			url:"date.php?action=deleterecordAll",
			data: $("#del_all").serialize(),
			success:function(result){
				var data = '';
				if(result != ''){
					data = eval("("+result+")");
				}
				alert(data.error_msg);
			},
			error:function(){
				console.log(result);
			}
		});
	}
}
// 编辑记录
function editRecord(t,openid){
	$("#error_show").html("");//初始化
	//$("#bankid_3").find("option").remove();
	var info = $(t).data('info');	
	var money = info.money;
	var remark = info.remark;
	var bankid = info.bankid;
	var proid = info.proid;
	var zhifu = info.zhifu;
	var classname = info.classname;
	var addtime = info.addtime;
	var id = info.id;
	$("#"+openid).modal({backdrop:'static', keyboard:true});
	$("#edit-money").val(money);
	$("#edit-remark").val(remark);
	$("#old-bank-id").val(bankid);
	$("#old-money").val(money);//修改前的金额
	$("#edit-zhifu").val(zhifu);
	$("#bankid_3").find("option[value='"+bankid+"']").attr("selected",true);
	$("#edit-proid").find("option[value='"+proid+"']").attr("selected",true);
	$("#edit-time").val(addtime);
	$("#edit-classtype").val(classname);
	$("#edit-id").val(id);
}
// 保存记录
function saveEditRecord(){
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "date.php?action=saverecord",
		data: $('#edit-form').serialize(),
		success: function (result) {
			$("#error_show").show();
			var data = '';
			if(result != ''){
				data = eval("("+result+")");
			}
			$('#error_show').html(data.error_msg);
			if(data.code == "1"){
				window.location.reload();
			}else{
				$("#btn_submit").removeClass("disabled");
				$("#btn_submit_save_edit").removeClass("disabled");
			}		
		},
		error : function() {
			$("#error_show").hide();
			console.log(result);
		}
	});
}
// 删除记录
function delRecord(type,t){
	if(type=="record"){
		geturl = "date.php?action=deleterecord&id="+t+"";
	}else if(type=="classify"){
		geturl = "date.php?action=deleteclassify&classid="+t+"";
	}else if(type=="bank"){
		geturl = "date.php?action=deletebank&bankid="+t+"";
	}else if(type=="program"){
		geturl = "date.php?action=deleteprogram&proid="+t+"";
	}else if(type=="menu"){
		geturl = "date.php?action=deletemenu&m_id="+t+"";
	}else if(type=="role"){
		geturl = "date.php?action=deleterole&role_id="+t+"";
	}else if(type=="user"){
		geturl = "date.php?action=deleteuser&uid="+t+"";
	}
	Ewin.confirm({ message: "确认要删除该记录吗？" }).on(function (e) {
		if(!e){return;}
		$.ajax({
			type:"get",
			url:geturl,
			async:true,
			success:function(data){
				alert(data);
				window.location.reload();
			}
		});
	});
}

function edit_menu(t){
	chushihua_menu();
	var info = $(t).data('info');
	var m_f_id = info.m_f_id;
	var m_type = info.m_type;
	$("#myModalLabel").text("编辑信息");
	$("#myModal_menu").modal({backdrop:'static', keyboard:true});
	$("#m_id").val(info.m_id);
	$("#m_url").val(info.m_url);
	$("#orderid").val(info.orderid);
	$("#m_name").val(info.m_name);
	$("#m_f_id").find("option[value='"+m_f_id+"']").attr("selected",true);
	$("#m_type").find("option[value='"+m_type+"']").attr("selected",true);
	$('#btn_submit_menu').attr('date-info','modify');
}
function edit_role(t){
	chushihua_role();
	var info = $(t).data('info');
	$("#myModalLabel").text("编辑信息");
	$("#myModal_role").modal({backdrop:'static', keyboard:true});
	$("#role_id").val(info.role_id);
	$("#role_name").val(info.role_name);
	$("#role_description").val(info.role_description);
	$('#btn_submit_role').attr('date-info','modify');
}
function edit_user(t){
	chushihua_user();
	var info = $(t).data('info');
	$("#myModalLabel_user").text("编辑信息");
	$("#myModal_user").modal({backdrop:'static', keyboard:true});
	$("#userid").val(info.uid);
	$("#pro_id").find("option[value='"+info.pro_id+"']").attr("selected",true);
	$("#role_id").find("option[value='"+info.role_id+"']").attr("selected",true);
	$("#isadmin").find("option[value='"+info.isadmin+"']").attr("selected",true);
	$("#username").val(info.username);
	$("#email").val(info.email);
	$("#password_div").hide();
	$('#btn_submit_user').attr('date-info','modify');
}
function edit_rolelist(t){
	var info = $(t).data('info');
	$("#myModalLabel_rolelist").text("编辑信息");
	$("#myModal_rolelist").modal({backdrop:'static', keyboard:true});	
	$("#role_id_rolelist").val(info.role_id);
	var str = info.role_menu_id;
    $(str.split(",")).each(function (i,dom){
		$("input[name='rolelist_id'][value='"+dom+"']").prop("checked",true);
    });
	$('#btn_submit_rolelist').attr('date-info','modify');
}
//添加编辑
function send_post_form(action,sendtype){	
	posturl = "date.php?action="+action+sendtype;
	form_id = '#add_form_'+sendtype;
	$.ajax({
		type: "POST",
		dataType: "json",
		url: posturl,
		data: $(form_id).serializeArray(),
		success: function (result) {
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
		},
		error:function(){
			$("#error_show_"+sendtype).hide();
			console.log(result);
		}
	});
}
function canBackGo(url){
	if(url.indexOf("?")>0){
		document.location.href = url + "&backUrl=" + encodeURIComponent(document.location.href);
	}else{
		document.location.href = url + "?backUrl=" + encodeURIComponent(document.location.href);
	}
}