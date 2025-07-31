<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['record_id'] == '' or $data['pickup_point'] == ''   or $data['drop_point'] == '' or $data['material_name'] == '' or $data['weight'] == ''  or $data['vehicle_id'] == '' or $data['amount'] == '' or $data['amt_type'] == '' or $data['visible_hours'] == '' or $data['total_amt'] == '' or $data['pick_lat'] == '' or $data['pick_lng'] == '' or $data['drop_lat'] == '' or $data['drop_lng'] == '' or $data['pick_state_id'] == '' or $data['drop_state_id'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
    
    $uid = strip_tags(mysqli_real_escape_string($service,$data['uid']));
	$pickup_point = mysqli_real_escape_string($service,$data['pickup_point']);
    $drop_point = mysqli_real_escape_string($service,$data['drop_point']);
	$material_name = mysqli_real_escape_string($service,$data['material_name']);
    $weight = mysqli_real_escape_string($service,$data['weight']);
    $description = mysqli_real_escape_string($service,$data['description']);
    $vehicle_id = $data['vehicle_id'];
	$amount = $data['amount'];
	$amt_type = $data['amt_type'];
	$visible_hours = $data['visible_hours'];
	$total_amt = $data['total_amt'];
	$pick_lat = $data['pick_lat'];
	$pick_lng = $data['pick_lng'];
	$drop_lat = $data['drop_lat'];
	$drop_lng = $data['drop_lng'];
	$pick_name = $data['pick_name'];
	$pick_mobile = $data['pick_mobile'];
	$drop_name = $data['drop_name'];
	$drop_mobile = $data['drop_mobile'];
	$pick_state_id = $data['pick_state_id'];
	$drop_state_id = $data['drop_state_id'];
	$record_id = $data['record_id'];
	$table="tbl_load";
	 $field = array('drop_mobile'=>$drop_mobile,'drop_name'=>$drop_name,'pick_mobile'=>$pick_mobile,'pick_name'=>$pick_name,'pickup_point'=>$pickup_point,'drop_point'=>$drop_point,'material_name'=>$material_name,'weight'=>$weight,'description'=>$description,'vehicle_id'=>$vehicle_id,'amount'=>$amount,'amt_type'=>$amt_type,'visible_hours'=>$visible_hours,'total_amt'=>$total_amt,'pick_lat'=>$pick_lat,'pick_lng'=>$pick_lng,'drop_lat'=>$drop_lat,'drop_lng'=>$drop_lng,'pick_state_id'=>$pick_state_id,'drop_state_id'=>$drop_state_id);
  $where = "where id=".$record_id." and uid=".$uid."";
 
$h = new FunctionQuery($service);
	  $check = $h->serviceupdateData_Api($field,$table,$where);
 $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Edit Load Successfully!!");
 }
echo  json_encode($returnArr);
?>