<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
if($_POST['owner_id'] == '' or $_POST['status'] == '')
{
    $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");
}
else
{
	$owner_id = $_POST['owner_id'];
	$status = $_POST['status'];
	$check_uid = $service->query("select * from tbl_lowner where id=".$owner_id."")->num_rows;
	if($check_uid != 0)
	{
		if($status == 'First' or $status == 'Both')
		{
		$target_path = dirname( dirname(__FILE__) ).'/images/lorryowner_document/';
$url = 'images/lorryowner_document/';

$size = $_POST['size'];
$v = array();
    for ($x = 0; $x < $size; $x++) {
       
            $newname = uniqid().date('YmdHis',time()).mt_rand().'.jpg';
			$v[] =  $url.$newname;
            // Throws exception incase file is not being moved
            move_uploaded_file($_FILES['image'.$x]['tmp_name'], $target_path .$newname);
    }
	
	$multifile = implode('$;',$v);
	
	$sizes = $_POST['sizes'];
$vs = array();
    for ($xp = 0; $xp < $sizes; $xp++) {
       
            $newnames = uniqid().date('YmdHis',time()).mt_rand().'.jpg';
			$vs[] =  $url.$newnames;
            // Throws exception incase file is not being moved
            move_uploaded_file($_FILES['images'.$xp]['tmp_name'], $target_path .$newnames);
    }
	
	$multifiles = implode('$;',$vs);
	
	
	
	
	
	$table="tbl_lowner";
		 $field = array('identity_document'=>$multifile,'selfie'=>$multifiles,'is_verify'=>'1');
  $where = "where id=".$owner_id."";
 
   $h = new FunctionQuery($service);
	  $check = $h->serviceupdateData_Api($field,$table,$where);
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Document Submitted Successfully!!!!!");
		}
       else if($status == 'Identity')
	   {
		 $target_path = dirname( dirname(__FILE__) ).'/images/lorryowner_document/';
$url = 'images/lorryowner_document/';
  $size = $_POST['size'];
$v = array();
    for ($x = 0; $x < $size; $x++) {
       
            $newname = uniqid().date('YmdHis',time()).mt_rand().'.jpg';
			$v[] =  $url.$newname;
            // Throws exception incase file is not being moved
            move_uploaded_file($_FILES['image'.$x]['tmp_name'], $target_path .$newname);
    }
	
	$multifile = implode('$;',$v);
	$table="tbl_lowner";
		 $field = array('identity_document'=>$multifile,'is_verify'=>'1');
  $where = "where id=".$owner_id."";
 
   $h = new FunctionQuery($service);
	  $check = $h->serviceupdateData_Api($field,$table,$where);
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Identity Submitted Successfully!!!!!");
	   }
else 
{
	$target_path = dirname( dirname(__FILE__) ).'/images/lorryowner_document/';
$url = 'images/lorryowner_document/';
 $sizes = $_POST['size'];
$vs = array();
    for ($xp = 0; $xp < $sizes; $xp++) {
       
            $newnames = uniqid().date('YmdHis',time()).mt_rand().'.jpg';
			$vs[] =  $url.$newnames;
            // Throws exception incase file is not being moved
            move_uploaded_file($_FILES['images'.$xp]['tmp_name'], $target_path .$newnames);
    }
	
	$multifiles = implode('$;',$vs);
	
	$table="tbl_lowner";
		 $field = array('selfie'=>$multifiles,'is_verify'=>'1');
  $where = "where id=".$owner_id."";
 
   $h = new FunctionQuery($service);
	  $check = $h->serviceupdateData_Api($field,$table,$where);
	$returnArr = array("ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Selfie Submitted Successfully!!!!!");
}	
	}
	else 
	{
		$returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"User Not Found!!!");
	}
}
echo  json_encode($returnArr);