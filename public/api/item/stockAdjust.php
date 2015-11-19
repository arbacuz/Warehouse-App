<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$items= $json->items;
$staff = $json->user->attributes->_id;
$branchID = $json->user->relationships->branch->_id;

#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

$i=0;
while($items[$i]){
	$itemID = $items[$i]->attributes->_id;
	$qty = $items[$i]->attributes->quantity;
	$lastUpdatedDate = date('Y-m-d H:i:s');
	$sql="SELECT * FROM ".TB_BRANCH." b INNER JOIN ".TB_ITEMBRANCH." bi ON b.branchID=bi.branchID INNER JOIN ".TB_ITEM." i ON bi.itemID=i.itemID WHERE b.branchID = $branchID AND i.itemID= $itemID ";
	$query = $db->querydb($sql);
	if($result=$db->fetch($query)){
		#Update itembranch
		$data = array(
			"lastUpdatedDate" => $lastUpdatedDate,
			"quantity" => $qty,
			"staffID" => $staff
			);
		$table = TB_ITEMBRANCH;
		$where = "itemID = $itemID AND branchID = $branchID";
		$query = $db->update($table,$data,$where);		
	}
	$i++;#Get next item
}
#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$arr["messages"] = "Complete adjust data to itemBranch and item table.";
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you updating the data to itemBranch and item table.";
}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>