<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
if($data['owner_id'] == '' or $data['load_id'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
    $uid = strip_tags(mysqli_real_escape_string($service,$data['owner_id']));
	$load_id = strip_tags(mysqli_real_escape_string($service,$data['load_id']));
   $check_load = $service->query("select * from tbl_load where id=".$load_id."")->num_rows;
if($check_load != 0)
{	
$check_load_require = $service->query("select vehicle_id,pick_state_id,drop_state_id from tbl_load where id=".$load_id."")->fetch_assoc();    
$check = $service->query("select * from tbl_lorry where owner_id=".$uid." and vehicle_id=".$check_load_require['vehicle_id']." and  routes REGEXP  '\\\b".$check_load_require['pick_state_id']."\\\b' and routes REGEXP  '\\\b".$check_load_require['drop_state_id']."\\\b'");
$op = array();
$arr = array();
while($row = $check->fetch_assoc())
{
	$vdata = $service->query("select title,img from tbl_vehicle where id=".$row['vehicle_id']."")->fetch_assoc();
		$op['id'] = $row['id'];
		$op['lorry_img'] = $vdata['img'];
		$op['lorry_title'] = $vdata['title'];
		$op['weight'] = $row['weight'];
		$op['rc_verify'] = $row['is_verify'];
		$op['lorry_no'] = $row['lorry_no'];
    $arr[] = $op;
}
$returnArr = array("BidLorryData"=>$arr,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Lorry List Get Successfully!!");
}
 else 
 {
	 $returnArr = array("BidLorryData"=>[],"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Load Not Found!!");
 }
}
echo json_encode($returnArr);