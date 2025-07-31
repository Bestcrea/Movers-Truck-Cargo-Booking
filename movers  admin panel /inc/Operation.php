<?php
require "config.php";
require "FunctionQuery.php";
if (isset($_POST["type"])) {
    if ($_POST["type"] == "login") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $stype = $_POST["stype"];
        
            $h = new FunctionQuery($service);

            $count = $h->servicelogin($username, $password, "admin");
            if ($count != 0) {
                $_SESSION["servicename"] = $username;
                $_SESSION["stype"] = $stype;
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Login Successfully!",
                    "message" => "welcome admin!!",
                    "action" => "dashboard.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" => "Please Use Valid Data!!",
                    "message" => "Invalid Data!!",
                    "action" => "index.php",
                ];
            }
        
    } elseif ($_POST["type"] == "update_status") {
        $id = $_POST["id"];
        $status = $_POST["status"];
        $coll_type = $_POST["coll_type"];
        $page_name = $_POST["page_name"];
         if ($coll_type == "userstatus") {
            $table = "tbl_user";
            $field = "status=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "User Status Change Successfully!!",
                    "message" => "User section!",
                    "action" => "userlist.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "userlist.php",
                ];
            }
        }elseif ($coll_type == "luserstatus") {
            $table = "tbl_lowner";
            $field = "status=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "User Status Change Successfully!!",
                    "message" => "User section!",
                    "action" => "ownerlist.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "ownerlist.php",
                ];
            }
        }else if ($coll_type == "documentstatus") {
            $table = "tbl_lorry";
            $field = "is_verify=1";
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Document Approved Successfully!!",
                    "message" => "Document section!",
                    "action" => "lorrylist.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "lorrylist.php",
                ];
            }
        }else if ($coll_type == "lorrydocstatus") {
            $table = "tbl_lowner";
            $field = "is_verify=2";
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Document Approved Successfully!!",
                    "message" => "Document section!",
                    "action" => "ownerlist.php",
                ];
				
				$udata = $service->query("select name from tbl_lowner where id=".$id."")->fetch_assoc();
$name = $udata['name'];

	  
	  
	   
$content = array(
       "en" => $name.', Your Document  Has Been Approved.'
   );
$heading = array(
   "en" => "Document Approved"
);

$fields = array(
'app_id' => $set['d_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$id),
'filters' => array(array('field' => 'tag', 'key' => 'owner_id', 'relation' => '=', 'value' => $id)),
'contents' => $content,
'headings' => $heading,
'android_channel_id'=>$set['d_cust']
);

$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['d_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");


$table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$id","$timestamp","Document Approved");
  
      $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table); 
	   
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "ownerlist.php",
                ];
            }
        }else if  ($coll_type == "userdocstatus") {
            $table = "tbl_user";
            $field = "is_verify=2";
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Document Approved Successfully!!",
                    "message" => "Document section!",
                    "action" => "userlist.php",
                ];
				
				$udata = $service->query("select name from tbl_user where id=".$id."")->fetch_assoc();
$name = $udata['name'];

	  
	  
	   
$content = array(
       "en" => $name.', Your Document  Has Been Approved.'
   );
$heading = array(
   "en" => "Document Approved"
);

