<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
$data = json_decode(file_get_contents('php://input'), true);


if($data['name'] == ''  or $data['mobile'] == ''   or $data['password'] == '' or $data['ccode'] == '' or $data['email'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
    
    $name = strip_tags(mysqli_real_escape_string($service,$data['name']));
	$email = strip_tags(mysqli_real_escape_string($service,$data['email']));
    $mobile = strip_tags(mysqli_real_escape_string($service,$data['mobile']));
	$ccode = strip_tags(mysqli_real_escape_string($service,$data['ccode']));
     $password = strip_tags(mysqli_real_escape_string($service,$data['password']));
     
     
     
    $checkmob = $service->query("select * from tbl_lowner where mobile=".$mobile."");
    $checkemail = $service->query("select * from tbl_lowner where email='".$email."'");
   
    if($checkmob->num_rows != 0)
    {
        $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Mobile Number Already Used!");
    }
	else if($checkemail->num_rows != 0)
    {
        $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Email Address Already Used!");
    }
    else
    {
       
	   
	   
		   $timestamp = date("Y-m-d H:i:s");
		   
		   $table="tbl_lowner";
  $field_values=array("name","email","mobile","rdate","password","ccode");
  $data_values=array("$name","$email","$mobile","$timestamp","$password","$ccode");
   $h = new FunctionQuery($service);
	  $check = $h->serviceinsertdata_Api($field_values,$data_values,$table);
  $c = $service->query("select * from tbl_lowner where id=".$check."")->fetch_assoc();
  $returnArr = array("UserLogin"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Sign Up Done Successfully!");
  
	   
    
}
}

echo json_encode($returnArr);