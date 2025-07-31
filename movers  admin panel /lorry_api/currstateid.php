<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$curr_state_name = $data['curr_state_name'];
if($curr_state_name == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
$sel = $service->query("select id from tbl_state where title='".$curr_state_name."'")->fetch_assoc();
$pstate_id = (empty($sel['id'])) ? "0" : $sel['id'];
if($pstate_id == '0')
{
	$returnArr = array("curr_state_id"=>$pstate_id,"ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Change Current Location We not Provide Service On This Location!!");
}
else 
{
$returnArr = array("curr_state_id"=>$pstate_id,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Get State Id!!");
}
}
echo json_encode($returnArr);
?>