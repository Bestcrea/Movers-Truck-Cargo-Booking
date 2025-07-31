<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == ''  or $data['pickup_point'] == ''   or $data['drop_point'] == '' or $data['material_name'] == '' or $data['weight'] == ''  or $data['vehicle_id'] == '' or $data['amount'] == '' or $data['amt_type'] == '' or $data['visible_hours'] == '' or $data['total_amt'] == '' or $data['pick_lat'] == '' or $data['pick_lng'] == '' or $data['drop_lat'] == '' or $data['drop_lng'] == '' or $data['pick_state_id'] == '' or $data['drop_state_id'] == '')
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
	$pick_state_id = $data['pick_state_id'];
	$lorry_id = $data['lorry_id'];
	$lorry_owner_id = $data['lorry_owner_id'];
	$drop_state_id = $data['drop_state_id'];
	$timestamp = date("Y-m-d H:i:s");
	
	$lorry =  $service->query("select weight from tbl_lorry where id=".$lorry_id."")->fetch_assoc();
	$lorry_book =  $service->query("select sum(weight) as total_load_book from tbl_load where lorry_id=".$lorry_id." and load_status!='Completed' and load_status!='Cancelled'")->fetch_assoc();
	
	if($lorry_book['total_load_book'] == '')
	{
	$book_load = $lorry['weight'];
	}
	else 
	{
		if(($lorry['weight'] - $lorry_book['total_load_book']) < 0)
		{
		$book_load = 0;	
		}
		else 
		{
			$book_load = $lorry['weight'] - $lorry_book['total_load_book'];
		}
	}
	
	if($weight > $book_load)
	{
		if($book_load == 0)
		{
			$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Lorry Full Booked!!");
		}
		else 
		{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"The lorry capacity is ".$book_load." not more than so you can post the load accordingly!!");
		}
	}
	else 
	{
		
	$table="tbl_load";
  $field_values=array("uid","pickup_point","drop_point","material_name","weight","description","vehicle_id","amount","amt_type","visible_hours","total_amt","pick_lat","pick_lng","drop_lat","drop_lng","pick_state_id","drop_state_id","post_date","load_type","lorry_id","lorry_owner_id");
  $data_values=array("$uid","$pickup_point","$drop_point","$material_name","$weight","$description","$vehicle_id","$amount","$amt_type","$visible_hours","$total_amt","$pick_lat","$pick_lng","$drop_lat","$drop_lng","$pick_state_id","$drop_state_id","$timestamp",'FIND_LORRY',"$lorry_id","$lorry_owner_id");
   $h = new FunctionQuery($service);
	  $check = $h->serviceinsertdata_Api_Id($field_values,$data_values,$table);

  $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Booking Request Send Successfully!");
  
  $content = array(
       "en" => 'New load Request Received.'
   );
$heading = array(
   "en" => "Check New Direct Load Request Send!!"
);

$fields = array(
'app_id' => $set['d_key'],
'included_segments' =>  array("Active Users"),
'filters' => array(array('field' => 'tag', 'key' => 'owner_id', 'relation' => '=', 'value' => $lorry_owner_id)),
'contents' => $content,
'headings' => $heading,
'android_channel_id'=>$set['d_cust']
);
$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['d_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");

$descriptions = 'Check New Direct Load Request#'.$check.'!!';

	   $table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$lorry_owner_id","$timestamp","$descriptions");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
}
}

echo json_encode($returnArr);