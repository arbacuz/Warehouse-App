<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_ITEM.";");
#-> Preparing return data.
$i =0;
$arr = array();
if($query){
	while($itemData = $db->fetch($query)){
		$arr["data"][$i]["attributes"]["_id"] = $itemData["itemID"];
		$arr["data"][$i]["attributes"]["code"] = $itemData["itemCode"];
		$arr["data"][$i]["attributes"]["name"] = $itemData["itemName"];
		$arr["data"][$i]["attributes"]["cost"] = $itemData["costPerUnit"];
		$sql = "SELECT typeName FROM ".TB_ITEMTYPE." WHERE typeID =".$itemData["typeID"].";";
		$subquery = $db->querydb($sql);
		if($subquery){
			if($itemTypeData = $db->fetch($subquery)){
				$arr["data"][$i]["relationships"]["type"]["name"]=$itemTypeData["typeName"];
			}
		}
		$i++;
	}
}else{
	$arr["status"] = "error";
	$arr["messages"] = "Error occure when you query the data to item table.";
	echo json_encode($arr);
	exit();
}
$arr["status"] = "success";
$arr["messages"] = "success query all items";
#-> Return json data.
echo json_encode($arr);

#-> Close database.
$db->closedb();

?>