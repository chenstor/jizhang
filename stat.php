<?php
include_once("header.php");
?>
<table width='100%' border='0' align='left' cellpadding='5' cellspacing='1' bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <th bgcolor='#EBEBEB' width="12%">统计</th>
        <th bgcolor='#EBEBEB' width="22%">今天</th>
        <th bgcolor='#EBEBEB' width="22%">本周</th>
        <th bgcolor='#EBEBEB' width="22%">本月</th>
        <th bgcolor='#EBEBEB' width="22%">本年</th>
    </tr>
    <tr class="red"><td align='left' bgcolor='#FFFFFF'>支出</td>
        <td align='left' bgcolor='#FFFFFF' id="pay_day">0</td>
        <td align='left' bgcolor='#FFFFFF' id="pay_week">0</td>
        <td align='left' bgcolor='#FFFFFF' id="pay_month">0</td>
        <td align='left' bgcolor='#FFFFFF' id="pay_year">0</td>
    </tr>
    <tr class="green"><td align='left' bgcolor='#FFFFFF'>收入</td>
        <td align='left' bgcolor='#FFFFFF' id="income_day">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_week">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_month">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_year">0</td>
    </tr>
    <tr><td align='left' bgcolor='#FFFFFF'>剩余</td>
        <td align='left' bgcolor='#FFFFFF' id="income_pay_day">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_pay_week">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_pay_month">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_pay_year">0</td>
    </tr>
</table>

<table width='100%' border='0' align='left' cellpadding='5' cellspacing='1' bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
        <th width="12%" bgcolor='#EBEBEB'>统计</th>
        <th width="22%" bgcolor='#EBEBEB'>昨天</th>
        <th width="22%" bgcolor='#EBEBEB'>上周</th>
        <th width="22%" bgcolor='#EBEBEB'>上月</th>
        <th width="22%" bgcolor='#EBEBEB'>去年</th>
    </tr>
    <tr class="red"><td align='left' bgcolor='#FFFFFF'>支出</td>
        <td align='left' bgcolor='#FFFFFF' id="pay_yesterday">0</td>
        <td align='left' bgcolor='#FFFFFF' id="pay_lastweek">0</td>
        <td align='left' bgcolor='#FFFFFF' id="pay_lastmonth">0</td>
        <td align='left' bgcolor='#FFFFFF' id="pay_lastyear">0</td>
    </tr>
    <tr class="green"><td align='left' bgcolor='#FFFFFF'>收入</td>
        <td align='left' bgcolor='#FFFFFF' id="income_yesterday">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_lastweek">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_lastmonth">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_lastyear">0</td>
    </tr>
    <tr><td align='left' bgcolor='#FFFFFF'>剩余</td>
        <td align='left' bgcolor='#FFFFFF' id="income_pay_yesterday">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_pay_lastweek">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_pay_lastmonth">0</td>
        <td align='left' bgcolor='#FFFFFF' id="income_pay_lastyear">0</td>
    </tr>
</table>

<table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class='table table-striped table-bordered'>
    <tr>
        <td id="stat"></td>
    </tr>
</table>

<script language="javascript">
	$("#stat").html("去年1月至今共收入<strong class='green'><?php echo state_day($last_year_start,$today,$userid,1);?></strong>，共支出<strong class='red'><?php echo state_day($last_year_start,$today,$userid,2);?></strong>");
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