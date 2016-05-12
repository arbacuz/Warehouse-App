<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$itemID = $json->item->attributes->_id;
$itemName = $json->item->attributes->name;
// $typeID = $json->item->->relationships->type->attribute->_id;
$costPerUnit = $json->item->attributes->cost;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$table = TB_ITEM;
$data = array(
	"itemName" => $itemName,
	"costPerUnit" => $costPerUnit
	);
$where = "itemID = '$itemID'";
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