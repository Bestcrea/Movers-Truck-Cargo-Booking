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
		if($sel['lorry_owner_id'] == 0)
		{
		$list['bidder_id'] = '';
		$list['lorry_id'] = '';	
		$list['bidder_name'] = '';
		$list['bidder_img'] = '';
		$list['lorry_number'] = '';
		$list['bidder_mobile'] = '';
		$list['rate'] = '';
		}
		else 
		{
		$odata = $service->query("select id,name,pro_pic,rdate,ccode,mobile from tbl_lowner where id=".$sel['lorry_owner_id']."")->fetch_assoc();
		$ldata = $service->query("select lorry_no,id from tbl_lorry where id=".$sel['lorry_id']."")->fetch_assoc();
	    $list['bidder_id'] = $odata['id'];
		$list['lorry_id'] = $ldata['id'];
		$list['bidder_name'] = $odata['name'];
		$list['bidder_img'] = empty($odata['pro_pic']) ? 'images/duser.png': $odata['pro_pic'];
		$list['lorry_number'] = $ldata['lorry_no'];
		$list['bidder_mobile'] = $odata['ccode'].$odata['mobile'];
		$rdata_rest = $service->query("SELECT sum(total_trate)/count(*) as rate_rest FROM tbl_load where lorry_owner_id=".$sel['lorry_owner_id']." and load_status='Completed' and total_trate !=0")->fetch_assoc();
		$list['rate'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		}
		$list['id'] = $sel['id'];
		$list['vehicle_title'] = $vdata['title'];
		$list['vehicle_img'] = $vdata['img'];
		$list['pickup_point'] = $sel['pickup_point'];
		$list['drop_point'] = $sel['drop_point'];
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
		$list['drop_lng'] = $sel['drop_lng'];
		$list['drop_state_id'] = $sel['drop_state_id'];
		$list['visible_hours'] = $sel['visible_hours'];
		$list['pick_state_id'] = $sel['pick_state_id'];
		$list['pickup_state'] = $pdata['title'];
		$list['drop_state'] = $ddata['title'];
		$list['is_rate'] = $sel['is_trate'];
		$list['pick_name'] = $sel['pick_name'];
		$list['pick_mobile'] = $sel['pick_mobile'];
		$list['drop_name'] = $sel['drop_name'];
		$list['drop_mobile'] = $sel['drop_mobile'];
		$list['amount'] = $sel['amount'];
		$list['amt_type'] = $sel['amt_type'];
		$list['total_amt'] = $sel['total_amt'];
		$list['flow_id'] = $sel['flow_id'];
        if($sel['flow_id'] == 1)
		{
			$list['message'] = 'Lorry Owner Ongoing To Pick up Your Load!!';
		}
		else if($sel['flow_id'] == 2)
		{
			$list['message'] = 'Lorry Owner Pick Up Load Going For Drop Your Load At Drop Location!!';
		}
		
		
		$list['post_date'] = $sel['post_date'];
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
$count = $bid = $service->query("select * from tbl_load_response where load_id=".$sel['id']." and status=0")->num_rows;
if($count !=0)
{
}
else 
{
  if($list['svisible_hours'] < 0)
  {
	  $table="tbl_load";
$where = "where uid=".$uid." and id=".$load_id."";
$h = new FunctionQuery($service);
	$check = $h->serviceDeleteData_Api($where,$table);
	
  }
}
$list['hours_type'] = 'fixed';
}
		$list['material_name'] = $sel['material_name'];
		$list['weight'] = $sel['weight'];
		$list['load_status'] = $sel['load_status'];
		
		$bid = $service->query("select * from tbl_load_response where load_id=".$sel['id']." and status=0");
		$p = array();
		$vp = array();
		if($sel['load_status'] == 'Pending')
		{
		while($kol = $bid->fetch_assoc())
		{
		$odata = $service->query("select name,pro_pic,rdate from tbl_lowner where id=".$kol['owner_id']."")->fetch_assoc();
        $ldata = $service->query("select lorry_no from tbl_lorry where id=".$kol['lorry_id']."")->fetch_assoc();
       		$p['bidder_id'] = $kol['owner_id'];
			$p['lorry_id'] = $kol['lorry_id'];
			$p['description'] = $kol['description'];
			$p['bidder_name'] = $odata['name'];
			$p['bidder_img'] = empty($odata['pro_pic']) ? 'images/duser.png': $odata['pro_pic'];
			$p['lorry_number'] = $ldata['lorry_no'];
			$p['amount'] = $kol['amount'];
			$p['amt_type'] = $kol['amt_type'];
			$p['total_lorry'] = $service->query("select * from tbl_lorry where owner_id=".$kol['owner_id']."")->num_rows;
			$date=date_create($odata['rdate']);
            $p['join_date'] =date_format($date,"Y");
			$p['total_amt'] = $kol['total_amt'];
			$p['is_immediate'] = $kol['is_immediate'];
			$rdata_rest = $service->query("SELECT sum(total_trate)/count(*) as rate_rest FROM tbl_load where lorry_owner_id=".$kol['owner_id']." and load_status='Completed' and total_trate !=0")->fetch_assoc();
			$p['rate'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
			$vp[] = $p;
		}
		}
		$list['bid_status'] = empty($vp)? [] : $vp;
		$returnArr = array("LoadDetails"=>$list,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Load Details Get  Successfully!!");
	}
	else 
	{
		 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Load Not Found!!");
	}
}
echo  json_encode($returnArr);
?>