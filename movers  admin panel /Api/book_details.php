<?php
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['load_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$load_id = $data['load_id'];
	$uid = $data['uid'];
	
	$count = $service->query("select * from tbl_load where id=".$load_id." and uid=".$uid."")->num_rows;
	if($count !=0)
	{
		$list = array();
		$sel = $service->query("select * from tbl_load where id=".$load_id." and uid=".$uid."")->fetch_assoc();
		$vdata = $service->query("select title,img from tbl_vehicle where id=".$sel['vehicle_id']."")->fetch_assoc();
		$pdata = $service->query("select title from tbl_state where id=".$sel['pick_state_id']."")->fetch_assoc();
		
		$ddata = $service->query("select title from tbl_state where id=".$sel['drop_state_id']."")->fetch_assoc();
		
		$odata = $service->query("select id,name,pro_pic,rdate,ccode,mobile from tbl_lowner where id=".$sel['lorry_owner_id']."")->fetch_assoc();
		$ldata = $service->query("select lorry_no,id from tbl_lorry where id=".$sel['lorry_id']."")->fetch_assoc();
	    $list['uid'] = $odata['id'];
		$list['bidder_id'] = $odata['id'];
		$list['lorry_id'] = $ldata['id'];
		$list['bidder_name'] = $odata['name'];
		$list['bidder_img'] = empty($odata['pro_pic']) ? 'images/duser.png': $odata['pro_pic'];
		$list['lorry_number'] = $ldata['lorry_no'];
		$list['bidder_mobile'] =  $odata['ccode'].$odata['mobile'];
		$rdata_rest = $service->query("SELECT sum(total_trate)/count(*) as rate_rest FROM tbl_load where lorry_owner_id=".$sel['lorry_owner_id']." and load_status='Completed' and total_trate !=0")->fetch_assoc();
		$list['rate'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		$list['offer_description'] = (empty($sel['offer_description'])) ? '' : $sel['offer_description'];
		$list['offer_price'] = (empty($sel['offer_price'])) ? '' : $sel['offer_price'];
		$list['offer_by'] = (empty($sel['offer_by'])) ? '' : $sel['offer_by'];
		$list['offer_type'] =(empty($sel['offer_type'])) ? '' : $sel['offer_type'];
		$list['offer_total'] =(empty($sel['offer_total'])) ? '' : $sel['offer_total'];
		
		$list['id'] = $sel['id'];
		$list['vehicle_title'] = $vdata['title'];
		$list['vehicle_img'] = $vdata['img'];
		$list['pickup_point'] = $sel['pickup_point'];
		$list['drop_point'] = $sel['drop_point'];
		$list['is_accept'] = $sel['is_accept'];
		$list['flow_id'] = $sel['flow_id'];
		if($sel['flow_id'] == 0)
		{
			$list['message'] = 'Waiting For Lorry Owner Response';
		}
		else if($sel['flow_id'] == 1)
		{
			$list['message'] = 'Lorry Owner Accepted Load Now You Can Proceed Payment!!';
		}
		else if($sel['flow_id'] == 2)
		{
			$list['message'] = 'Lorry Owner Cancelled Load!!';
		}
		else if($sel['flow_id'] == 3)
		{
			$list['message'] = 'Lorry Owner Offer New Price Please Make Your Decission!!';
		}
		else if($sel['flow_id'] == 4)
		{
			$list['message'] = 'You Accepted Load And Payed Successfully!!';
		}
		else if($sel['flow_id'] == 5)
		{
			$list['message'] = 'You Cancelled Load!!';
		}
		else if($sel['flow_id'] == 6)
		{
			$list['message'] = 'You Offer Load Price Wait Lorry Owner Response!!';
		}
		else if($sel['flow_id'] == 7)
		{
			$list['message'] = 'Your Load Was Pick up By Lorry Owner!!';
		}
		else if($sel['flow_id'] == 8)
		{
			$list['message'] = 'Your Load Successfully Drop At Drop Location Now Give Review About Lorry Owner Response!!';
		}
		
		
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
		$list['description'] = $sel['description'];
		$list['pick_lat'] = $sel['pick_lat'];
		$list['pick_lng'] = $sel['pick_lng'];
		$list['drop_lat'] = $sel['drop_lat'];
		$list['pick_name'] = $sel['pick_name'];
		$list['pick_mobile'] = $sel['pick_mobile'];
		$list['drop_name'] = $sel['drop_name'];
		$list['drop_mobile'] = $sel['drop_mobile'];
		$list['is_rate'] = $sel['is_trate'];
		$list['drop_lng'] = $sel['drop_lng'];
		$list['drop_state_id'] = $sel['drop_state_id'];
		$list['visible_hours'] = $sel['visible_hours'];
		$list['pick_state_id'] = $sel['pick_state_id'];
		$list['pickup_state'] = $pdata['title'];
		$list['drop_state'] = $ddata['title'];
		$list['amount'] = $sel['amount'];
		$list['amt_type'] = $sel['amt_type'];
		$list['total_amt'] = $sel['total_amt'];
		$list['pay_amt'] = $sel['total_amt']-$sel['wal_amt'];
		$list['post_date'] = $sel['post_date'];
		$list['comment_reject'] = (empty($sel['comment_reject'])) ? '' : $sel['comment_reject'];
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