$fields = array(
'app_id' => $set['one_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$id),
'filters' => array(array('field' => 'tag', 'key' => 'userid', 'relation' => '=', 'value' => $id)),
'contents' => $content,
'headings' => $heading,
'android_channel_id'=>$set['one_cust']
);

$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['one_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");

$title = 'Document Approved Please Check Status!!';
$table="tbl_notification";
  $field_values=array("uid","datetime","description","title");
  $data_values=array("$id","$timestamp","Document Approved","$title");
  
      $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table);
	   
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "userlist.php",
                ];
            }
        } elseif ($coll_type == "dark_mode") {
            $table = "tbl_setting";
            $field = "show_dark=" . $status . "";
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData_single($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Dark Mode Status Change Successfully!!",
                    "message" => "Dark Mode section!",
                    "action" => $page_name,
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => $page_name,
                ];
            }
        }  else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" => "Option Not There!!",
                "message" => "Error!!",
                "action" => "dashboard.php",
            ];
        }
    }  elseif ($_POST["type"] == "add_banner") {
        $okey = $_POST["status"];
		$b_type = $_POST["b_type"];
        $target_dir = dirname(dirname(__FILE__)) . "/images/banner/";
        $url = "images/banner/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if (
            end($temp) != "jpg" &&
            end($temp) != "png" &&
            end($temp) != "jpeg"
        ) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" => "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                "message" => "Upload Problem!!",
                "action" => "add_Banner.php",
            ];
        } else {
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "banner";
            $field_values = ["img", "status","b_type"];
            $data_values = ["$url", "$okey","$b_type"];

            $h = new FunctionQuery($service);
            $check = $h->serviceinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Banner Add Successfully!!",
                    "message" => "banner section!",
                    "action" => "list_Banner.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "add_Banner.php",
                ];
            }
        }
    }   elseif ($_POST["type"] == "add_state") {
        $okey = $_POST["status"];
        $title = $service->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/state/";
        $url = "images/state/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if (
            end($temp) != "jpg" &&
            end($temp) != "png" &&
            end($temp) != "jpeg"
        ) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" => "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                "message" => "Upload Problem!!",
                "action" => "add_state.php",
            ];
        } else {
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_state";
            $field_values = ["img", "status", "title"];
            $data_values = ["$url", "$okey", "$title"];

            $h = new FunctionQuery($service);
            $check = $h->serviceinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Province Add Successfully!!",
                    "message" => "Province section!",
                    "action" => "list_state.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "add_state.php",
                ];
            }
        }
    }elseif ($_POST["type"] == "add_vehicle") {
        $okey = $_POST["status"];
        $title = $service->real_escape_string($_POST["title"]);
		$sweight = $_POST['sweight'];
		$eweight = $_POST['eweight'];
        $target_dir = dirname(dirname(__FILE__)) . "/images/vehicle/";
        $url = "images/vehicle/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if (
            end($temp) != "jpg" &&
            end($temp) != "png" &&
            end($temp) != "jpeg"
        ) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" => "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                "message" => "Upload Problem!!",
                "action" => "add_vehicle.php",
            ];
        } else {
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "tbl_vehicle";
            $field_values = ["img", "status", "title","min_weight","max_weight"];
            $data_values = ["$url", "$okey", "$title","$sweight","$eweight"];

            $h = new FunctionQuery($service);
            $check = $h->serviceinsertdata($field_values, $data_values, $table);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Vehicle Add Successfully!!",
                    "message" => "Vehicle section!",
                    "action" => "list_vehicle.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "add_vehicle.php",
                ];
            }
        }
    } elseif ($_POST["type"] == "com_payout") {
        $payout_id = $_POST["payout_id"];
        $target_dir = dirname(dirname(__FILE__)) . "/images/payout/";
        $url = "images/payout/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if (
            end($temp) != "jpg" &&
            end($temp) != "png" &&
            end($temp) != "jpeg"
        ) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" => "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                "message" => "Upload Problem!!",
                "action" => "payout.php",
            ];
        } else {
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $target_file);
            $table = "payout_setting";
            $field = ["proof" => $url, "status" => "completed"];
            $where = "where id=" . $payout_id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData($field, $table, $where);

            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Payout Update Successfully!!",
                    "message" => "Payout section!",
                    "action" => "payout.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "payout.php",
                ];
            }
        }
    }elseif($_POST["type"] =="cancle_order")
	{
		$c_reason = $service->real_escape_string($_POST['c_reason']);
		$id = $_POST["id"];
		
						$table="tbl_lorry";
  $field = array('cancle_reason'=>$c_reason,'is_verify'=>2);
  $where = "where id=".$id."";
$h = new FunctionQuery($service);
	  $check = $h->serviceupdateData($field,$table,$where);
	  
	  $checks = $service->query("select owner_id from tbl_lorry where id=".$id."")->fetch_assoc(); 
		 $owner_id = $checks['owner_id'];
			$udata = $service->query("select name from tbl_lowner where id=".$owner_id."")->fetch_assoc();
$name = $udata['name'];

	  
	  
	   
$content = array(
       "en" => $name.', Your Document #'.$id.' Has Been Rejected.'
   );
$heading = array(
   "en" => $c_reason
);

$fields = array(
'app_id' => $set['d_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$id),
'filters' => array(array('field' => 'tag', 'key' => 'owner_id', 'relation' => '=', 'value' => $owner_id)),
'contents' => $content,
'headings' => $heading,
'android_channel_id'=>$set['d_cust']
);

$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['d_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");


$table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$owner_id","$timestamp","$c_reason");
  
      $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table); 
	   
	   
	  if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Document Rejected Successfully!!",
                        "message" => "Document section!",
                        "action" => "lorrylist.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "lorrylist.php",
                    ];
                }
	}
	elseif($_POST["type"] =="set_commission")
	{
		$commission = $_POST['commission'];
		$id = $_POST["id"];
		
				
	  $table = "tbl_lowner";
            $field = "commission=" . $commission . "";
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData_single($field, $table, $where);
			
	 
	  if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Set Commission Successfully!!",
                        "message" => "Commission section!",
                        "action" => "ownerlist.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "ownerlist.php",
                    ];
                }
	}
	elseif($_POST["type"] =="lorrycancle_order")
	{
		$c_reason = $service->real_escape_string($_POST['c_reason']);
		$id = $_POST["id"];
		$status = $_POST["status"];
		
						$table="tbl_lowner";
  $field = array('reject_comment'=>$c_reason,'is_verify'=>$status);
  $where = "where id=".$id."";
$h = new FunctionQuery($service);
	  $check = $h->serviceupdateData($field,$table,$where);
	  
	  
			$udata = $service->query("select name from tbl_lowner where id=".$id."")->fetch_assoc();
$name = $udata['name'];

	  
	  
	   
$content = array(
       "en" => $name.', Your Document #'.$id.' Has Been Rejected.'
   );
$heading = array(
   "en" => $c_reason
);

$fields = array(
'app_id' => $set['d_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$id),
'filters' => array(array('field' => 'tag', 'key' => 'owner_id', 'relation' => '=', 'value' => $id)),
'contents' => $content,
'headings' => $heading,
'android_channel_id'=>$set['d_cust']
);

$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['d_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");


$table="tbl_rnoti";
  $field_values=array("rid","date","msg");
  $data_values=array("$id","$timestamp","$c_reason");
  
      $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table); 
	   
	   
	  if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Document Rejected Successfully!!",
                        "message" => "Document section!",
                        "action" => "ownerlist.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "ownerlist.php",
                    ];
                }
	}
	elseif($_POST["type"] =="usercancle_order")
	{
		$c_reason = $service->real_escape_string($_POST['c_reason']);
		$id = $_POST["id"];
		$status = $_POST["status"];
		
						$table="tbl_user";
  $field = array('reject_comment'=>$c_reason,'is_verify'=>$status);
  $where = "where id=".$id."";
$h = new FunctionQuery($service);
	  $check = $h->serviceupdateData($field,$table,$where);
	  
	  
			$udata = $service->query("select name from tbl_user where id=".$id."")->fetch_assoc();
$name = $udata['name'];

	  
	  
	   
$content = array(
       "en" => $name.', Your Document #'.$id.' Has Been Rejected.'
   );
$heading = array(
   "en" => $c_reason
);

$fields = array(
'app_id' => $set['one_key'],
'included_segments' =>  array("Active Users"),
'data' => array("order_id" =>$id),
'filters' => array(array('field' => 'tag', 'key' => 'userid', 'relation' => '=', 'value' => $id)),
'contents' => $content,
'headings' => $heading,
'android_channel_id'=>$set['one_cust']
);

$fields = json_encode($fields);

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array('Content-Type: application/json; charset=utf-8',
'Authorization: Basic '.$set['one_hash']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
$response = curl_exec($ch);
curl_close($ch);

$timestamp = date("Y-m-d H:i:s");

$title = 'Document Rejected Please Check Status!!';
$table="tbl_notification";
  $field_values=array("uid","datetime","description","title");
  $data_values=array("$id","$timestamp","$c_reason","$title");
  
      $h = new FunctionQuery($service);
	   $h->serviceinsertdata_Api($field_values,$data_values,$table); 
	   
	   
	  if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Document Rejected Successfully!!",
                        "message" => "Document section!",
                        "action" => "userlist.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "userlist.php",
                    ];
                }
	}
	elseif ($_POST["type"] == "edit_state") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
        $title = $service->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/state/";
        $url = "images/state/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            if (
                end($temp) != "jpg" &&
                end($temp) != "png" &&
                end($temp) != "jpeg"
            ) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                    "message" => "Upload Problem!!",
                    "action" => "add_state.php?id=" . $id . "",
                ];
            } else {
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_state";
                $field = ["status" => $okey, "img" => $url, "title" => $title];
                $where = "where id=" . $id . "";
                $h = new FunctionQuery($service);
                $check = $h->serviceupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Province Update Successfully!!",
                        "message" => "Province section!",
                        "action" => "list_state.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "add_state.php?id=" . $id . "",
                    ];
                }
            }
        } else {
            $table = "tbl_state";
            $field = ["status" => $okey, "title" => $title];
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Province Update Successfully!!",
                    "message" => "Province section!",
                    "action" => "list_state.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "add_state.php?id=" . $id . "",
                ];
            }
        }
    } elseif ($_POST["type"] == "edit_vehicle") {
        $okey = $_POST["status"];
		$sweight = $_POST["sweight"];
		$eweight = $_POST["eweight"];
        $id = $_POST["id"];
        $title = $service->real_escape_string($_POST["title"]);
        $target_dir = dirname(dirname(__FILE__)) . "/images/vehicle/";
        $url = "images/vehicle/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            if (
                end($temp) != "jpg" &&
                end($temp) != "png" &&
                end($temp) != "jpeg"
            ) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                    "message" => "Upload Problem!!",
                    "action" => "add_state.php?id=" . $id . "",
                ];
            } else {
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_vehicle";
                $field = ["status" => $okey, "img" => $url, "title" => $title,"min_weight"=>$sweight,"max_weight"=>$eweight];
                $where = "where id=" . $id . "";
                $h = new FunctionQuery($service);
                $check = $h->serviceupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Vehicle Update Successfully!!",
                        "message" => "Vehicle section!",
                        "action" => "list_vehicle.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "add_vehicle.php?id=" . $id . "",
                    ];
                }
            }
        } else {
            $table = "tbl_vehicle";
            $field = ["status" => $okey, "title" => $title,"min_weight"=>$sweight,"max_weight"=>$eweight];
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Vehicle Update Successfully!!",
                    "message" => "Vehicle section!",
                    "action" => "list_vehicle.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "add_vehicle.php?id=" . $id . "",
                ];
            }
        }
    }elseif ($_POST["type"] == "edit_banner") {
        $okey = $_POST["status"];
        $id = $_POST["id"];
		$b_type = $_POST["b_type"];
        $target_dir = dirname(dirname(__FILE__)) . "/images/banner/";
        $url = "images/banner/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            if (
                end($temp) != "jpg" &&
                end($temp) != "png" &&
                end($temp) != "jpeg"
            ) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                    "message" => "Upload Problem!!",
                    "action" => "add_Banner.php?id=" . $id . "",
                ];
            } else {
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "banner";
                $field = ["status" => $okey, "img" => $url,"b_type"=>$b_type];
                $where = "where id=" . $id . "";
                $h = new FunctionQuery($service);
                $check = $h->serviceupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Banner Update Successfully!!",
                        "message" => "banner section!",
                        "action" => "list_Banner.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "add_Banner.php?id=" . $id . "",
                    ];
                }
            }
        } else {
            $table = "banner";
            $field = ["status" => $okey,"b_type"=>$b_type];
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Banner Update Successfully!!",
                    "message" => "banner section!",
                    "action" => "list_Banner.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "add_Banner.php?id=" . $id . "",
                ];
            }
        }
    }  elseif ($_POST["type"] == "edit_payment") {
        $dname = mysqli_real_escape_string($service, $_POST["cname"]);
        $attributes = mysqli_real_escape_string($service, $_POST["p_attr"]);
        $ptitle = mysqli_real_escape_string($service, $_POST["ptitle"]);
        $okey = $_POST["status"];
        $id = $_POST["id"];
        $p_show = $_POST["p_show"];
        $target_dir = dirname(dirname(__FILE__)) . "/images/payment/";
        $url = "images/payment/";
        $temp = explode(".", $_FILES["cat_img"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["cat_img"]["name"] != "") {
            if (
                end($temp) != "jpg" &&
                end($temp) != "png" &&
                end($temp) != "jpeg"
            ) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                    "message" => "Upload Problem!!",
                    "action" => "edit_payment.php?id=" . $id . "",
                ];
            } else {
                move_uploaded_file(
                    $_FILES["cat_img"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_payment_list";
                $field = [
                    "title" => $dname,
                    "status" => $okey,
                    "img" => $url,
                    "attributes" => $attributes,
                    "subtitle" => $ptitle,
                    "p_show" => $p_show,
                ];
                $where = "where id=" . $id . "";
                $h = new FunctionQuery($service);
                $check = $h->serviceupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Payment Gateway Update Successfully!!",
                        "message" => "Payment Gateway section!",
                        "action" => "payment_method.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "edit_payment.php?id=" . $id . "",
                    ];
                }
            }
        } else {
            $table = "tbl_payment_list";
            $field = [
                "title" => $dname,
                "status" => $okey,
                "attributes" => $attributes,
                "subtitle" => $ptitle,
                "p_show" => $p_show,
            ];
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Payment Gateway Update Successfully!!",
                    "message" => "Payment Gateway section!",
                    "action" => "payment_method.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "edit_payment.php?id=" . $id . "",
                ];
            }
        }
    } elseif ($_POST["type"] == "add_faq") {
        $okey = $_POST["status"];
        $question = $service->real_escape_string($_POST["question"]);
        $answer = $service->real_escape_string($_POST["answer"]);

        $table = "tbl_faq";
        $field_values = ["question", "answer", "status"];
        $data_values = ["$question", "$answer", "$okey"];

        $h = new FunctionQuery($service);
        $check = $h->serviceinsertdata($field_values, $data_values, $table);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "FAQ Add Successfully!!",
                "message" => "FAQ section!",
                "action" => "list_faq.php",
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" =>
                    "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                "message" => "Operation DISABLED!!",
                "action" => "add_faq.php",
            ];
        }
    } elseif ($_POST["type"] == "add_code") {
        $okey = $_POST["status"];
        $title = $service->real_escape_string($_POST["title"]);

        $table = "tbl_code";
        $field_values = ["ccode", "status"];
        $data_values = ["$title", "$okey"];

        $h = new FunctionQuery($service);
        $check = $h->serviceinsertdata($field_values, $data_values, $table);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "Country Code Add Successfully!!",
                "message" => "Country Code section!",
                "action" => "list_country_code.php",
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" =>
                    "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                "message" => "Operation DISABLED!!",
                "action" => "add_country_code.php",
            ];
        }
    } elseif ($_POST["type"] == "edit_faq") {
        $okey = $_POST["status"];
        $question = $service->real_escape_string($_POST["question"]);
        $answer = $service->real_escape_string($_POST["answer"]);
        $id = $_POST["id"];
        $table = "tbl_faq";
        $field = [
            "status" => $okey,
            "answer" => $answer,
            "question" => $question,
        ];
        $where = "where id=" . $id . "";
        $h = new FunctionQuery($service);
        $check = $h->serviceupdateData($field, $table, $where);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "FAQ Update Successfully!!",
                "message" => "FAQ Code section!",
                "action" => "list_faq.php",
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" =>
                    "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                "message" => "Operation DISABLED!!",
                "action" => "add_faq.php?id=" . $id . "",
            ];
        }
    } elseif ($_POST["type"] == "edit_code") {
        $okey = $_POST["status"];
        $title = $service->real_escape_string($_POST["title"]);
        $id = $_POST["id"];
        $table = "tbl_code";
        $field = ["status" => $okey, "ccode" => $title];
        $where = "where id=" . $id . "";
        $h = new FunctionQuery($service);
        $check = $h->serviceupdateData($field, $table, $where);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "Country Code Update Successfully!!",
                "message" => "Country Code section!",
                "action" => "list_country_code.php",
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" =>
                    "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                "message" => "Operation DISABLED!!",
                "action" => "add_country_code.php?id=" . $id . "",
            ];
        }
    } elseif ($_POST["type"] == "add_page") {
        $ctitle = $service->real_escape_string($_POST["ctitle"]);
        $cstatus = $_POST["cstatus"];
        $cdesc = $service->real_escape_string($_POST["cdesc"]);
        $table = "tbl_page";

        $field_values = ["description", "status", "title"];
        $data_values = ["$cdesc", "$cstatus", "$ctitle"];

        $h = new FunctionQuery($service);
        $check = $h->serviceinsertdata($field_values, $data_values, $table);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "Page Add Successfully!!",
                "message" => "Page section!",
                "action" => "list_Page.php",
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" =>
                    "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                "message" => "Operation DISABLED!!",
                "action" => "add_Page.php",
            ];
        }
    } elseif ($_POST["type"] == "edit_page") {
        $id = $_POST["id"];
        $ctitle = $service->real_escape_string($_POST["ctitle"]);
        $cstatus = $_POST["cstatus"];
        $cdesc = $service->real_escape_string($_POST["cdesc"]);

        $table = "tbl_page";
        $field = [
            "description" => $cdesc,
            "status" => $cstatus,
            "title" => $ctitle,
        ];
        $where = "where id=" . $id . "";
        $h = new FunctionQuery($service);
        $check = $h->serviceupdateData($field, $table, $where);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "Page Update Successfully!!",
                "message" => "Page section!",
                "action" => "list_Page.php",
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" =>
                    "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                "message" => "Operation DISABLED!!",
                "action" => "add_Page.php?id=" . $id . "",
            ];
        }
    }  elseif ($_POST["type"] == "edit_profile") {
        $dname = $_POST["email"];
        $dsname = $_POST["password"];
        $id = $_POST["id"];
        $table = "admin";
        $field = ["username" => $dname, "password" => $dsname];
        $where = "where id=" . $id . "";
        $h = new FunctionQuery($service);
        $check = $h->serviceupdateData($field, $table, $where);
        if ($check == 1) {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "true",
                "title" => "Profile Update Successfully!!",
                "message" => "Profile  section!",
                "action" => "profile.php",
            ];
        } else {
            $returnArr = [
                "ResponseCode" => "200",
                "Result" => "false",
                "title" =>
                    "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                "message" => "Operation DISABLED!!",
                "action" => "profile.php",
            ];
        }
    } elseif ($_POST["type"] == "edit_setting") {
        $webname = mysqli_real_escape_string($service, $_POST["webname"]);
        $timezone = $_POST["timezone"];
        $currency = $_POST["currency"];
        $pstore = $_POST["pstore"];
        $id = $_POST["id"];
        $one_key = $_POST["one_key"];
        $one_hash = $_POST["one_hash"];
        $d_key = $_POST["d_key"];
        $d_hash = $_POST["d_hash"];
        $scredit = $_POST["scredit"];
        $rcredit = $_POST["rcredit"];

        $target_dir = dirname(dirname(__FILE__)) . "/images/website/";
        $url = "images/website/";
        $temp = explode(".", $_FILES["weblogo"]["name"]);
        $newfilename = round(microtime(true)) . "." . end($temp);
        $target_file = $target_dir . basename($newfilename);
        $url = $url . basename($newfilename);
        if ($_FILES["weblogo"]["name"] != "") {
            if (
                end($temp) != "jpg" &&
                end($temp) != "png" &&
                end($temp) != "jpeg"
            ) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "Sorry, only JPG, JPEG, PNG  files are allowed !!",
                    "message" => "Upload Problem!!",
                    "action" => "setting.php",
                ];
            } else {
                move_uploaded_file(
                    $_FILES["weblogo"]["tmp_name"],
                    $target_file
                );
                $table = "tbl_setting";
                $field = [
                    "timezone" => $timezone,
                    "weblogo" => $url,
                    "webname" => $webname,
                    "currency" => $currency,
                    "pstore" => $pstore,
                    "one_key" => $one_key,
                    "one_hash" => $one_hash,
                    "d_key" => $d_key,
                    "d_hash" => $d_hash,
                    "scredit" => $scredit,
                    "rcredit" => $rcredit,
                ];
                $where = "where id=" . $id . "";
                $h = new FunctionQuery($service);
                $check = $h->serviceupdateData($field, $table, $where);

                if ($check == 1) {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "true",
                        "title" => "Setting Update Successfully!!",
                        "message" => "Setting section!",
                        "action" => "setting.php",
                    ];
                } else {
                    $returnArr = [
                        "ResponseCode" => "200",
                        "Result" => "false",
                        "title" =>
                            "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                        "message" => "Operation DISABLED!!",
                        "action" => "setting.php",
                    ];
                }
            }
        } else {
            $table = "tbl_setting";
            $field = [
                "timezone" => $timezone,
                "webname" => $webname,
                "currency" => $currency,
                "pstore" => $pstore,
                "one_key" => $one_key,
                "one_hash" => $one_hash,
                "d_key" => $d_key,
                "d_hash" => $d_hash,
                "scredit" => $scredit,
                "rcredit" => $rcredit,
            ];
            $where = "where id=" . $id . "";
            $h = new FunctionQuery($service);
            $check = $h->serviceupdateData($field, $table, $where);
            if ($check == 1) {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "true",
                    "title" => "Setting Update Successfully!!",
                    "message" => "Offer section!",
                    "action" => "setting.php",
                ];
            } else {
                $returnArr = [
                    "ResponseCode" => "200",
                    "Result" => "false",
                    "title" =>
                        "For Demo purpose all  Insert/Update/Delete are DISABLED !!",
                    "message" => "Operation DISABLED!!",
                    "action" => "setting.php",
                ];
            }
        }
    } 
}
echo json_encode($returnArr);
