<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
$pick_state_name = $data['pick_state_name'];
$drop_state_name = $data['drop_state_name'];
if($pick_state_name == '' or $drop_state_name == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
$sel = $service->query("select id from tbl_state where title='".$pick_state_name."'")->fetch_assoc();
$pstate_id = (empty($sel['id'])) ? "0" : $sel['id'];

$sels = $service->query("select id from tbl_state where title='".$drop_state_name."'")->fetch_assoc();
$dstate_id = (empty($sels['id'])) ? "0" : $sels['id'];
if($pstate_id == '0' and $dstate_id == '0')
{
	$returnArr = array("pick_state_id"=>$pstate_id,"drop_state_id"=>$dstate_id,"ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Change Pick Up Location And Drop Location We not Provide Service On This Location!!");
}
else if($pstate_id != '0' and $dstate_id == '0')
{
	$returnArr = array("pick_state_id"=>$pstate_id,"drop_state_id"=>$dstate_id,"ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Change Drop Location We not Provide Service On This Location!!");
}
else if($pstate_id == '0' and $dstate_id != '0')
{
	$returnArr = array("pick_state_id"=>$pstate_id,"drop_state_id"=>$dstate_id,"ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Change Pick Up Location We not Provide Service On This Location!!");
}
else 
{
$returnArr = array("pick_state_id"=>$pstate_id,"drop_state_id"=>$dstate_id,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Get State Id!!");
}
}
echo json_encode($returnArr);
?>