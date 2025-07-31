<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
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
	$earn = array();
	$total_earn            = $service->query("select sum((total_amt) - ((total_amt) * commission/100)) as total_amt from tbl_load where lorry_owner_id=" . $owner_id . " and load_status ='Completed'")->fetch_assoc();
    $total_earns           = empty($total_earn['total_amt']) ? "0" : number_format((float) ($total_earn['total_amt']), 2, '.', '');
	
	$total_payout          = $service->query("select sum(amt) as total_payout from payout_setting where owner_id=" . $owner_id . "")->fetch_assoc();
    $receive_payout        = empty($total_payout['total_payout']) ? "0" : number_format((float) ($total_payout['total_payout']), 2, '.', '');
	$final_earn            = number_format((float) ($total_earns) - $receive_payout, 2, '.', '');
	
	$earn['earning'] = $final_earn;
	$earn['withdraw_limit'] = $set['pstore'];
	
	$returnArr = array("Earning"=>$earn,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Earning Report Get Successfully!");
}
echo json_encode($returnArr);
?>