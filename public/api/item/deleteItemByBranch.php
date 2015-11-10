<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$itemID = $json->items->_id;
$branchID = $json->branch->_id;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);
#-> Delete Data
$condition = "itemID=".$itemID." AND branchID= ".$branchID;
$query = $db->delete(TB_ITEMBRANCH,$condition);

$arr = array();
if($query){
	if($query) {
		$arr["status"] = "success";
		$arr["messages"] = "Complete deleting data in .$table. table.";
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