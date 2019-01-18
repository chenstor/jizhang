<?php
/**
* 安全过滤输入[jb]
*/
function check_str($string, $isurl = false){
	$string = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','',$string); //去掉控制字符
	$string = str_replace(array("\0","%00","\r"),'',$string); //\0表示ASCII 0x00的字符，通常作为字符串结束标志；这三个都是可能有害字符
	empty($isurl) && $string = preg_replace("/&(?!(#[0-9]+|[a-z]+);)/si",'&',$string); //HTML里面可以用&#xxx;来对一些字符进行编码，比如 (空格), ? Unicode字符等，A(?!B) 表示的是A后面不是B,所以作者想保留 ?类似的 HTML编码字符，去掉其他的问题字符
	$string = str_replace(array("%3C",'<'),'<',$string); //ascii的'<'转成'<';
	$string = str_replace(array("%3E",'>'),'>',$string);
	$string = str_replace(array('"',"'","\t",' '),array('“','‘',' ',' '),$string);
	return trim($string);
}

/**
* 安全过滤类-过滤javascript,css,iframes,object等不安全参数 过滤级别高
* @param  string $value 需要过滤的值
* @return string
*/
function fliter_script($value) {
	$value = preg_replace("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i","&111n\\2",$value);
	$value = preg_replace("/(.*?)<\/script>/si","",$value);
	$value = preg_replace("/(.*?)<\/iframe>/si","",$value);
	$value = preg_replace ("//iesU", '', $value);
	return $value;
}

/**
* 安全过滤类-过滤HTML标签
* @param  string $value 需要过滤的值
* @return string
*/
function fliter_html($value) {
	if(function_exists('htmlspecialchars')) return htmlspecialchars($value);
	return str_replace(array("&", '"', "'", "<", ">"), array("&", "\"", "'", "<", ">"), $value);
}

/**
* 安全过滤类-对进入的数据加下划线 防止SQL注入
* @param  string $value 需要过滤的值
* @return string
*/
function fliter_sql($value) {
	$sql = array("select", 'insert', "update", "delete", "\'", "\/\*","\.\.\/", "\.\/", "union", "into", "load_file", "outfile");
	$sql_re = array("","","","","","","","","","","","");
	return str_replace($sql, $sql_re, $value);
}

/**
* 安全过滤类-通用数据过滤
* @param string $value 需要过滤的变量
* @return string|array
*/
function fliter_escape($value) {
	if(is_array($value)){
		foreach($value as $k => $v){
			$value[$k] = fliter_str($v);
		}
	}else{
		$value = fliter_str($value);
	}
	return $value;
}

/**
* 安全过滤类-字符串过滤 过滤特殊有危害字符
* @param  string $value 需要过滤的值
* @return string
*/
function fliter_str($value) {
	$badstr = array("\0", "%00", "\r", '&', ' ', '"', "'", "<", ">", "   ", "%3C", "%3E");
	$newstr = array("", "", "", '&', ' ', '"', "", "<", ">", "", "<", ">");
	$value  = str_replace($badstr, $newstr, $value);
	$value  = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $value);
	return $value;
}

/**
* 私有路径安全转化
* @param string $fileName
* @return string
*/
function filter_dir($fileName) {
	$tmpname = strtolower($fileName);
	$temp = array(':/',"\0", "..");
	if (str_replace($temp, '', $tmpname) !== $tmpname) {
		return false;
	}
	return $fileName;
}

/**
* 过滤目录
* @param string $path
* @return array
*/
function filter_path($path) {
	$path = str_replace(array("'",'#','=','`','$','%','&',';'), '', $path);
	return rtrim(preg_replace('/(\/){2,}|(\\\){1,}/', '/', $path), '/');
}

/**
* 过滤PHP标签
* @param string $string
* @return string
*/
function filter_phptag($string) {
	return str_replace(array(''), array('<?', '?>'), $string);
}

/**
* 安全过滤类-返回函数
* @param  string $value 需要过滤的值
* @return string
*/
function str_out($value) {
	$badstr = array("<", ">", "%3C", "%3E");
	$newstr = array("<", ">", "<", ">");
	$value  = str_replace($newstr, $badstr, $value);
	return stripslashes($value); //下划线
}
?>