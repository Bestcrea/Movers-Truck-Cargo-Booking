<?php

require 'config.php';

class FunctionQuery {

    private $service;

    public function __construct($service) {
        $this->service = $service;
    }

    public function servicelogin($username, $password, $tblname) {

        if($tblname == 'admin') {
            $q = "select * from ".$tblname." where username='".$username."' and password='".$password."'";
            return $this->service->query($q)->num_rows;
        } else {
            $q = "select * from ".$tblname." where email='".$username."' and password='".$password."'";
            return $this->service->query($q)->num_rows;
        }
    }

    public function serviceinsertdata($field, $data, $table) {
        $field_values= implode(',', $field);
        $data_values=implode("','", $data);

        $sql = "INSERT INTO $table($field_values) VALUES('$data_values')";
        $result = $this->service->query($sql);
        return $result;
    }

   public function serviceinsertdata_id($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result= $this->service->query($sql);
  return $this->service->insert_id;
  }
  
 public function serviceinsertdata_Api($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$this->service->query($sql);
  return $result;
  }
  
 public function serviceinsertdata_Api_Id($field,$data,$table){

    $field_values= implode(',',$field);
    $data_values=implode("','",$data);

    $sql = "INSERT INTO $table($field_values)VALUES('$data_values')";
    $result=$this->service->query($sql);
  return $this->service->insert_id;
  }
  
 public function serviceupdateData($field,$table,$where){
$cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
			
           $cols[] = "$key = '$val'"; 
			
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
$result=$this->service->query($sql);
    return $result;
  }
  
  
  public function serviceupdateData_Api($field,$table,$where){
$cols = array();

    foreach($field as $key=>$val) {
        if($val != NULL) // check if value is not null then only add that colunm to array
        {
           $cols[] = "$key = '$val'"; 
        }
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " $where";
$result=$this->service->query($sql);
    return $result;
  }
  
  
  
  
 public function serviceupdateData_single($field,$table,$where){
$query = "UPDATE $table SET $field";

$sql =  $query.' '.$where;
$result=$this->service->query($sql);
  return $result;
  }
  
 public function serviceDeleteData($where,$table){

    $sql = "Delete From $table $where";
    $result=$this->service->query($sql);
  return $result;
  }
  
 public function serviceDeleteData_Api($where,$table){

    $sql = "Delete From $table $where";
    $result=$this->service->query($sql);
  return $result;
  }

}

?>