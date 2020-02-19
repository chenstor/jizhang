<?php include_once("header.php");
if($userinfo['isadmin']=="0" and ViewAllData=="2"){
	echo "您不是管理员，无权限查看此功能，请联系管理员！";
	include_once("footer.php");
	exit();
}
//============搜索参数处理================
$s_classid = get('classid','all');
$s_starttime = get('starttime');
$s_endtime = get('endtime',$today);//默认今天
$s_startmoney = get('startmoney');
$s_endmoney = get('endmoney');
$s_remark = get('remark');
$s_bankid = get('bankid');
$s_page = get('page','1');

$pageurl = "all_show.php";
?>
<?php	
	show_tab(6);
		$Prolist = itlu_page_search($userid,20,$s_page,$s_classid,$s_starttime,$s_endtime,$s_startmoney,$s_endmoney,$s_remark,$s_bankid,1);
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
				echo "<li>".bankname($row['bankid'],$row['jiid'],"默认账户")."</li>";
				echo "<li class='t_a_r'>".$row['acmoney']."</li>";
				if(isMobile()){
					echo "<li>".date("m-d",$row['actime'])."</li>";
				}else{
					echo "<li>".date("Y-m-d H:i",$row['actime'])."</li>";
				}
				echo "<li>".$row['acremark']."</li>";
				echo "<li>".recordname($row['jiid'],"默认账户")."</li>";
			echo "</ul>";
			$thiscount ++ ;
		}
	show_tab(3);	
?>
	<?php 
	//显示页码
	$allcount = record_num_query($userid,$s_classid,$s_starttime,$s_endtime,$s_startmoney,$s_endmoney,$s_remark,$s_bankid,1);
	$pages = ceil($allcount/20);	
	if($pages > 1){?>
	<div class="page"><?php getPageHtml($s_page,$pages,$pageurl."?",$thiscount,$allcount);?></div>
	<?php }?>
<?php include_once("footer.php");?>