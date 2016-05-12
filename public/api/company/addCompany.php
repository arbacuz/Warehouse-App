<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);

$companyName = $json->company->name;
$companyAddress = $json->company->address;
$companyTel = $json->company->telephone;
$companyTypeID = $json->company->type->attributes->_id;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

//ADD COMPANY TABLE 
if((strlen($companyName) == 0)||($companyTypeID==null)) {
	$query = false;
} else {
	$table = TB_COMPANY;
	$data = array("companyName"=> $companyName,
			"companyAddress"=> $companyAddress,
			"companyTel"=> $companyTel,
			"companyTypeID"=>$companyTypeID
			);
	$query = $db->add($table,$data);
}

#-> Prepaing data for return.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Add Company Successfull";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Some missing values, failed to add";
}

#-> Return the data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>