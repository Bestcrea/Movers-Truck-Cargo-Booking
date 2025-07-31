<?php
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
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
if($data['owner_id'] == '' or $data['pick_state_id'] == '' or $data['drop_state_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$owner_id = $data['owner_id'];
	$pick_state_id = $data['pick_state_id'];
	$drop_state_id = $data['drop_state_id'];
	if($pick_state_id == 0 and $drop_state_id == 0)
	{
		$vehicle = $service->query("select * from tbl_vehicle where status=1");
	$op = array();
	$vom =array();
	while($pop = $vehicle->fetch_assoc())
	{
		$op['id'] = $pop['id'];
		$op['title'] = $pop['title'];
		$op['min_weight'] = $pop['min_weight'];
		$op['max_weight'] = $pop['max_weight'];
		$vehicle_id = $pop['id'];
		$op['total_lorry'] = 1;
		
	$list = $service->query("select id,uid,vehicle_id,pick_lat,pick_lng,drop_lat,drop_lng,pickup_point,drop_point,amount,amt_type,total_amt,post_date,load_status,pick_state_id,drop_state_id,weight,material_name from tbl_load where load_status ='Pending' and vehicle_id=".$vehicle_id."  and lorry_id=0 and load_type='POST_LOAD' and load_status='Pending' order by id desc");
	$k = array();
	$p = array();
	
	while($row = $list->fetch_assoc())
	{
		$vdata = $service->query("select title,img from tbl_vehicle where id=".$row['vehicle_id']."")->fetch_assoc();
		$pdata = $service->query("select title from tbl_state where id=".$row['pick_state_id']."")->fetch_assoc();
		$ddata = $service->query("select title from tbl_state where id=".$row['drop_state_id']."")->fetch_assoc();
		$owner = $service->query("select name,pro_pic from tbl_user where id=".$row['uid']."")->fetch_assoc();
		$k['id'] = $row['id'];
		$k['uid'] = $row['uid'];
		$k['vehicle_title'] = $vdata['title'];
		$k['vehicle_img'] = $vdata['img'];
		$k['pickup_point'] = $row['pickup_point'];
		$k['drop_point'] = $row['drop_point'];
		$k['pickup_state'] = $pdata['title'];
		$k['drop_state'] = $ddata['title'];
		$k['amount'] = $row['amount'];
		$k['weight'] = $row['weight'];
		$k['amt_type'] = $row['amt_type'];
		$k['total_amt'] = $row['total_amt'];
		$k['post_date'] = $row['post_date'];
		$k['load_status'] = $row['load_status'];
		$k['owner_name'] = $owner['name'];
		$k['owner_img'] = empty($owner['pro_pic']) ? 'images/duser.png': $owner['pro_pic'];
		$rdata_rest = $service->query("SELECT sum(total_lrate)/count(*) as rate_rest FROM tbl_load where uid=".$row['uid']." and load_status='Completed' and total_lrate !=0")->fetch_assoc();
		$k['owner_rating'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		$k['material_name'] = $row['material_name'];
		$k['load_distance'] = number_format((float)distance($row['pick_lat'], $row['pick_lng'],$row['drop_lat'], $row['drop_lng'], "K"), 2, '.', '').' KM';
		$bid = $service->query("select status,amount,amt_type,total_amt from tbl_load_response where load_id=".$row['id']." and owner_id=".$owner_id."")->fetch_assoc();
		$comment_id = $bid['status'] ?? '';
		 $amount = $bid['amount'] ?? '';
		 $amt_type = $bid['amt_type'] ?? '';
		 $total_amt = $bid['total_amt'] ?? '';
		 if($comment_id == '')
		 {
			 $is_bid = 0;
			 $k['bid_amount'] = '';
			 $k['bid_amount_type'] = '';
			 $k['bid_total_amt'] = '';
		 }
		 else if($comment_id == 0)
		 {
			 $is_bid = 1;
			 $k['bid_amount'] = $amount;
			 $k['bid_amount_type'] = $amt_type;
			 $k['bid_amount_total_amt'] = $total_amt;
		 }
		 else if($comment_id == 1)
		 {
			 $is_bid = 2;
			 $k['bid_amount'] = '';
			 $k['bid_amount_type'] = '';
			 $k['bid_total_amt'] = '';
		 }
		 else 
		 {
			 $is_bid = 3;
			 $k['bid_amount'] = '';
			 $k['bid_amount_type'] = '';
			 $k['bid_total_amt'] = '';
		 }
		 
		$k['is_bid'] = $is_bid;
		if($is_bid == 1)
		{
			$p[] = $k;
		}
		
	}
	$op['loaddata'] = $p;
	if(empty($p))
	{
	}
	else 
	{
	$vom[] = $op;
	}
	}
	$returnArr = array("FindLoadData"=>$vom,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Load History Get  Successfully!!");
	}
	else 
	{
	$vehicle = $service->query("select * from tbl_vehicle where status=1");
	$op = array();
	$vom =array();
	while($pop = $vehicle->fetch_assoc())
	{
		$op['id'] = $pop['id'];
		$op['title'] = $pop['title'];
		$op['min_weight'] = $pop['min_weight'];
		$op['max_weight'] = $pop['max_weight'];
		$vehicle_id = $pop['id'];
		$op['total_lorry'] = $service->query("select * from tbl_lorry where vehicle_id=".$vehicle_id." and owner_id=".$owner_id." and  routes REGEXP  '\\\b".$pick_state_id."\\\b' and routes REGEXP  '\\\b".$drop_state_id."\\\b'")->num_rows;
		
	$list = $service->query("select id,uid,vehicle_id,pick_lat,pick_lng,drop_lat,drop_lng,pickup_point,drop_point,amount,amt_type,total_amt,post_date,load_status,pick_state_id,drop_state_id,weight,material_name from tbl_load where load_status ='Pending' and vehicle_id=".$vehicle_id." and pick_state_id=".$pick_state_id." and drop_state_id=".$drop_state_id." and lorry_id=0 and load_type='POST_LOAD' order by id desc");
	$k = array();
	$p = array();
	if($op['total_lorry'] != 0)
	{
	while($row = $list->fetch_assoc())
	{
		$vdata = $service->query("select title,img from tbl_vehicle where id=".$row['vehicle_id']."")->fetch_assoc();
		$pdata = $service->query("select title from tbl_state where id=".$row['pick_state_id']."")->fetch_assoc();
		$ddata = $service->query("select title from tbl_state where id=".$row['drop_state_id']."")->fetch_assoc();
		$owner = $service->query("select name,pro_pic from tbl_user where id=".$row['uid']."")->fetch_assoc();
		$k['id'] = $row['id'];
		$k['uid'] = $row['uid'];
		$k['vehicle_title'] = $vdata['title'];
		$k['vehicle_img'] = $vdata['img'];
		$k['pickup_point'] = $row['pickup_point'];
		$k['drop_point'] = $row['drop_point'];
		$k['pickup_state'] = $pdata['title'];
		$k['drop_state'] = $ddata['title'];
		$k['amount'] = $row['amount'];
		$k['weight'] = $row['weight'];
		$k['amt_type'] = $row['amt_type'];
		$k['total_amt'] = $row['total_amt'];
		$k['post_date'] = $row['post_date'];
		$k['load_status'] = $row['load_status'];
		$k['owner_name'] = $owner['name'];
		$k['owner_img'] = empty($owner['pro_pic']) ? 'images/duser.png': $owner['pro_pic'];
		$k['material_name'] = $row['material_name'];
		$k['load_distance'] = number_format((float)distance($row['pick_lat'], $row['pick_lng'],$row['drop_lat'], $row['drop_lng'], "K"), 2, '.', '').' KM';
		$rdata_rest = $service->query("SELECT sum(total_lrate)/count(*) as rate_rest FROM tbl_load where uid=".$row['uid']." and load_status='Completed' and total_lrate !=0")->fetch_assoc();
		$k['owner_rating'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		
		$bid = $service->query("select status,amount,amt_type,total_amt from tbl_load_response where load_id=".$row['id']." and owner_id=".$owner_id."")->fetch_assoc();
		$comment_id = $bid['status'] ?? '';
		 $amount = $bid['amount'] ?? '';
		 $amt_type = $bid['amt_type'] ?? '';
		 $total_amt = $bid['total_amt'] ?? '';
		 if($comment_id == '')
		 {
			 $is_bid = 0;
			 $k['bid_amount'] = '';
			 $k['bid_amount_type'] = '';
			 $k['bid_total_amt'] = '';
		 }
		 else if($comment_id == 0)
		 {
			 $is_bid = 1;
			 $k['bid_amount'] = $amount;
			 $k['bid_amount_type'] = $amt_type;
			 $k['bid_amount_total_amt'] = $total_amt;
		 }
		 else if($comment_id == 1)
		 {
			 $is_bid = 2;
			 $k['bid_amount'] = '';
			 $k['bid_amount_type'] = '';
			 $k['bid_total_amt'] = '';
		 }
		 else 
		 {
			 $is_bid = 3;
			 $k['bid_amount'] = '';
			 $k['bid_amount_type'] = '';
			 $k['bid_total_amt'] = '';
		 }
		 
		$k['is_bid'] = $is_bid;
			$p[] = $k;
		
		
	}
	$op['loaddata'] = $p;
	}
	else 
	{
		$op['loaddata'] = $p;
	}
	$vom[] = $op;
	}
	$returnArr = array("FindLoadData"=>$vom,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Load History Get  Successfully!!");
	}
}
echo  json_encode($returnArr);
?>