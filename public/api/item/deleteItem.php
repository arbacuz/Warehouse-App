<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
// var_dump($json);
$itemID = $json->item->attributes->_id;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);
#-> Delete Data

$table = TB_ITEM;
$where = "itemID = $itemID";
$query = $db->delete($table,$where);
$arr = array();
if($query){
	if($query) {
		$arr["status"] = "success";
		$arr["messages"] = "Complete deleting data in item table.";
	} else {
		$arr["status"] = "error";
		$arr["messages"] = "Error occure when you delete the data in item table.";
	}
}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>