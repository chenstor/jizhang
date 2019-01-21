<?php include_once("header.php");?>
<table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor='#B3B3B3' class='table table-striped table-bordered'>
    <tr>
		<th width="80">
			<select name="year" id="year">
				<?php
				$first_year = user_first_year($userid);
				$get_year = get("year",$this_year);
				for ($y = $first_year; $y <= $this_year; $y++){
					if($get_year == $y){
						echo "<option value='$y' selected>$y</option>";
					}else{
						echo "<option value='$y'>$y</option>";
					}					
				}				
				?>
			</select>
		</th>
		<?php for($m=1;$m<=12;$m++){?>
		<th class="center"><?php echo showmonth($m);?>月</th>
		<?php }?>
		</tr>
	</tr>
	<?php
	$type_count_num = 0; //分类总数
	$typelist = show_type("",$userid);
	foreach($typelist as $myrow){
		if($myrow['classtype']==1){
			$fontcolor = "green";
		}else{
			$fontcolor = "red";
		}
		$type_count_num++;
		// 取分类统计数据
		$type_count_data = "";
		$type_count_list = total_count($myrow['classid'],$get_year,$userid);
		for($b=1;$b<=12;$b++){
			$month_num = "0.00";
			foreach($type_count_list as $countrow){
				if($b == $countrow['month']){
					$month_num = $countrow['total'];
				}else{
					$month_num = "0.00";
				}
			}
			$type_count_data .= "{'month':'".$b."','count':'".$month_num."'},";
		}
		$type_count_data = substr($type_count_data,0,-1);
		$type_count_data = "[".$type_count_data."]";
				
	?>
	<tr class="<?php echo $fontcolor;?>" id="itlu_data_<?php echo $type_count_num;?>" data-info="<?php echo $type_count_data;?>">
		<td><?php echo $myrow['classname'];?></td>
		<?php for($n=0;$n<12;$n++){?>
		<td align="center" id="show_<?php echo $type_count_num;?>_<?php echo $n;?>">0</td>
		<?php }?>
	</tr>
	<?php }?>
	<tr class="green_all">
		<td>月收入</td>
		<?php for($n=0;$n<12;$n++){?>
		<td align="center" id="green_all_<?php echo $n;?>">0</td>
		<?php }?>
	</tr>
	<tr class="red_all">
		<td>月支出</td>
		<?php for($n=0;$n<12;$n++){?>
		<td align="center" id="red_all_<?php echo $n;?>">0</td>
		<?php }?>
	</tr>
	<tr>
		<td>月剩余</td>
		<?php for($n=0;$n<12;$n++){?>
		<td align="center" id="result_<?php echo $n;?>">0</td>
		<?php }?>
	</tr>
</table><input id="type_count" value="<?php echo $type_count_num;?>" type="hidden" />

<script language="javascript" type="text/javascript">
$(function(){
	$("#year").change(function(){
		var select_year = $(this).val();
		location.href = "?year="+select_year;
	});
});
var date_total = new Array();
var date_total_show = new Array();
var typenums = $("#type_count").val();
console.log(typenums);
for(var k=1; k<=typenums; k++){
	date_total[k] = $("#itlu_data_"+k).attr("data-info");
	date_total_show[k] = eval(date_total[k]);
	for(var l = 0;l<12;l++){
		$("#show_"+k+"_"+l).text(date_total_show[k][l].count);		
	}
}
//================
var len_green_all = $("table > tbody > tr.green").length;
for(var g1=0; g1<12; g1++){
	var sum = 0;
	for(var b=0; b<len_green_all; b++){
		var sum1 = $("table > tbody > tr.green:nth-child("+(b+2)+")").children("td").eq(g1+1).text();
		sum = sum + Number(sum1);
	}
	$("#green_all_"+g1).text(sum.toFixed(2));	
}
//================
var len_red_all = $("table > tbody > tr.red").length;
for(var g1=0; g1<12; g1++){
	var sum = 0;
	for(var b=0; b<len_red_all; b++){
		var sum1 = $("table > tbody > tr.red").children("td").eq(13*b+g1+1).text();
		sum = sum + Number(sum1);
		//console.log(sum);
	}
	$("#red_all_"+g1).text(sum.toFixed(2));	
}
//================
for(var g2=0; g2<12; g2++){
	$("#result_"+g2).text(subtraction($("#green_all_"+g2).text(),$("#red_all_"+g2).text()));	
}
</script>
<?php include_once("footer.php");?>