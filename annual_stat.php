<?php include("header.php");
$first_year = user_first_year();
$get_year = get("year",$this_year);
?>
<script type="text/javascript" src="js/echarts.min.js"></script>
<div class="table stat"><div class="itlu-title"><select name="year" id="year">
				<?php				
				for ($y = $first_year; $y <= $this_year; $y++){
					if($get_year == $y){
						echo "<option value='$y' selected>$y</option>";
					}else{
						echo "<option value='$y'>$y</option>";
					}					
				}				
				?>
			</select></div></div>

		<table width="100%" border="0" cellpadding="5" cellspacing="1" class='table table-striped table-bordered'>
			<tr><td style="background:#fff"><div id="itlu_main_show" style="width:100%;height:400px"></div></td></tr>
		</table>
		<table width="100%" border="0" cellpadding="5" cellspacing="1" class='table table-striped table-bordered'>
			<tr><td style="background:#fff"><div id="itlu_type_pay" style="width:100%;height:400px"></div></td></tr>
		</table>
		<table width="100%" border="0" cellpadding="5" cellspacing="1" class='table table-striped table-bordered'>
			<tr><td style="background:#fff"><div id="itlu_type_income" style="width:100%;height:400px"></div></td></tr>
		</table>
<script type="text/javascript">
        var myChart = echarts.init(document.getElementById('itlu_main_show')); 
		var myChart_2 = echarts.init(document.getElementById('itlu_type_pay')); 
		var myChart_1 = echarts.init(document.getElementById('itlu_type_income')); 
		option = {
			title: {text: '年度统计'},
			tooltip: {trigger: 'axis'},
			legend: {data: ['支出', '收入', '结余']},
			grid: {
				left: '3%',
				right: '4%',
				bottom: '3%',
				containLabel: true
			},
			toolbox: {feature: {saveAsImage: {}}},
			xAxis: {
				type: 'category',
				boundaryGap: false,
				data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
			},
			yAxis: {type: 'value'},
			series: [
				{
					name: '支出',
					type: 'line',
					itemStyle : { 
						normal : { 
							color:'#ff0000',
							lineStyle:{color:'#ff0000'}
						} 
					},
					<?php
					$pay_count_data = "";
					$pay_count_list = total_count(0,$get_year,$userinfo['pro_id'],$userinfo['isadmin'],2);
					for($b=1;$b<=12;$b++){
						$month_pay_num = "0";
						foreach($pay_count_list as $countrow){
							if($b == $countrow['month']){
								$month_pay_num = $countrow['total'];
								continue;
							}
						}
						$pay_count_data .= $month_pay_num.",";
					}
					$pay_count_data = substr($pay_count_data,0,-1);
					?>
					data: [<?php echo $pay_count_data;?>]
				},
				{
					name: '收入',
					type: 'line',
					itemStyle : { 
						normal : { 
							color:'#5cb85c',
							lineStyle:{color:'#5cb85c'}
						} 
					},
					<?php
					$income_count_data = "";
					$income_count_list = total_count(0,$get_year,$userinfo['pro_id'],$userinfo['isadmin'],1);
					for($b=1;$b<=12;$b++){
						$month_income_num = "0";
						foreach($income_count_list as $countrow){
							if($b == $countrow['month']){
								$month_income_num = $countrow['total'];
								continue;
							}
						}
						$income_count_data .= $month_income_num.",";
					}
					$income_count_data = substr($income_count_data,0,-1);
					?>
					data: [<?php echo $income_count_data;?>]
				},
				{
					name: '结余',
					type: 'line',
					itemStyle : { 
						normal : { 
							color:'#0371C5', //改变折线点的颜色
							lineStyle:{ 
								color:'#0371C5' //改变折线颜色
							}
						} 
					},
					<?php
					$pay_list = explode(",",$pay_count_data);
					$income_list = explode(",",$income_count_data);
					$res_list = "";
					for($index=0;$index<12;$index++){
						$res = $income_list[$index] - $pay_list[$index] ;
						$res_list .= $res.",";
					}
					$res_list = substr($res_list,0,-1);
					?>
					data: [<?php echo $res_list;?>]
				}
			]
		};
		<?php
		$typelist_show = '';
		$itlu_div = '';
		$type_d = show_type(2);
		foreach($type_d as $myrow){
			$typelist_show .= "'".$myrow['classname']."',";
			$itlu_div .= "{name:'".$myrow['classname']."', type: 'bar', stack: '总量', data:[".month_type_count($myrow['classid'],$get_year,$userinfo['pro_id'],$userinfo['isadmin'])."]},\n";
		}
		$typelist_show = substr($typelist_show,0,-1);
		$itlu_div = substr($itlu_div,0,-2);
		?>
		option_2 = {
			title: {text: '支出分类统计'},
			tooltip: {
				trigger: 'axis',
				axisPointer: {
					type: 'shadow'
				}
			},
			legend: {
				data: [<?php echo $typelist_show;?>]
			},
			grid: {
				left: '3%',
				right: '4%',
				bottom: '3%',
				containLabel: true
			},
			xAxis: [
				{
					type: 'category',
					data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
				}
			],
			yAxis: [{type: 'value'}],
			series: [
				<?php echo $itlu_div;?>
			]
		};
		<?php
		$typelist_show = '';
		$itlu_div = '';
		$type_d = show_type(1);
		foreach($type_d as $myrow){
			$typelist_show .= "'".$myrow['classname']."',";
			$itlu_div .= "{name:'".$myrow['classname']."', type: 'bar', stack: '总量', data:[".month_type_count($myrow['classid'],$get_year,$userinfo['pro_id'],$userinfo['isadmin'])."]},\n";
		}
		$typelist_show = substr($typelist_show,0,-1);
		$itlu_div = substr($itlu_div,0,-2);
		?>
		option_1 = {
			title: {text: '收入分类统计'},
			tooltip: {
				trigger: 'axis',
				axisPointer: {
					type: 'shadow'
				}
			},
			legend: {
				data: [<?php echo $typelist_show;?>]
			},
			grid: {
				left: '3%',
				right: '4%',
				bottom: '3%',
				containLabel: true
			},
			xAxis: [
				{
					type: 'category',
					data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
				}
			],
			yAxis: [{type: 'value'}],
			series: [
				<?php echo $itlu_div;?>
			]
		};
        myChart.setOption(option);
		myChart_2.setOption(option_2);
		myChart_1.setOption(option_1);
    </script>
<?php include("footer.php");?>