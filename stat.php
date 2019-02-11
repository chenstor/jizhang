<?php
include_once("header.php");
?>
<div class="table">
	<div class="table-header-group">  
		<ul class="table-row">  
			<li class="w12p">统计</li><li class="w22p">今天</li><li class="w22p">本周</li><li class="w22p">本月</li><li class="w22p">本年</li>
		</ul>  
	</div>
	<div class="table-row-group">  
		<ul class="table-row red"><li>支出</li><li id="pay_day">0</li><li id="pay_week">0</li><li id="pay_month">0</li><li id="pay_year">0</li></ul>
		<ul class="table-row green"><li>收入</li><li id="income_day">0</li><li id="income_week">0</li><li id="income_month">0</li><li id="income_year">0</li></ul> 
		<ul class="table-row"><li>剩余</li><li id="income_pay_day">0</li><li id="income_pay_week">0</li><li id="income_pay_month">0</li><li id="income_pay_year">0</li></ul> 
    </div>
</div>

<div class="table">
	<div class="table-header-group">  
		<ul class="table-row">  
			<li class="w12p">统计</li><li class="w22p">昨天</li><li class="w22p">上周</li><li class="w22p">上月</li><li class="w22p">去年</li>
		</ul>  
	</div>
	<div class="table-row-group">  
		<ul class="table-row red"><li>支出</li><li id="pay_yesterday">0</li><li id="pay_lastweek">0</li><li id="pay_lastmonth">0</li><li id="pay_lastyear">0</li></ul>
		<ul class="table-row green"><li>收入</li><li id="income_yesterday">0</li><li id="income_lastweek">0</li><li id="income_lastmonth">0</li><li id="income_lastyear">0</li></ul> 
		<ul class="table-row"><li>剩余</li><li id="income_pay_yesterday">0</li><li id="income_pay_lastweek">0</li><li id="income_pay_lastmonth">0</li><li id="income_pay_lastyear">0</li></ul> 
    </div>
</div>

<div class="table stat"><div id="stat"></div></div>

<script language="javascript">
	$("#stat").html("<?php echo date("Y年m月",$userinfo['regtime']);?>至今共收入<strong class='green'><?php echo state_day(date("Y-m-d",$userinfo['regtime']),$today,$userid,1);?></strong>，共支出<strong class='red'><?php echo state_day(date("Y-m-d",$userinfo['regtime']),$today,$userid,2);?></strong>");
	$("#pay_day").text("<?php echo state_day($today,$today,$userid,2);?>");
	$("#pay_week").text("<?php echo state_day(get_week_day(1),$today,$userid,2);?>");
	$("#pay_month").text("<?php echo state_day($this_month_firstday,$today,$userid,2);?>");
	$("#pay_year").text("<?php echo state_day($this_year_firstday,$today,$userid,2);?>");
	
	$("#income_day").text("<?php echo state_day($today,$today,$userid,1);?>");
	$("#income_week").text("<?php echo state_day(get_week_day(1),$today,$userid,1);?>");
	$("#income_month").text("<?php echo state_day($this_month_firstday,$today,$userid,1);?>");
	$("#income_year").text("<?php echo state_day($this_year_firstday,$today,$userid,1);?>");

	$("#income_pay_day").text(subtraction($("#income_day").text(),$("#pay_day").text()));
	$("#income_pay_week").text(subtraction($("#income_week").text(),$("#pay_week").text()));
	$("#income_pay_month").text(subtraction($("#income_month").text(),$("#pay_month").text()));
	$("#income_pay_year").text(subtraction($("#income_year").text(),$("#pay_year").text()));	
	//昨天统计数据
	$("#pay_yesterday").text("<?php echo state_day($yesterday,$yesterday,$userid,2);?>");
	$("#pay_lastweek").text("<?php echo state_day($last_week_start,$last_week_end,$userid,2);?>");
	$("#pay_lastmonth").text("<?php echo state_day($last_month_start,$last_month_end,$userid,2);?>");
	$("#pay_lastyear").text("<?php echo state_day($last_year_start,$last_year_end,$userid,2);?>");
	
	$("#income_yesterday").text("<?php echo state_day($yesterday,$yesterday,$userid,1);?>");
	$("#income_lastweek").text("<?php echo state_day($last_week_start,$last_week_end,$userid,1);?>");
	$("#income_lastmonth").text("<?php echo state_day($last_month_start,$last_month_end,$userid,1);?>");
	$("#income_lastyear").text("<?php echo state_day($last_year_start,$last_year_end,$userid,1);?>");

	$("#income_pay_yesterday").text(subtraction($("#income_yesterday").text(),$("#pay_yesterday").text()));
	$("#income_pay_lastweek").text(subtraction($("#income_lastweek").text(),$("#pay_lastweek").text()));
	$("#income_pay_lastmonth").text(subtraction($("#income_lastmonth").text(),$("#pay_lastmonth").text()));
	$("#income_pay_lastyear").text(subtraction($("#income_lastyear").text(),$("#pay_lastyear").text()));
</script>
<?php include_once("footer.php");?>