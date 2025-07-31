<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['load_id'] == ''   or $data['total_lrate'] == ''  or $data['rate_ltext'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$uid = $data['uid'];
	$load_id = $data['load_id'];
	$total_lrate = $data['total_lrate'];
	$rate_ltext = mysqli_real_escape_string($service,$data['rate_ltext']);
	
	$table="tbl_load";
  $field = array('total_lrate'=>$total_lrate,'rate_ltext'=>$rate_ltext,'is_lrate'=>1);
  $where = "where lorry_owner_id=".$uid." and id=".$load_id."";
$h = new FunctionQuery($service);
	  $check = $h->serviceupdateData_Api($field,$table,$where);
	  $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Rate Updated Successfully!!!");
}
echo json_encode($returnArr);