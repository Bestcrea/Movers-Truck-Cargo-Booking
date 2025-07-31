<?php
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = (float)$lon1 - (float)$lon2;
  $dist = sin(deg2rad((float)$lat1)) * sin(deg2rad((float)$lat2)) +  cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) * cos(deg2rad((float)$theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
      return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
  } else {
      return $miles;
  }
}
if($data['owner_id'] == '' or $data['status'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$owner_id = $data['owner_id'];
	$status = $data['status'];
	if($status == 'Current')
	{
	$list = $service->query("select id,vehicle_id,pickup_point,drop_point,amount,amt_type,total_amt,post_date,load_status,pick_state_id,drop_state_id,visible_hours,pick_lat,pick_lng,drop_lat,drop_lng from tbl_load where lorry_owner_id=".$owner_id." and load_status!='Completed' and load_status!='Cancelled' and load_type='POST_LOAD' order by id desc");
	}
	else 
	{
		$list = $service->query("select id,vehicle_id,pickup_point,drop_point,amount,amt_type,total_amt,post_date,load_status,pick_state_id,drop_state_id,visible_hours,pick_lat,pick_lng,drop_lat,drop_lng from tbl_load where lorry_owner_id=".$owner_id." and load_status='Completed' and load_type='POST_LOAD' order by id desc");
	}
	$k = array();
	$p = array();
	while($row = $list->fetch_assoc())
	{
		$vdata = $service->query("select title,img from tbl_vehicle where id=".$row['vehicle_id']."")->fetch_assoc();
		$pdata = $service->query("select title from tbl_state where id=".$row['pick_state_id']."")->fetch_assoc();
		$ddata = $service->query("select title from tbl_state where id=".$row['drop_state_id']."")->fetch_assoc();
		$k['id'] = $row['id'];
		$k['vehicle_title'] = $vdata['title'];
		$k['vehicle_img'] = $vdata['img'];
		$k['pickup_point'] = $row['pickup_point'];
		$k['drop_point'] = $row['drop_point'];
		$k['pickup_state'] = $pdata['title'];
		$k['drop_state'] = $ddata['title'];
		$k['amount'] = $row['amount'];
		$k['amt_type'] = $row['amt_type'];
		$k['total_amt'] = $row['total_amt'];
		$k['post_date'] = $row['post_date'];
		$k['load_status'] = $row['load_status'];
		$k['load_distance'] = number_format((float)distance($row['pick_lat'], $row['pick_lng'],$row['drop_lat'], $row['drop_lng'], "K"), 2, '.', '').' KM';
		$d1= new DateTime($row['post_date']); // first date
		$timestamp = date("Y-m-d H:i:s");
$d2= new DateTime($timestamp); // second date
$interval= $d1->diff($d2);
$k['svisible_hours'] = $row['visible_hours'] - (($interval->days * 24) + $interval->h);
		$p[] = $k;
	}
	$returnArr = array("LoadHistoryData"=>$p,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Load History Get  Successfully!!");
}
echo  json_encode($returnArr);
?>