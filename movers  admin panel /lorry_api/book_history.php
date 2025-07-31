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
if($data['owner_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$owner_id = $data['owner_id'];
	$status = $data['status'];
	if($status == 'Current')
	{
	$list = $service->query("select id,lorry_id,weight,uid,pick_lat,pick_lng,drop_lat,drop_lng,pickup_point,drop_point from tbl_load where lorry_owner_id=".$owner_id." and load_status!='Completed' and load_status!='Cancelled' and load_type='FIND_LORRY' order by id desc");
	}
	else 
	{
		$list = $service->query("select id,lorry_id,weight,uid,pick_lat,pick_lng,drop_lat,drop_lng,pickup_point,drop_point from tbl_load where lorry_owner_id=".$owner_id." and (load_status='Completed' or load_status ='Cancelled') and load_type='FIND_LORRY' order by id desc");
	}
	$k = array();
	$p = array();
	while($row = $list->fetch_assoc())
	{
		$lorrydata = $service->query("select * from tbl_lorry where id=".$row['lorry_id']."")->fetch_assoc();
	$vdata = $service->query("select title,img from tbl_vehicle where id=".$lorrydata['vehicle_id']."")->fetch_assoc();
		$odata = $service->query("select name,pro_pic from  tbl_user where id=".$row['uid']."")->fetch_assoc();
		$k['lorry_id'] = $row['id'];
		$k['vehicle_id'] = $lorrydata['vehicle_id'];
		$k['lorry_owner_id'] = $row['uid'];
		$k['lorry_owner_title'] = $odata['name'];
		$k['lorry_owner_img'] = empty($odata['pro_pic']) ? 'images/duser.png': $odata['pro_pic'];
		$k['lorry_img'] = $vdata['img'];
		$k['lorry_title'] = $vdata['title'];
		$k['weight'] = $row['weight'];
		$k['curr_location'] = $lorrydata['curr_location'];
		$k['pickup_point'] = $row['pickup_point'];
		$k['drop_point'] = $row['drop_point'];
		$routes = explode(',',$lorrydata['routes']);
		$k['routes_count'] = count($routes);
		$k['rc_verify'] = $lorrydata['is_verify'];
		$k['lorry_no'] = $lorrydata['lorry_no'];
		$rdata_rest = $service->query("SELECT sum(total_lrate)/count(*) as rate_rest FROM tbl_load where uid=".$row['uid']." and load_status='Completed' and total_lrate !=0")->fetch_assoc();
	    $k['review'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		$k['load_distance'] = number_format((float)distance($row['pick_lat'], $row['pick_lng'],$row['drop_lat'], $row['drop_lng'], "K"), 2, '.', '').' KM';
		$p[] = $k;
	}
	$returnArr = array("BookHistory"=>$p,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Book Lorry History Get  Successfully!!");
}
echo  json_encode($returnArr);
?>