<?php
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['owner_id'] == '' or $data['load_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$load_id = $data['load_id'];
	$owner_id = $data['owner_id'];
	
	$count = $service->query("select * from tbl_load where id=".$load_id." and lorry_owner_id=".$owner_id."")->num_rows;
	if($count !=0)
	{
		$list = array();
		$sel = $service->query("select * from tbl_load where id=".$load_id." and lorry_owner_id=".$owner_id."")->fetch_assoc();
		$vdata = $service->query("select title,img from tbl_vehicle where id=".$sel['vehicle_id']."")->fetch_assoc();
		$pdata = $service->query("select title from tbl_state where id=".$sel['pick_state_id']."")->fetch_assoc();
		$ddata = $service->query("select title from tbl_state where id=".$sel['drop_state_id']."")->fetch_assoc();
		$list['id'] = $sel['id'];
		$list['uid'] = $sel['uid'];
		$list['vehicle_title'] = $vdata['title'];
		$list['vehicle_img'] = $vdata['img'];
		$list['pickup_point'] = $sel['pickup_point'];
		$list['drop_point'] = $sel['drop_point'];
		$list['description'] = $sel['description'];
		$list['pick_lat'] = $sel['pick_lat'];
		$list['pick_lng'] = $sel['pick_lng'];
		$list['drop_lat'] = $sel['drop_lat'];
		$list['drop_lng'] = $sel['drop_lng'];
		$list['drop_state_id'] = $sel['drop_state_id'];
		$list['visible_hours'] = $sel['visible_hours'];
		$list['pick_state_id'] = $sel['pick_state_id'];
		$list['pick_name'] = $sel['pick_name'];
		$list['pick_mobile'] = $sel['pick_mobile'];
		$list['drop_name'] = $sel['drop_name'];
		$list['drop_mobile'] = $sel['drop_mobile'];
		$list['pickup_state'] = $pdata['title'];
		$list['drop_state'] = $ddata['title'];
		$list['amount'] = $sel['amount'];
		$list['amt_type'] = $sel['amt_type'];
		$list['total_amt'] = $sel['total_amt'];
		$list['is_rate'] = $sel['is_lrate'];
		$odata = $service->query("select id,name,pro_pic,rdate,ccode,mobile from tbl_user where id=".$sel['uid']."")->fetch_assoc();
		$list['loader_name'] = $odata['name'];
		$list['loader_img'] = empty($odata['pro_pic']) ? 'images/duser.png': $odata['pro_pic'];
		$rdata_rest = $service->query("SELECT sum(total_lrate)/count(*) as rate_rest FROM tbl_load where uid=".$sel['uid']." and load_status='Completed' and total_lrate !=0")->fetch_assoc();
		$list['loader_rate'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		$list['flow_id'] = $sel['flow_id'];
		$list['loader_mobile'] = $odata['ccode'].$odata['mobile'];
		$list['post_date'] = $sel['post_date'];
		if($sel['p_method_id'] == 0)
		{
			$list['p_method_name'] = '';
		}
		else 
		{
		$pname = $service->query("select title from tbl_payment_list where id=".$sel['p_method_id']."")->fetch_assoc();
		$list['p_method_name'] = $pname['title'];
		}
		$list['Order_Transaction_id'] = $sel['trans_id'];
		$list['wal_amt'] = $sel['wal_amt'];
		$list['pay_amt'] = $sel['total_amt']-$sel['wal_amt'];
		$d1= new DateTime($sel['post_date']); // first date
		$timestamp = date("Y-m-d H:i:s");
$d2= new DateTime($timestamp); // second date
$interval= $d1->diff($d2); // get difference between two dates
if($sel['visible_hours'] == 0)
{
	$list['svisible_hours'] = 0 ;
	$list['hours_type'] = 'infinite';
}
else 
{
$list['svisible_hours'] = $sel['visible_hours'] - (($interval->days * 24) + $interval->h);
$list['hours_type'] = 'fixed';
}
		$list['material_name'] = $sel['material_name'];
		$list['weight'] = $sel['weight'];
		$list['load_status'] = $sel['load_status'];
		
		
		$returnArr = array("LoadDetails"=>$list,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Load Details Get  Successfully!!");
	}
	else 
	{
		 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Load Not Found!!");
	}
}
echo  json_encode($returnArr);
?>