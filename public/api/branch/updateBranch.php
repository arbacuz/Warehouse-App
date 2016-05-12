<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$branchID = $json->branch->attributes->_id;
$branchName = $json->branch->attributes->name;
$branchAddress = $json->branch->attributes->address;
$branchTel = $json->branch->attributes->telephone;
$capacity = $json->branch->attributes->capacity;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Update the data.
$table = TB_BRANCH;
$data = array(
	"branchName" => $branchName,
	"branchAddress" => $branchAddress,
	"branchTel" => $branchTel,
	"capacity" => $capacity
	);
$where = "branchID = '$branchID'";
$query = $db->update($table,$data,$where);

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete updating data to $table table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you updating the data to $table table.";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>