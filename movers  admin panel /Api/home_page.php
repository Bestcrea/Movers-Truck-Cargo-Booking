<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$uid = $data['uid'];
if($uid == '')
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
	
	
	


	
	$banner = $service->query("select * from banner where status=1 and b_type=1");
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
	$cat['total_lorry'] = $service->query("select * from tbl_lorry where routes REGEXP  '\\\b".$row['id']."\\\b' and status=1")->num_rows;
    $cp[] = $cat;
}



$tbwallet = $service->query("select wallet,is_verify,reject_comment from tbl_user where id=".$uid."")->fetch_assoc();
$wallet = empty($tbwallet['wallet']) ? 0 : $tbwallet['wallet'];
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
$kp = array('Banner'=>$v,'Statelist'=>$cp,"currency"=>$set['currency'],"wallet"=>$wallet,"is_verify"=>$is_verify,"top_msg"=>$message);
	
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Home Data Get Successfully!","HomeData"=>$kp);

}
echo json_encode($returnArr);
?>