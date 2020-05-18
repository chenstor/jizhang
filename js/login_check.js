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

function GetUrlHash(){
  var query = window.location.hash;  
  return query;
}
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
function canBackGo(url){
	if(url.indexOf("?")>0){
		document.location.href = url + "&backUrl=" + encodeURIComponent(document.location.href);
	}else{
		document.location.href = url + "?backUrl=" + encodeURIComponent(document.location.href);
	}
}
//登录检查
function login_check(formname,type){
	var user_name = $("#user_name").val();
	var user_pass = $("#user_pass").val();
	var user_email = $("#user_email").val();
	if(type=="login"){
		if(isEmpty(user_name)){
			alert("用户名不能为空！");
			$("#user_name").focus();
			return false;
		}
		if(isEmpty(user_pass)){
			alert("密码不能为空！");
			$("#user_pass").focus();
			return false;
		}
		if($("#user_pass").val().length < 6){
			alert("密码至少要6位数！");
			$("#user_pass").focus();
			return false;
		}
	}
	if(type=="getpassword"){
		if(isEmpty(user_email)){
			alert("邮箱不能为空！");
			$("#user_email").focus();
			return false;
		}
	}
	if(type=="register"){
		if(isEmpty(user_name) || isEmpty(user_email) || isEmpty(user_pass)){
			alert("用户名、邮箱、密码不能为空啊！");
			$("#user_name").focus();
			return false;
		}
		if($("#user_email").val().length > 30){
			alert("邮箱最多只能30个字符！");
			$("#user_email").focus();
			return false;
		}
		if($("#user_pass").val().length < 6){
			alert("密码至少要6位数！");
			$("#user_pass").focus();
			return false;
		}
	}
	submitdate(formname,type);
	return false;
}
// 提交数据
function submitdate(formname,type){
	posturl = "login_chk.php?action="+type;
	$.ajax({
		type: "POST",
		dataType: "json",
		url: posturl,
		data: $(formname).serialize(),
		success: function (result) {
			$("#login_error").show();
			tipsword = "错误";
			var data = '';
			if(result != ''){
				data = eval("("+result+")");
			}
			if(data.code == "1"){tipsword = "成功";}
			$('#login_error').html("<strong>"+tipsword+"</strong>：" + data.error_msg);
			if(data.url != ""){location.href=data.url;}			
		},
		error : function(result) {
			$("#login_error").hide();
			console.log(result);
		}
	});
}