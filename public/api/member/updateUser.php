<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
// $staffName = $json->staffName;
$staffID = 4;
$staffName = "name1asasdasddasd";
$email = "a@a.comasdaasdasdsd";
$password = "1234asdasdasdasdsasd";
$positionID = 1;
$branchID = 1;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Update the data.
$table = TB_STAFF;
$data = array(
	"staffName" => $staffName,
	"email" => $email,
	"password" => $password,
	"positionID" => $positionID,
	"branchID" => $branchID
	);
$where = "staffID = '$staffID'";
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