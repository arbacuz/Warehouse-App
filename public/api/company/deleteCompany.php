<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$companyID = $json->company->attributes->_id;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

//DELETE COMPANY TABLE 
$table = TB_COMPANY;
$where = "companyID=$companyID";
$query = $db->delete($table,$where);

#-> Preparing the data for return.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Delete company successfully";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Failed to delete";
}

#-> Return the data.
echo json_encode($arr);
		 
#-> Close database.
$db->closedb();

?>