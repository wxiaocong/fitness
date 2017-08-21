<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 计算两点地理坐标之间的距离
 * @param  Decimal $longitude1 起点经度
 * @param  Decimal $latitude1  起点纬度
 * @param  Decimal $longitude2 终点经度 
 * @param  Decimal $latitude2  终点纬度
 * @param  Int     $unit       单位 1:米 2:公里
 * @param  Int     $decimal    精度 保留小数位数
 * @return Decimal
 */
function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=2){

    $EARTH_RADIUS = 6370.996; // 地球半径系数
    $PI = 3.1415926;

    $radLat1 = $latitude1 * $PI / 180.0;
    $radLat2 = $latitude2 * $PI / 180.0;

    $radLng1 = $longitude1 * $PI / 180.0;
    $radLng2 = $longitude2 * $PI /180.0;

    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;

    $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $distance = $distance * $EARTH_RADIUS * 1000;

    if($unit==2){
        $distance = $distance / 1000;
    }
    return round($distance, $decimal);
}


function get_check_pwd($pwd,$salt='fjeixcmgjel&%$f8')
{
	return md5($pwd.$salt);
// 	return md5(md5($pwd).$salt);
}

//生成随机数
function getRandom($param){
	$str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$key = "";
	for($i=0;$i<$param;$i++)
	{
		$key .= $str{mt_rand(0,32)};    //生成php随机数
	}
	return $key;
}

/**
 *判断时间格式是否合法
 */
function is_datetime($datetime) {
	if ($datetime == date ( 'Y-m-d H:i:s', strtotime ( $datetime ) )) {
		return true;
	} else {
		return false;
	}
}

/**
 *判断日期格式是否合法
 */
function is_date($date) {
	if ($date == date ( 'Y-m-d', strtotime ( $date ) )) {
		return true;
	} else {
		return false;
	}
}

//删除空格和回车
function trimall($str){
	$qian=array(" ","　","\t","\n","\r");
	return str_replace($qian, '', $str);
}
	 
//创建订单号
function createOrderSn(){
	list($usec, $sec) = explode(" ", microtime());
	return date('YmdHis').$usec*pow(10, 8).rand(10,99);
}

//二维数组比较
function array_diff_assoc2_deep($array1, $array2) {
	if(empty($array1)){
		$result['del'] = $array2;
		return $result;
	}
	if(empty($array2)){
		$result['add'] = $array1;
		return $result;
	}
	$arr1 = $arr2 = array();
	$re_array1 = $array1;
	$re_array2 = $array2;
	
	foreach ($array2 as $v2)
		$arr2[] = md5(serialize($v2));
	foreach ($array1 as $k=>$v1)
	{
		$arr1[] = $tmp = md5(serialize($v1));
		if(in_array($tmp, $arr2)){
			unset($re_array1[$k]);	//已拥有
			$key = array_search($array1[$k], $array2);
			unset($re_array2[$key]);
		}
	}
	$result['add'] = $re_array1;
	$result['del'] = $re_array2;
	return $result;
}

if ( ! function_exists( 'exif_imagetype' ) ) {
	function exif_imagetype ( $filename ) {
		if ( ( list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
			return $type;
		}
		return false;
	}
}

/* End of file functions_helper.php */
/* Location: ./system/helpers/functions_helper.php */