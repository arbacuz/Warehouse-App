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

$sql = "SELECT * FROM ".TB_REGISTER." r INNER JOIN ".TB_BRANCH." b ON r.branchID=b.branchID INNER JOIN ".TB_ORDER." o ON o.branchID=b.branchID WHERE b.branchID=$branchID";
$query=$db->querydb($sql);
if($query){
	if($result=$db->fetch($query)){
		$registerID = $result["registerID"];
		$orderID = $result["orderID"];
		$condition1 = "itemID=".$itemID." AND registerID= ".$registerID;
		$condition2 = "itemID=".$itemID." AND orderID= ".$orderID;
		$condition3 = "itemID=".$itemID." AND branchID= ".$branchID;
		$query1 = $db->delete(TB_REGISTERITEM,$condition1);
		$query2 = $db->delete(TB_ORDERITEM,$condition2);
		$query3 = $db->delete(TB_ITEMBRANCH,$condition3);
	}
}

$arr = array();
if($query1&&$query2&&$query3){
	if($query) {
		$arr["status"] = "success";
		$arr["messages"] = "Complete deleting data in itemBranch table.";
	} else {
		$arr["status"] = "error";
		$arr["messages"] = "Error occure when you delete the data in itemBranch table.";
	}
}

#-> Return json data.
echo json_encode($json);

#-> Close database.
$db->closedb();

?>