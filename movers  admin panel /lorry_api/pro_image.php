<?php 
require dirname( dirname(__FILE__) ).'/inc/config.php';
require dirname( dirname(__FILE__) ).'/inc/FunctionQuery.php';
require dirname( dirname(__FILE__) ).'/inc/keyvaliation.php';
header('Content-type: text/json');
if($_POST['owner_id'] == '' or $_POST['size'] == '')
{
 $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Something Went Wrong!");    
}
else
{
 $owner_id =  $service->real_escape_string($_POST['owner_id']);
 $target_path = dirname( dirname(__FILE__) ).'/images/profile/';
$url = 'images/profile/';

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
  $field = array('pro_pic'=>$multifile);
  $where = "where id=".$owner_id."";
$h = new FunctionQuery($service);
	  $check = $h->serviceupdateData_Api($field,$table,$where);
	  $c = $service->query("select * from tbl_lowner where id=".$owner_id."")->fetch_assoc();
 $returnArr = array("UserLogin"=>$c,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"Profile Image Upload Successfully!!");
}
echo  json_encode($returnArr);
?>