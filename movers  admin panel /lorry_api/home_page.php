<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$owner_id = $data['owner_id'];
if($owner_id == '')
{
	$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong  try again !");
}
else 
{
	$v = array();
	$cp = array(); 
	$d = array();
	$pop = array();
	$sec = array();
	
	
	


	
	$banner = $service->query("select * from banner where status=1 and b_type=2");
$vbanner = array();
while($row = $banner->fetch_assoc())
{
	$vbanner['id'] = $row['id'];
	$vbanner['img'] = $row['img'];
    $v[] = $vbanner;
}

$cato = $service->query("select * from tbl_state where status=1");
$cat = array();
while($row = $cato->fetch_assoc())
{
	$cat['id'] = $row['id'];
	$cat['title'] = $row['title'];
	$cat['img'] = $row['img'];
	$cat['total_load'] = $service->query("select * from tbl_load where pick_state_id=".$row['id']." or drop_state_id=".$row['id']."")->num_rows;
	$cat['total_lorry'] =  $service->query("select * from tbl_lorry where routes REGEXP  '\\\b".$row['id']."\\\b' and status=1")->num_rows;
    $cp[] = $cat;
}

$lry = $service->query("select * from tbl_lorry where owner_id=".$owner_id."");
$lorry = array();
while($row = $lry->fetch_assoc())
{
	$vdata = $service->query("select title,img from tbl_vehicle where id=".$row['vehicle_id']."")->fetch_assoc();
	$lorry['id'] = $row['id'];
	$lorry['lorry_img'] = $vdata['img'];
	$lorry['lorry_title'] = $vdata['title'];
	$lorry['weight'] = $row['weight'];
	$lorry['rc_verify'] = $row['is_verify'];
	$lorry['lorry_no'] = $row['lorry_no'];
	$lorry['routes'] = count(explode(',',$row['routes']));
	$state = $service->query("select GROUP_CONCAT(`title`) as total_state from tbl_state where id IN(".$row['routes'].")")->fetch_assoc();
	$lorry['total_routes'] = explode(',',$state['total_state']);
	$lorry['description'] = $row['description'];
	$lorry['weight'] = $row['weight'];
    $pop[] = $lorry;
}


$tbwallet = $service->query("select is_verify,reject_comment from tbl_lowner where id=".$owner_id."")->fetch_assoc();
$is_verify = $tbwallet['is_verify'];
$reject_comment = $tbwallet['reject_comment'];
if($is_verify == 0)
{
	$message = 'Identity Verification Pending.';
}
else if($is_verify == 1)
{
	$message = 'Identity Verification Under Process.';
}
else if($is_verify == 2)
{
	$message = 'Identity Verified.';
}
else 
{
	$message = $reject_comment;
}

$kp = array('Banner'=>$v,'Statelist'=>$cp,"currency"=>$set['currency'],"mylorrylist"=>$pop,"is_verify"=>$is_verify,"top_msg"=>$message);
	
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Home Data Get Successfully!","HomeData"=>$kp);

}
echo json_encode($returnArr);
?>