<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$positionName = $json->position->name;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Add the data.
$table = TB_POSITION;
$data = array(
	"positionName" => $positionName
	);
$query = $db->add($table,$data);

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adding data to $table table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you add the data to $table table.";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>