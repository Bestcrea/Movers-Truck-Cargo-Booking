<?php
require dirname(dirname(__FILE__)) . '/inc/config.php';
require dirname(dirname(__FILE__)) . '/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';

$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');



function siteURL()
{
    $protocol   = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    return $protocol . $domainName;
}

$status   = $data['status'];
$load_id  = $data['load_id'];
$owner_id = $data['owner_id'];
if ($status == '' or $load_id == '' or $owner_id == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went wrong  try again !"
    );
} else {
    
    
    if ($status == 1) {
        
            $table     = "tbl_load";
            $field     = array(
                'is_accept' => 1,
			    'flow_id'=>1
            );
            $where     = "where id=" . $load_id . " and lorry_owner_id=" . $owner_id . "";
            $h         = new FunctionQuery($service);
            $check     = $h->serviceupdateData_Api($field, $table, $where);
            $returnArr = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Load Accepted Successfully!!!!!"
            );
			
			$getloadownerid = $service->query("select uid from tbl_load where id=".$load_id." and lorry_owner_id=" . $owner_id . "")->fetch_assoc();
  $uid = $getloadownerid['uid'];
  $content = array(
       "en" => 'Direct Load Accepted By Lorry Owner.'
   );
$heading = array(
   "en" => "Check Your Load ID#".$load_id." Accepted By LorryOwner!!"
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

$descriptions = "Check Your Load ID#".$load_id." Accepted By LorryOwner!!";
$title = "Direct Load Accepted By Lorry Owner.";
	   $table="tbl_notification";
  $field_values=array("uid","datetime","description","title");
  $data_values=array("$uid","$timestamp","$descriptions","$title");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
    } else if ($status == 2){
		$comment_reject = $data['comment_reject'];
        $table     = "tbl_load";
        $field     = array(
            'load_status' => 'Cancelled',
            'lorry_owner_id' => $owner_id,
			'comment_reject'=>$comment_reject,
			    'flow_id'=>2
        );
        $where     = "where id=" . $load_id . " and lorry_owner_id=" . $owner_id . "";
        $h         = new FunctionQuery($service);
        $check     = $h->serviceupdateData_Api($field, $table, $where);
        $returnArr = array(
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Load Request Rejected Successfully!!!!!"
        );
		
		$getloadownerid = $service->query("select uid from tbl_load where id=".$load_id." and lorry_owner_id=" . $owner_id . "")->fetch_assoc();
  $uid = $getloadownerid['uid'];
  $content = array(
       "en" => 'Direct Load Rejected By Lorry Owner.'
   );
$heading = array(
   "en" => "Check Your Load ID#".$load_id." Rejected By LorryOwner!!"
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

$descriptions = "Check Your Load ID#".$load_id." Rejected By LorryOwner!!";
$title = "Direct Load Rejected By Lorry Owner.";
	   $table="tbl_notification";
  $field_values=array("uid","datetime","description","title");
  $data_values=array("$uid","$timestamp","$descriptions","$title");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
    }
	else
	{
		$offer_description = $data['offer_description'];
		$offer_price = $data['offer_price'];
		$offer_type = $data['offer_type'];
		$offer_total = $data['offer_total'];
		$table     = "tbl_load";
        $field     = array(
            'offer_description' => $offer_description,
            'offer_price' => $offer_price,
			'offer_by'=>'LorryOwner',
			'offer_type'=>$offer_type,
			'offer_total'=>$offer_total,
			'flow_id'=>3
        );
        $where     = "where id=" . $load_id . " and lorry_owner_id=" . $owner_id . "";
        $h         = new FunctionQuery($service);
        $check     = $h->serviceupdateData_Api($field, $table, $where);
        $returnArr = array(
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Offer Price Successfully!!!!!"
        );
		
		$getloadownerid = $service->query("select uid from tbl_load where id=".$load_id." and lorry_owner_id=" . $owner_id . "")->fetch_assoc();
  $uid = $getloadownerid['uid'];
  $content = array(
       "en" => 'Direct Load New Offer Sent By Lorry Owner.'
   );
$heading = array(
   "en" => "Check Your Load ID#".$load_id." Offer Sent By LorryOwner!!"
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

$descriptions = "Check Your Load ID#".$load_id." Offer Sent By LorryOwner!!";
$title = "Direct Load Offer Sent By Lorry Owner.";
	   $table="tbl_notification";
  $field_values=array("uid","datetime","description","title");
  $data_values=array("$uid","$timestamp","$descriptions","$title");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
	} 
	
}
echo json_encode($returnArr);
?>