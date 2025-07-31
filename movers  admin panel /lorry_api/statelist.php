<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
$data = json_decode(file_get_contents('php://input'), true);
header('Content-type: text/json');
if($data['owner_id'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
    $owner_id = strip_tags(mysqli_real_escape_string($service,$data['owner_id']));
    
    
$check = $service->query("select * from tbl_state where status=1");
$op = array();
while($row = $check->fetch_assoc())
{
		$op[] = $row;
}
$returnArr = array("StateData"=>$op,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"State List Get Successfully!!");
}
echo json_encode($returnArr);