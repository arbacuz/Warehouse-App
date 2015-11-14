<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$itemName = $json->items->name;
$itemCode = $json->items->code;
$typeID = $json->items->type->attribute->_id;
$costPerUnit = $json->items->cost;
$staff = $json->staff->_id;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$sql="SELECT * FROM ".TB_BRANCH." b INNER JOIN ".TB_ITEMBRANCH." bi ON b.branchID=bi.branchID INNER JOIN ".TB_ITEM." i ON bi.itemID=i.itemID WHERE b.branchID = $branchID AND i.itemName='$itemName'";
$query = $db->querydb($sql);
if($query){
	if($result=$db->fetch($query)){
		$qty=$result["quantity"];
		$branchID = $result["branchID"];
		$itemID = $result["itemID"];
		$data = array(
			"branchID" => $branchID,
			"itemID" => $itemID,
			"lastUpdatedDate" => $lastUpdatedDate,
			"quantity" => $qty,
			"staff" => $staff
			);
		$where = "itemID = '$itemID' AND branchID = '$branchID'";
		$query = $db->update($table,$data,$where);		
	}	
	$table = TB_ITEM;
	$data = array(
		"itemName" => $itemName,
		"itemCode" => $itemCode,
		"typeID" => $typeID,
		"costPerUnit" => $costPerUnit
		);
	$where = "itemID = $itemID";
	$query = $db->update($table,$data,$where);
}
#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adjust data to $table table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you updating the data to $table table.";
}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>