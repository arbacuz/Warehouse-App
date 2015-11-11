<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$staffID = $json->user->attributes->_id;
// $staffID = 3;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Delete the data.
$table = TB_STAFF;
$where = "staffID = '$staffID'";
$query = $db->delete($table,$where);

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete deleting data in $table table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you delete the data in $table table.";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>