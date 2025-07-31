<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['load_id'] == ''   or $data['total_trate'] == ''  or $data['rate_ttext'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$uid = $data['uid'];
	$load_id = $data['load_id'];
	$total_trate = $data['total_trate'];
	$rate_ttext = mysqli_real_escape_string($service,$data['rate_ttext']);
	
	$table="tbl_load";
  $field = array('total_trate'=>$total_trate,'rate_ttext'=>$rate_ttext,'is_trate'=>1);
  $where = "where uid=".$uid." and id=".$load_id."";
$h = new FunctionQuery($service);
	  $check = $h->serviceupdateData_Api($field,$table,$where);
	  $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Rate Updated Successfully!!!");
}
echo json_encode($returnArr);