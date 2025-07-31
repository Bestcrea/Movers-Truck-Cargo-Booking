<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['owner_id'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$owner_id = $data['owner_id'];
	$lprofile = $service->query("select name,pro_pic,rdate from tbl_lowner where id=".$owner_id."")->fetch_assoc();
	$apro = array();
	$apro['name'] = $lprofile['name'];
	$apro['pro_pic'] = $lprofile['pro_pic'];
	$apro['rdate'] = $lprofile['rdate'];
	$rdata_rest = $service->query("SELECT sum(total_trate)/count(*) as rate_rest FROM tbl_load where lorry_owner_id=".$owner_id." and load_status='Completed' and total_trate !=0")->fetch_assoc();
	$apro['review'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
	$apro['total_review'] = $service->query("select * from tbl_load where lorry_owner_id=".$owner_id." and is_trate=1")->num_rows;
	$apro['total_lorry'] = $service->query("select * from tbl_lorry where owner_id=".$owner_id."")->num_rows;
	
	
	$user = $service->query("SELECT lorry_owner_id,total_trate,rate_ttext FROM `tbl_load` where lorry_owner_id=".$owner_id." and is_trate=1");
$po = array();
$uo = array();
while($row = $user->fetch_assoc())
{
	$udata = $service->query("SELECT pro_pic,name,ccode,mobile FROM `tbl_lowner` where id=".$row['lorry_owner_id']."")->fetch_assoc();
	$po['user_img'] = $udata['pro_pic'];
	$po['customername'] = $udata['name'];
	$po['rate_number'] = $row['total_trate'];
	$po['rate_text'] = $row['rate_ttext'];
	$uo[] = $po;
}
$apro['total_review_user_wise'] =$uo;


	$returnArr = array("lorrizprofile"=>$apro,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Lorry Owner Profile Get successfully!");
}
echo json_encode($returnArr);
?>
