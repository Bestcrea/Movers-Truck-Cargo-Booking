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

$uid      = $data['uid'];
$status   = $data['status'];
$load_id  = $data['load_id'];
if ($uid == '' or $status == '' or $load_id == '') {
    $returnArr = array(
        "ResponseCode" => "401",
        "Result" => "false",
        "ResponseMsg" => "Something Went wrong  try again !"
    );
} else {
    
    
    if ($status == 1) {
        $amount      = $data['amount'];
        $amt_type    = $data['amt_type'];
        $total_amt   = $data['total_amt'];
        $wal_amt     = $data['wal_amt'];
        $p_method_id = $data['p_method_id'];
        $trans_id    = $data['trans_id'];
        $description = $data['description'];
        $vp          = $service->query("select wallet from tbl_user where id=" . $uid . "")->fetch_assoc();
		$getownerid = $service->query("select lorry_owner_id from tbl_load where id=" . $load_id . " and uid=" . $uid . "")->fetch_assoc();
		$getcommission = $service->query("select commission from tbl_lowner where id=" . $getownerid['lorry_owner_id'] . "")->fetch_assoc();
        if ($vp['wallet'] >= $data['wal_amt']) {
            $table     = "tbl_load";
            $field     = array(
                'amount' => $amount,
                'amt_type' => $amt_type,
                'total_amt' => $total_amt,
                'wal_amt' => $wal_amt,
                'p_method_id' => $p_method_id,
                'trans_id' => $trans_id,
                'description' => $description,
                'load_status' => 'Accepted',
			    'flow_id'=>4,
				'commission' => $getcommission['commission']
				
            );
            $where     = "where id=" . $load_id . " and uid=" . $uid . "";
            $h         = new FunctionQuery($service);
            $check     = $h->serviceupdateData_Api($field, $table, $where);
			
            if ($wal_amt != 0) {
                
                $mt    = intval($vp['wallet']) - intval($wal_amt);
                $table = "tbl_user";
                $field = array(
                    'wallet' => "$mt"
                );
                $where = "where id=" . $uid . "";
                $h     = new FunctionQuery($service);
                $check = $h->serviceupdateData_Api($field, $table, $where);
                $timestamp = date("Y-m-d H:i:s");
                $table        = "wallet_report";
                $field_values = array(
                    "uid",
                    "message",
                    "status",
                    "amt",
                    "tdate"
                );
                $data_values  = array(
                    "$uid",
                    'Wallet Used in Load Id#' . $load_id,
                    'Debit',
                    "$wal_amt",
                    "$timestamp"
                );
                
                $h      = new FunctionQuery($service);
                $checks = $h->serviceinsertdata_Api($field_values, $data_values, $table);
            }
            $tbwallet  = $service->query("select wallet from tbl_user where id=" . $uid . "")->fetch_assoc();
            $returnArr = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Load Accepted Successfully!!!!!",
                "wallet" => $tbwallet['wallet']
            );
			
			$owner_id =  $getownerid['lorry_owner_id'];
			$content = array(
       "en" => 'Your Offer Accepted.'
   );
$heading = array(
   "en" => "Check Your Offer Accepted. Load ID#".$load_id."!!"
);

$fields = array(
'app_id' => $set['d_key'],
'included_segments' =>  array("Active Users"),
'filters' => array(array('field' => 'tag', 'key' => 'owner_id', 'relation' => '=', 'value' => $owner_id)),
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

$descriptions = 'Check Your Offer Accepted. Load ID#'.$load_id.'!!';
$table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$owner_id","$timestamp","$descriptions");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
        } else {
            $tbwallet  = $service->query("select wallet from tbl_user where id=" . $uid . "")->fetch_assoc();
            $returnArr = array(
                "ResponseCode" => "200",
                "Result" => "false",
                "ResponseMsg" => "Wallet Balance Not There As Per Order Refresh One Time Screen!!!",
                "wallet" => $tbwallet['wallet']
            );
            
        }
    } else if ($status == 2){
		$comment_reject = $data['comment_reject'];
        $table     = "tbl_load";
        $field     = array(
            'load_status' => 'Cancelled',
			    'flow_id'=>5,
			'comment_reject'=>$comment_reject
        );
        $where     = "where id=" . $load_id . " and uid=" . $uid . "";
        $h         = new FunctionQuery($service);
        $check     = $h->serviceupdateData_Api($field, $table, $where);
        $returnArr = array(
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Load Request Rejected Successfully!!!!!"
        );
		$getownerid = $service->query("select lorry_owner_id from tbl_load where id=" . $load_id . " and uid=" . $uid . "")->fetch_assoc();
		$owner_id =  $getownerid['lorry_owner_id'];
			$content = array(
       "en" => 'Your Offer Rejected.'
   );
$heading = array(
   "en" => "Check Your Offer Rejected. Load ID#".$load_id."!!"
);

$fields = array(
'app_id' => $set['d_key'],
'included_segments' =>  array("Active Users"),
'filters' => array(array('field' => 'tag', 'key' => 'owner_id', 'relation' => '=', 'value' => $owner_id)),
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

$descriptions = 'Check Your Offer Rejected. Load ID#'.$load_id.'!!';
$table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$owner_id","$timestamp","$descriptions");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
	   
    }
	else if ($status == 3)
	{
		$offer_description = $data['offer_description'];
		$offer_price = $data['offer_price'];
		$offer_type = $data['offer_type'];
		$offer_total = $data['offer_total'];
		$table     = "tbl_load";
        $field     = array(
            'offer_description' => $offer_description,
            'offer_price' => $offer_price,
			'offer_by'=>'Transporter',
			'offer_type'=>$offer_type,
			'offer_total'=>$offer_total,
			'flow_id'=>6
        );
        $where     = "where id=" . $load_id . " and uid=" . $uid . "";
        $h         = new FunctionQuery($service);
        $check     = $h->serviceupdateData_Api($field, $table, $where);
        $returnArr = array(
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Offer Price Successfully!!!!!"
        );
		
		$getownerid = $service->query("select lorry_owner_id from tbl_load where id=" . $load_id . " and uid=" . $uid . "")->fetch_assoc();
		$owner_id =  $getownerid['lorry_owner_id'];
			$content = array(
       "en" => 'New Offer Send You.'
   );
$heading = array(
   "en" => "New Offer Send You.Please  Check Load ID#".$load_id."!!"
);

$fields = array(
'app_id' => $set['d_key'],
'included_segments' =>  array("Active Users"),
'filters' => array(array('field' => 'tag', 'key' => 'owner_id', 'relation' => '=', 'value' => $owner_id)),
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

$descriptions = 'New Offer Send You.Please  Check Load ID#'.$load_id.'!!';
$table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$owner_id","$timestamp","$descriptions");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
	} 
	else 
	{
		  
        $wal_amt     = $data['wal_amt'];
        $p_method_id = $data['p_method_id'];
        $trans_id    = $data['trans_id'];
        $vp          = $service->query("select wallet from tbl_user where id=" . $uid . "")->fetch_assoc();
		$getownerid = $service->query("select lorry_owner_id from tbl_load where id=" . $load_id . " and uid=" . $uid . "")->fetch_assoc();
		$getcommission = $service->query("select commission from tbl_lowner where id=" . $getownerid['lorry_owner_id'] . "")->fetch_assoc();
        if ($vp['wallet'] >= $data['wal_amt']) {
            $table     = "tbl_load";
            $field     = array(
                'wal_amt' => $wal_amt,
                'p_method_id' => $p_method_id,
                'trans_id' => $trans_id,
                'load_status' => 'Accepted',
			    'flow_id'=>4,
				'commission' => $getcommission['commission']
            );
            $where     = "where id=" . $load_id . " and uid=" . $uid . "";
            $h         = new FunctionQuery($service);
            $check     = $h->serviceupdateData_Api($field, $table, $where);
			
            if ($wal_amt != 0) {
                
                $mt    = intval($vp['wallet']) - intval($wal_amt);
                $table = "tbl_user";
                $field = array(
                    'wallet' => "$mt"
                );
                $where = "where id=" . $uid . "";
                $h     = new FunctionQuery($service);
                $check = $h->serviceupdateData_Api($field, $table, $where);
                $timestamp = date("Y-m-d H:i:s");
                $table        = "wallet_report";
                $field_values = array(
                    "uid",
                    "message",
                    "status",
                    "amt",
                    "tdate"
                );
                $data_values  = array(
                    "$uid",
                    'Wallet Used in Load Id#' . $load_id,
                    'Debit',
                    "$wal_amt",
                    "$timestamp"
                );
                
                $h      = new FunctionQuery($service);
                $checks = $h->serviceinsertdata_Api($field_values, $data_values, $table);
            }
            $tbwallet  = $service->query("select wallet from tbl_user where id=" . $uid . "")->fetch_assoc();
            $returnArr = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Load Accepted Successfully!!!!!",
                "wallet" => $tbwallet['wallet']
            );
			
			$owner_id =  $getownerid['lorry_owner_id'];
			$content = array(
       "en" => 'Your Direct Load Accepted.'
   );
$heading = array(
   "en" => "Direct Load Accepted. Load ID#".$load_id."!!"
);

$fields = array(
'app_id' => $set['d_key'],
'included_segments' =>  array("Active Users"),
'filters' => array(array('field' => 'tag', 'key' => 'owner_id', 'relation' => '=', 'value' => $owner_id)),
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

$descriptions = 'Direct Load Accepted. Load ID#'.$load_id.'!!';
$table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$owner_id","$timestamp","$descriptions");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
	   
        } else {
            $tbwallet  = $service->query("select wallet from tbl_user where id=" . $uid . "")->fetch_assoc();
            $returnArr = array(
                "ResponseCode" => "200",
                "Result" => "false",
                "ResponseMsg" => "Wallet Balance Not There As Per Order Refresh One Time Screen!!!",
                "wallet" => $tbwallet['wallet']
            );
            
        }
	}
}
echo json_encode($returnArr);
?>