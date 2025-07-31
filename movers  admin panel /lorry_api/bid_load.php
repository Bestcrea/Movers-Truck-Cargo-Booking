<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['owner_id'] == ''  or $data['load_id'] == ''   or $data['lorry_id'] == '' or $data['amount'] == '' or $data['amt_type'] == ''  or $data['total_amt'] == '' or $data['is_immediate'] == '' or $data['total_load'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	
	 
	$owner_id = strip_tags(mysqli_real_escape_string($service,$data['owner_id']));
	$load_id = mysqli_real_escape_string($service,$data['load_id']);
    $lorry_id = mysqli_real_escape_string($service,$data['lorry_id']);
	$amount = mysqli_real_escape_string($service,$data['amount']);
    $amt_type = mysqli_real_escape_string($service,$data['amt_type']);
    $total_amt = mysqli_real_escape_string($service,$data['total_amt']);
	$description = mysqli_real_escape_string($service,$data['description']);
    $is_immediate = $data['is_immediate'];
	$total_load = $data['total_load'];
	$check_exist = $service->query("select * from tbl_load where id=".$load_id."")->num_rows;
	if($check_exist != 0)
	{
	$lorry =  $service->query("select weight from tbl_lorry where id=".$lorry_id."")->fetch_assoc();
	$lorry_book =  $service->query("select sum(weight) as total_load_book from tbl_load where lorry_id=".$lorry_id." and load_status!='Completed' and load_status!='Cancelled'")->fetch_assoc();
	$check_bid = $service->query("select * from tbl_load_response where owner_id=".$owner_id." and lorry_id=".$lorry_id." and load_id=".$load_id."")->num_rows;
	$check_request = $service->query("select * from tbl_load where lorry_id=".$lorry_id." and lorry_owner_id=".$owner_id." and load_status!='Completed' and load_type='FIND_LORRY' and load_status!='Cancelled'")->num_rows; 
	if($check_bid != 0)
	{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Already Bid On This Load!!");
	}
	else if($check_request != 0)
	{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"First Response Your Direct Leads!!");
	}
	else 
	{
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
	
	if($total_load > $book_load)
	{
		if($book_load == 0)
		{
			$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Lorry Full Booked!!");
		}
		else 
		{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"The lorry capacity is ".$book_load." not more than Load Weight. so you can't Bid the load accordingly!!");
		}
	}
	else 
	{
	$check_weight = 
	$table="tbl_load_response";
  $field_values=array("owner_id","load_id","lorry_id","amount","amt_type","total_amt","is_immediate","total_load","description");
  $data_values=array("$owner_id","$load_id","$lorry_id","$amount","$amt_type","$total_amt","$is_immediate","$total_load","$description");
   $h = new FunctionQuery($service);
	  $check = $h->serviceinsertdata_Api_Id($field_values,$data_values,$table);

  $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Bid Sent Successfully!");
  
  
  $getloadownerid = $service->query("select uid from tbl_load where id=".$load_id."")->fetch_assoc();
  $uid = $getloadownerid['uid'];
  $content = array(
       "en" => 'Bid On Your Load Received.'
   );
$heading = array(
   "en" => "Check Your Load ID#".$load_id."!!"
);

$fields = array(
'app_id' => $set['one_key'],
'included_segments' =>  array("Active Users"),
'filters' => array(array('field' => 'tag', 'key' => 'userid', 'relation' => '=', 'value' => $uid)),
'contents' => $content,
'headings' => $heading,
'android_channel_id'=>$set['one_cust']
);
$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['one_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");

$descriptions = 'Bid On Your Load Received.';
$title = "Check Your Load ID#".$load_id."!!";
	   $table="tbl_notification";
  $field_values=array("uid","datetime","description","title");
  $data_values=array("$uid","$timestamp","$descriptions","$title");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
	}
	}
	}
	else 
	{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Load Not Found Refresh Screen To Find Another Load Thanks!!");
	}
}
echo json_encode($returnArr);