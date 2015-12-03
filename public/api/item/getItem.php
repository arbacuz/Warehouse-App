<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$itemID = $json->item->_id;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_ITEM." i INNER JOIN ".TB_ITEMTYPE." it ON i.typeID=it.typeID INNER JOIN ".TB_ITEMBRANCH." ib ON i.itemID = ib.itemID INNER JOIN ".TB_BRANCH." b ON b.branchID = ib.branchID WHERE i.itemID = $itemID");

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	while($result = $db->fetch($query)) {
		$arr["data"]["attributes"]["_id"] = $result["itemID"];
		$arr["data"]["attributes"]["code"] = $result["itemCode"];
		$arr["data"]["attributes"]["name"] = $result["itemName"];
		$arr["data"]["attributes"]["cost"] = $result["costPerUnit"];
		$arr["data"]["relationships"]["type"]["_id"]=$result["typeID"];
		$arr["data"]["relationships"]["type"]["name"]=$result["typeName"];
		$arr["data"]["relationships"]["branch"]["_id"]=$result["branchID"];
		$arr["data"]["relationships"]["branch"]["name"]=$result["branchName"];
		$arr["data"]["relationships"]["branch"]["address"]=$result["branchAddress"];
		$arr["data"]["relationships"]["branch"]["telephone"]=$result["branchTel"];
	}
} else {
	$arr["status"] = "error";
	$arr["messages"] = "Cannot get the item.";
}

#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>