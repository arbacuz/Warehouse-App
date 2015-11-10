<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$branchID = $json->branch->_id;
$branchName = $json->branch->name;
$qty = $json->quantity;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_ITEMBRANCH. "WHERE branchID =".$branchID." AND quantity=".$qty);

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$i = 0;
	while($itemBranchData = $db->fetch($query)){
		#Query item name from itemID
		$sql = "SELECT * FROM ".TB_ITEM." WHERE itemID =".$itemTypeData["itemID"].";";
		$subquery = $db->querydb($sql);
		if($subquery){
			if($itemData = $db->fetch($subquery)){
				$arr["data"][$i]["attributes"]["itemID"] = $itemData["itemID"];
				$arr["data"][$i]["attributes"]["itemCode"] = $itemData["itemCode"];
				$arr["data"][$i]["attributes"]["itemName"] = $itemData["itemName"];
				$arr["data"][$i]["attributes"]["costPerUnit"] = $itemData["costPerUnit"];
				$sql = "SELECT typeName FROM ".TB_ITEMTYPE." WHERE typeID =".$itemData["typeID"].";";
				$subquery = $db->querydb($sql);
				if($subquery){
					if($itemTypeData = $db->fetch($subquery)){
						$arr["data"][$i]["attributes"]["type"]=$itemTypeData["typeName"];
					}
				}
			}
		}
		#Query staff name from staffID
		$sql = "SELECT * FROM ".TB_STAFF." WHERE staffID =".$itemBranchData["staffID"].";";
		$subquery = $db->querydb($sql);
		if($subquery){
			if($staffData = $db->fetch($subquery)){
				$arr["data"][$i]["attributes"]["staffName"] = $staffData["staffName"];
			}
		}
		$arr["data"][$i]["attributes"]["branchName"]=$branchName;
		$arr["data"][$i]["attributes"]["lastUpdatedDate"] = $itemBranchData["lastUpdatedDate"];
		$i ++;
	}
} else {
	$arr["status"] = "error";
}


#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>