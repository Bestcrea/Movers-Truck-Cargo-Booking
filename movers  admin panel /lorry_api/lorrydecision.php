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
$status = $data['status'];
$load_type = $data['load_type'];
if ($status == '' or $load_id == '' or $owner_id == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went wrong  try again !"
    );
} else {
	
	if ($status == 1) {
        
            $table     = "tbl_load";
			if($load_type == 'POST_LOAD')
			{
				$field     = array(
                'load_status' => 'Load_start',
			    'flow_id'=>2
            );
			}
			else 
			{
            $field     = array(
                'load_status' => 'Load_start',
			    'flow_id'=>7
            );
			}
            $where     = "where id=" . $load_id . " and lorry_owner_id=" . $owner_id . "";
            $h         = new FunctionQuery($service);
            $check     = $h->serviceupdateData_Api($field, $table, $where);
            $returnArr = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Load Pick up Successfully!!!!!"
            );
			
			$getloadownerid = $service->query("select uid from tbl_load where id=".$load_id."")->fetch_assoc();
  $uid = $getloadownerid['uid'];
  $content = array(
       "en" => 'Load Pick up Successfully.'
   );
$heading = array(
   "en" => "Check Your Load ID#".$load_id." Pick up Successfully!!"
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

$descriptions = 'Load Pick up Successfully.';
$title = "Check Your Load ID#".$load_id." Pick up Successfully!!";
	   $table="tbl_notification";
  $field_values=array("uid","datetime","description","title");
  $data_values=array("$uid","$timestamp","$descriptions","$title");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
    } else if ($status == 2){
		
        $table     = "tbl_load";
		if($load_type == 'POST_LOAD')
			{
				$field     = array(
                'load_status' => 'Completed',
			    'flow_id'=>3
            );
			}
			else 
			{
        $field     = array(
                'load_status' => 'Completed',
			    'flow_id'=>8
            );
			}
        $where     = "where id=" . $load_id . " and lorry_owner_id=" . $owner_id . "";
        $h         = new FunctionQuery($service);
        $check     = $h->serviceupdateData_Api($field, $table, $where);
        $returnArr = array(
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Load Completed Successfully!!!!!"
        );
		
		$getloadownerid = $service->query("select uid from tbl_load where id=".$load_id."")->fetch_assoc();
  $uid = $getloadownerid['uid'];
  $content = array(
       "en" => 'Load Drop Successfully.'
   );
$heading = array(
   "en" => "Check Your Load ID#".$load_id." Drop Successfully!!"
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

$descriptions = 'Load Drop Successfully.';
$title = "Check Your Load ID#".$load_id." Drop Successfully!!";
	   $table="tbl_notification";
  $field_values=array("uid","datetime","description","title");
  $data_values=array("$uid","$timestamp","$descriptions","$title");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
	   
    }
	
}
echo json_encode($returnArr);
?>