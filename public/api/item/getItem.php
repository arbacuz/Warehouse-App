<?php
#-> Include config and class files.
include_once("../../includes/config.php");
include_once("../../includes/class_mysql.php");

#-> Get data from js and initialize
$data = file_get_contents("php://input");
$json = json_decode($data);
$itemID = $json->$items->_id;


#-> Connect to the database
$db = new Database();
$db->connectdb(DB_NAME,DB_USER,DB_PASS);

#-> Query the data.
$query = $db->querydb("SELECT * FROM ".TB_ITEM. "WHERE itemID =".$itemID);

#-> Preparing return data.
$arr = array();
if($query) {
	$arr["status"] = "success";
	$i = 0;
	while($result = $db->fetch($query)) {
		$arr["data"][$i]["attributes"]["itemID"] = $result["itemID"];
		$arr["data"][$i]["attributes"]["itemCode"] = $result["itemCode"];
		$arr["data"][$i]["attributes"]["itemName"] = $result["itemName"];
		$sql = "SELECT typeName FROM ".TB_ITEMTYPE." WHERE typeID =".$result["typeID"].";";
		$query = $db->querydb($sql);
		if($query){
			if($fetchdata = $db->fetch($query)){
				$arr["data"][$i]["attributes"]["type"]=$fetchdata["typeName"];
			}
		}
		$arr["data"][$i]["attributes"]["costPerUnit"] = $result["costPerUnit"];
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