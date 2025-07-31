<?php
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json'); 

$data = json_decode(file_get_contents('php://input'), true);

$load_id = $data['load_id'];
$owner_id = $data["owner_id"];
if ($load_id =='' or $owner_id == '')
{
$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong  try again !");
}
else 
{
	$count = $service->query("select * from tbl_load_response where load_id=" . $load_id . " and owner_id=".$owner_id."")->num_rows;
	if($count !=0)
	{
	$table = "tbl_load_response";
        $where = "where load_id=" . $load_id . " and owner_id=".$owner_id."";
        $h = new FunctionQuery($service);
        $check = $h->serviceDeleteData($where, $table);
		$returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Bid  Delete Successfully!!"
            ];
	}
	else 
	{
	$returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Try To Delete Own Bid Thanks!!"
            ];	
	}
}
echo  json_encode($returnArr);
?>