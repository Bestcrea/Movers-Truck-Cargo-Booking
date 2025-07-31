<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
if($_POST['owner_id'] == '' or $_POST['lorry_no'] == '' or $_POST['weight'] == '' or $_POST['vehicle_id'] == ''  or $_POST['status'] == '' or $_POST['curr_location'] == '' or $_POST['curr_state_id'] == '' or $_POST['routes'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{

	$owner_id = strip_tags(mysqli_real_escape_string($service,$_POST['owner_id']));
	$lorry_no = mysqli_real_escape_string($service,$_POST['lorry_no']);
    $weight = mysqli_real_escape_string($service,$_POST['weight']);
    $description = mysqli_real_escape_string($service,$_POST['description']);
    $vehicle_id = $_POST['vehicle_id'];
	$status = $_POST['status'];
	$curr_location = $_POST['curr_location'];
	$curr_state_id = $_POST['curr_state_id'];
	$routes = $_POST['routes'];
	
	$target_path = dirname( dirname(__FILE__) ).'/images/lorry/';

$url = 'images/lorry/';


$size = $_POST['size'];
$v = array();
    for ($x = 0; $x < $size; $x++) {
       
            $newname = uniqid().date('YmdHis',time()).mt_rand().'.jpg';
			$v[] =  $url.$newname;
            // Throws exception incase file is not being moved
            move_uploaded_file($_FILES['image'.$x]['tmp_name'], $target_path .$newname);
    }
$multifile = implode('$;',$v);

$table="tbl_lorry";
  $field_values=array("owner_id","lorry_no","weight","description","status","vehicle_id","curr_location","curr_state_id","routes","document","is_verify");
  $data_values=array("$owner_id","$lorry_no","$weight","$description","$status","$vehicle_id","$curr_location","$curr_state_id","$routes","$multifile","1");
   $h = new FunctionQuery($service);
	  $check = $h->serviceinsertdata_Api_Id($field_values,$data_values,$table);

  $returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Lorry Add Successfully!");
	
}
echo json_encode($returnArr);