<?php
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json'); 

$data = json_decode(file_get_contents('php://input'), true);

$record_id = $data['record_id'];
$uid = $data["uid"];
if ($record_id =='' or $uid == '')
{
$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went wrong  try again !");
}
else 
{
	$count = $service->query("select * from tbl_load where id=" . $record_id . " and uid=".$uid."")->num_rows;
	if($count !=0)
	{
	$table = "tbl_load";
        $where = "where id=" . $record_id . " and uid=".$uid."";
        $h = new FunctionQuery($service);
        $check = $h->serviceDeleteData($where, $table);
		$returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "ResponseMsg" => "Load  Delete Successfully!!"
            ];
	}
	else 
	{
	$returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "ResponseMsg" => "Try To Delete Own Load Thanks!!"
            ];	
	}
}
echo  json_encode($returnArr);
?>
