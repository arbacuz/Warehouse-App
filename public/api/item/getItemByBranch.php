<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> GET,ADD,DEL,EDIT
//$db->......

#-> Preparing data.
$arr = array();
if($query) {
	$result = $db->fetch($query);
	// ASSIGN DATA TO ARRAY
} else {
	// IF NO RESULT
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>