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
$owner_id = $data['owner_id'];
$lorry_id = $data['lorry_id'];
if ($uid == '' or $status == '' or $load_id == '' or $owner_id == '' or $lorry_id == '') {
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
		$getcommission = $service->query("select commission from tbl_lowner where id=" . $owner_id . "")->fetch_assoc();
        $vp          = $service->query("select wallet from tbl_user where id=" . $uid . "")->fetch_assoc();
        if ($vp['wallet'] >= $data['wal_amt']) {
            $table     = "tbl_load";
            $field     = array(
                'amount' => $amount,
                'amt_type' => $amt_type,
                'total_amt' => $total_amt,
                'lorry_id' => $lorry_id,
                'lorry_owner_id' => $owner_id,
                'wal_amt' => $wal_amt,
                'p_method_id' => $p_method_id,
                'trans_id' => $trans_id,
                'description' => $description,
                'load_status' => 'Accepted',
				'commission' => $getcommission['commission'],
				'flow_id'=>1
            );
            $where     = "where id=" . $load_id . " and uid=" . $uid . "";
            $h         = new FunctionQuery($service);
            $check     = $h->serviceupdateData_Api($field, $table, $where);
            $returnArr = array(
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Bidder Accepted Successfully!!!!!"
            );
            if ($wal_amt != 0) {
                
                $mt    = intval($vp['wallet']) - intval($wal_amt);
                $table = "tbl_user";
                $field = array(
                    'wallet' => "$mt"
                );
                $where = "where id=" . $uid . "";
                $h     = new FunctionQuery($service);
                $check = $h->serviceupdateData_Api($field, $table, $where);
                
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
			
			
  $content = array(
       "en" => 'Your Bid Accepted.'
   );
$heading = array(
   "en" => "Check Your Bid Accepted. Load ID#".$load_id."!!"
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

$descriptions = 'Check Your Bid Accepted. Load ID#'.$load_id.'!!';
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
    } else {
        $table     = "tbl_load_response";
        $field     = array(
            'status' => 2,
            'owner_id' => $owner_id
        );
        $where     = "where load_id=" . $load_id . " and owner_id=" . $owner_id . " and lorry_id=" . $lorry_id . "";
        $h         = new FunctionQuery($service);
        $check     = $h->serviceupdateData_Api($field, $table, $where);
        $returnArr = array(
            "ResponseCode" => "200",
            "Result" => "true",
            "ResponseMsg" => "Bidder Rejected Successfully!!!!!"
        );
		
		$content = array(
       "en" => 'Your Bid Rejected.'
   );
$heading = array(
   "en" => "Check Your Bid Rejected. Load ID#".$load_id."!!"
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

$descriptions = 'Check Your Bid Rejected. Load ID#'.$load_id.'!!';
$table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$owner_id","$timestamp","$descriptions");
  
    $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
    }
    
}
echo json_encode($returnArr);
?>