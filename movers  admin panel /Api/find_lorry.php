<?php
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);
if($data['uid'] == '' or $data['pick_state_id'] == '' or $data['drop_state_id'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$uid = $data['uid'];
	$pick_state_id = $data['pick_state_id'];
	$drop_state_id = $data['drop_state_id'];
	$vehicle = $service->query("select * from tbl_vehicle where status=1");
	$op = array();
	$vom =array();
	while($pop = $vehicle->fetch_assoc())
	{
		$op['id'] = $pop['id'];
		$op['title'] = $pop['title'];
		$op['min_weight'] = $pop['min_weight'];
		$op['max_weight'] = $pop['max_weight'];
		$vehicle_id = $pop['id'];
		
	$list = $service->query("select * from tbl_lorry where  vehicle_id=".$vehicle_id." and routes REGEXP  '\\\b".$pick_state_id."\\\b' and routes REGEXP  '\\\b".$drop_state_id."\\\b'");
	$k = array();
	$p = array();
	while($row = $list->fetch_assoc())
	{
		$vdata = $service->query("select title,img from tbl_vehicle where id=".$row['vehicle_id']."")->fetch_assoc();
		$odata = $service->query("select name,pro_pic from  tbl_lowner where id=".$row['owner_id']."")->fetch_assoc();
		$k['lorry_id'] = $row['id'];
		$k['vehicle_id'] = $row['vehicle_id'];
		$k['lorry_owner_id'] = $row['owner_id'];
		$k['lorry_owner_title'] = $odata['name'];
		$k['lorry_owner_img'] = empty($odata['pro_pic']) ? 'images/duser.png': $odata['pro_pic'];
		$k['lorry_img'] = $vdata['img'];
		$k['lorry_title'] = $vdata['title'];
		$k['weight'] = $row['weight'];
		$rdata_rest = $service->query("SELECT sum(total_trate)/count(*) as rate_rest FROM tbl_load where lorry_owner_id=".$row['owner_id']." and load_status='Completed' and total_trate !=0")->fetch_assoc();
	    $k['review'] = (empty($rdata_rest['rate_rest'])) ? "5" : number_format((float)$rdata_rest['rate_rest'], 2, '.', '');
		$k['curr_location'] = $row['curr_location'];
		$routes = explode(',',$row['routes']);
		$k['routes_count'] = count($routes);
		$k['rc_verify'] = $row['is_verify'];
		$k['lorry_no'] = $row['lorry_no'];
		
		$lorry =  $service->query("select weight from tbl_lorry where id=".$row['id']."")->fetch_assoc();
	$lorry_book =  $service->query("select sum(weight) as total_load_book from tbl_load where lorry_id=".$row['id']." and load_status!='Completed' and load_status!='Cancelled'")->fetch_assoc();
	
	if($lorry_book['total_load_book'] == '')
	{
	$book_load = $lorry['weight'];
	}
	else 
	{
		if(($lorry['weight'] - $lorry_book['total_load_book']) < 0)
		{
		$book_load = 0;	
		}
		else 
		{
			$book_load = $lorry['weight'] - $lorry_book['total_load_book'];
		}
	}
	
		$p[] = $k;
	}
	$op['lorrydata'] = $p;
	
	
	
	
		if($book_load == 0)
		{
		}
		else 
		{
	$vom[] = $op;
		}
	}
	$returnArr = array("FindLorryData"=>$vom,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Find Lorry Get  Successfully!!");
}
echo  json_encode($returnArr);
?>