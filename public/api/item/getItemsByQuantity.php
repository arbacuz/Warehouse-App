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
$query = $db->querydb("SELECT * FROM ".TB_ITEMBRANCH. " ib INNER JOIN ".TB_STAFF." sf ON ib.staffID=sf.staffID INNER JOIN "
	.TB_ITEM." i ON ib.itemID=i.itemID INNER JOIN "
	.TB_ITEMTYPE." it ON i.typeID = it.typeID WHERE ib.branchID =".$branchID." AND quantity <= ".$qty);

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$i = 0;
	while($result = $db->fetch($query)){
	$arr["data"][$i]["attributes"]["_id"] = $result["itemID"];
	$arr["data"][$i]["attributes"]["code"] = $result["itemCode"];
	$arr["data"][$i]["attributes"]["name"] = $result["itemName"];
	$arr["data"][$i]["attributes"]["cost"] = $result["costPerUnit"];
	$arr["data"][$i]["relationships"]["type"]["name"]=$result["typeName"];
	$arr["data"][$i]["relationships"]["type"]["_id"]=$result["typeID"];
	$arr["data"][$i]["attributes"]["quantity"] = $result["quantity"];
	$arr["data"][$i]["attributes"]["lastupdateddate"] = $result["lastUpdatedDate"];
	$arr["data"][$i]["relationships"]["staff"]["name"] = $result["staffName"];
	$arr["data"][$i]["relationships"]["branch"]["name"]=$branchName;
	$i++;
	}
}
else {
	$arr["status"] = "error";
	$arr["messages"] = "Cannot get the item.";
	
}
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>