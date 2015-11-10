<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$item = $json->items;
$itemID = $json->items->_id;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);
#-> Delete Data
$condition = "itemID=".$itemID;
$query = $db->delete(TB_ITEM,$condition);

$arr = array();
if($query){
	if($query) {
		$arr["status"] = "success";
		$arr["messages"] = "Complete deleting data in .$table.table.";
	} else {
		$arr["status"] = "error";
		$arr["messages"] = "Error occure when you delete the data in .$table. table.";
	}
}
#-> Return json data.
echo json_encode($json);

#-> Close database.
$db->closedb();

?>